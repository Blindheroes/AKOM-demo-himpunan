<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Letter;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\LetterTemplate;
use App\Models\LetterNumberFormat;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\LetterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LetterController extends Controller
{
    use AuthorizesRequests;
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('role:staff,executive,admin')->except(['show', 'download']);
    // }

    public function index(Request $request)
    {
        $query = Letter::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('letter_number', 'like', "%{$search}%")
                    ->orWhere('regarding', 'like', "%{$search}%")
                    ->orWhere('recipient', 'like', "%{$search}%")
                    ->orWhere('recipient_institution', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by department if specified
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Staff can only view letters from their department or ones they created
        if (Auth::user()->role === 'staff') {
            $query->where(function ($q) {
                $q->where('department_id', Auth::user()->department_id)
                    ->orWhere('created_by', Auth::id());
            });
        }

        $letters = $query->orderBy('date', 'desc')->paginate(15);
        $departments = Department::all();

        return view('letters.index', compact('letters', 'departments'));
    }

    public function create()
    {
        $templates = LetterTemplate::where('is_active', true)->get();
        $departments = Department::all();

        // Staff can only select their own department
        if (Auth::user()->role === 'staff') {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('letters.create', compact('templates', 'departments'));
    }

    public function store(LetterRequest $request)
    {
        $letter = new Letter($request->validated());
        $letter->created_by = Auth::id();

        // Generate letter number if department is specified
        if ($request->has('department_id')) {
            $format = LetterNumberFormat::where('department_id', $request->department_id)
                ->where('is_active', true)
                ->first();

            if ($format) {
                $number = $format->next_number;
                $pattern = $format->format_pattern;

                // Replace placeholders in the pattern
                $date = now();
                $pattern = str_replace('{YEAR}', $date->format('Y'), $pattern);
                $pattern = str_replace('{MONTH}', $date->format('m'), $pattern);
                $pattern = str_replace('{NUMBER}', str_pad($number, 3, '0', STR_PAD_LEFT), $pattern);

                // Get department slug
                $department = Department::find($request->department_id);
                $pattern = str_replace('{DEPT}', $department ? $department->slug : 'DEPT', $pattern);

                $letter->letter_number = $pattern;

                // Increment number in format
                $format->next_number++;
                $format->save();
            }
        }

        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Letter created successfully. You can now edit or sign it.');
    }

    public function show(Letter $letter)
    {
        // Authorization check
        $this->authorize('view', $letter);

        return view('letters.show', compact('letter'));
    }

    public function edit(Letter $letter)
    {
        // Authorization check
        $this->authorize('update', $letter);

        $templates = LetterTemplate::where('is_active', true)->get();
        $departments = Department::all();

        // Staff can only select their own department
        if (Auth::user()->role === 'staff') {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('letters.edit', compact('letter', 'templates', 'departments'));
    }

    public function update(LetterRequest $request, Letter $letter)
    {
        // Authorization check
        $this->authorize('update', $letter);

        // Prevent editing of signed letters
        if (in_array($letter->status, ['signed', 'sent', 'archived'])) {
            return redirect()->route('letters.show', $letter)
                ->with('error', 'Signed or sent letters cannot be edited.');
        }

        $letter->fill($request->validated());
        $letter->version++;
        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Letter updated successfully.');
    }

    public function destroy(Letter $letter)
    {
        // Authorization check
        $this->authorize('delete', $letter);

        // Delete document if exists
        if ($letter->document_path) {
            Storage::delete($letter->document_path);
        }

        $letter->delete();

        return redirect()->route('letters.index')
            ->with('success', 'Letter deleted successfully.');
    }

    public function sign(Letter $letter)
    {
        // Authorization check
        $this->authorize('sign', $letter);

        // Check if user has signature authority
        if (!Auth::user()->signature_authority) {
            return redirect()->route('letters.show', $letter)
                ->with('error', 'You do not have signature authority.');
        }

        // Check if user has a signature
        $signature = Auth::user()->signature;
        if (!$signature || !$signature->is_active) {
            return redirect()->route('letters.show', $letter)
                ->with('error', 'You do not have an active digital signature.');
        }

        // Update letter status
        $letter->signed_by = Auth::id();
        $letter->signing_date = now();
        $letter->status = 'signed';

        // Generate PDF with signature
        try {
            $pdf = PDF::loadView('letters.pdf', [
                'letter' => $letter,
                'signature' => $signature
            ]);

            $filename = 'letter_' . $letter->id . '_signed.pdf';
            $path = 'letters/' . $filename;

            Storage::put('public/' . $path, $pdf->output());
            $letter->document_path = $path;
        } catch (\Exception $e) {
            Log::error('Failed to generate signed letter PDF: ' . $e->getMessage());
            return redirect()->route('letters.show', $letter)
                ->with('error', 'Failed to generate signed letter PDF. Please try again.');
        }

        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Letter signed successfully.');
    }

    public function download(Letter $letter)
    {
        // Authorization check
        $this->authorize('view', $letter);

        // Check if document exists
        if (!$letter->document_path) {
            // Generate PDF on the fly if no document exists
            $pdf = PDF::loadView('letters.pdf', ['letter' => $letter]);
            return $pdf->download($letter->title . '.pdf');
        }

        // Otherwise return the stored document
        return Storage::download('public/' . $letter->document_path, $letter->title . '.pdf');
    }

    public function markAsSent(Letter $letter)
    {
        // Authorization check
        $this->authorize('update', $letter);

        // Check if letter is signed
        if ($letter->status !== 'signed') {
            return redirect()->route('letters.show', $letter)
                ->with('error', 'Only signed letters can be marked as sent.');
        }

        $letter->status = 'sent';
        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Letter marked as sent successfully.');
    }

    public function archive(Letter $letter)
    {
        // Authorization check
        $this->authorize('update', $letter);

        // Only sent letters can be archived
        if ($letter->status !== 'sent') {
            return redirect()->route('letters.show', $letter)
                ->with('error', 'Only sent letters can be archived.');
        }

        $letter->status = 'archived';
        $letter->save();

        return redirect()->route('letters.show', $letter)
            ->with('success', 'Letter archived successfully.');
    }
}
