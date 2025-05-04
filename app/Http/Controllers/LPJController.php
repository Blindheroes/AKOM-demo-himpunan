<?php

namespace App\Http\Controllers;

use App\Models\LpjTemplate;
use App\Models\Lpj;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Event as ModelsEvent;
use Symfony\Contracts\EventDispatcher\Event;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LPJController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of all LPJs.
     */
    public function index(Request $request)
    {
        $query = Lpj::query()->with(['event', 'creator']); // Eager load relationships

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhereHas('event', function ($q2) use ($search) {
                        $q2->where('title', 'like', "%{$search}%");
                    });
            });
        }

        // Apply filters if any
        if ($request->filled('event')) {
            $query->where('event_id', $request->event);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Staff can only see their own LPJs and those from their department
        if (Auth::user()->role === 'staff') {
            $query->where(function ($q) {
                $q->where('created_by', Auth::id())
                    ->orWhereHas('event', function ($q2) {
                        $q2->where('department_id', Auth::user()->department_id);
                    });
            });
        }

        $lpjs = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Get events for the filter dropdown
        $events = ModelsEvent::where('status', 'completed')->get();

        return view('lpjs.index', compact('lpjs', 'events'));
    }

    /**
     * Show the form for creating a new LPJ.
     */
    public function create(ModelsEvent $event, LpjTemplate $template)
    {
        $this->authorize('create', [Lpj::class, $event]);

        // Make sure event has ended
        if ($event->end_date > now()) {
            return back()->with('error', 'Event belum selesai');
        }

        // Create LPJ with template structure
        $lpj = new Lpj();
        $lpj->title = $event->title . ' - LPJ';
        $lpj->event_id = $event->id;
        $lpj->template_id = $template->id;
        $lpj->content = $template->structure; // Empty structure
        $lpj->created_by = Auth::id();
        $lpj->save();

        return redirect()->route('lpjs.edit', $lpj);
    }

    /**
     * Show form to select a template for LPJ creation after selecting an event.
     */
    public function selectEventTemplate(Request $request)
    {
        // Validate the event ID
        $validated = $request->validate([
            'event' => 'required|exists:events,id'
        ]);

        $event = ModelsEvent::findOrFail($request->event);

        // Make sure event has ended
        if ($event->end_date > now()) {
            return redirect()->route('lpjs.index')
                ->with('error', 'Event belum selesai. LPJ hanya dapat dibuat untuk event yang sudah selesai.');
        }

        // Get all active LPJ templates
        $templates = LpjTemplate::where('is_active', true)->get();

        if ($templates->isEmpty()) {
            return redirect()->route('lpjs.index')
                ->with('error', 'No active LPJ templates available. Please contact an administrator.');
        }

        return view('lpjs.select-template', compact('event', 'templates'));
    }

    /**
     * Create an LPJ using the specified event and template.
     */
    public function storeWithTemplate(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'template_id' => 'required|exists:lpj_templates,id'
        ]);

        $event = ModelsEvent::findOrFail($validated['event_id']);
        $template = LpjTemplate::findOrFail($validated['template_id']);

        // Check if a LPJ for this event already exists
        $existingLpj = Lpj::where('event_id', $event->id)->first();
        if ($existingLpj) {
            return redirect()->route('lpjs.edit', $existingLpj)
                ->with('info', 'An LPJ for this event already exists. You can edit it here.');
        }

        // Create LPJ with template structure
        $lpj = new Lpj();
        $lpj->title = $event->title . ' - LPJ';
        $lpj->event_id = $event->id;
        $lpj->template_id = $template->id;
        $lpj->content = $template->structure;
        $lpj->status = 'draft';
        $lpj->created_by = Auth::id();
        $lpj->save();

        return redirect()->route('lpjs.edit', $lpj)
            ->with('success', 'LPJ created successfully. You can now edit the content.');
    }

    /**
     * Display the specified LPJ.
     */
    public function show(Lpj $lpj)
    {
        $this->authorize('view', $lpj);

        return view('lpjs.show', compact('lpj'));
    }

    /**
     * Show the form for editing the specified LPJ.
     */
    public function edit(Lpj $lpj)
    {
        $this->authorize('update', $lpj);

        // Can't edit already approved LPJs
        if ($lpj->status === 'approved') {
            return redirect()->route('lpjs.show', $lpj)
                ->with('error', 'Approved LPJs cannot be edited.');
        }

        return view('lpjs.edit', compact('lpj'));
    }

    /**
     * Update the specified LPJ.
     */
    public function update(Request $request, Lpj $lpj)
    {
        $this->authorize('update', $lpj);

        // Can't update already approved LPJs
        if ($lpj->status === 'approved') {
            return redirect()->route('lpjs.show', $lpj)
                ->with('error', 'Approved LPJs cannot be updated.');
        }

        $validated = $request->validate([
            'content' => 'required|array',
            'status' => 'sometimes|in:draft,pending'
        ]);

        $lpj->content = $validated['content'];

        // Update status if specified
        if (isset($validated['status'])) {
            $lpj->status = $validated['status'];

            // Set submission date if status changed to pending
            if ($validated['status'] === 'pending') {
                $lpj->submitted_at = now();
            }
        }

        $lpj->save();

        return redirect()->route('lpjs.show', $lpj)
            ->with('success', 'LPJ updated successfully.');
    }

    /**
     * Remove the specified LPJ.
     */
    public function destroy(Lpj $lpj)
    {
        $this->authorize('delete', $lpj);

        // Can't delete approved LPJs
        if ($lpj->status === 'approved') {
            return redirect()->route('lpjs.show', $lpj)
                ->with('error', 'Approved LPJs cannot be deleted.');
        }

        $lpj->delete();

        return redirect()->route('lpjs.index')
            ->with('success', 'LPJ deleted successfully.');
    }

    public function generatePDF(Lpj $lpj)
    {
        $this->authorize('view', $lpj);

        $data = [
            'lpj' => $lpj,
            'event' => $lpj->event,
            'creator' => $lpj->creator,
            'template' => $lpj->template
        ];

        $pdf = PDF::loadView('lpjs.pdf', $data);

        return $pdf->download($lpj->title . '.pdf');
    }

    /**
     * Approve the specified LPJ.
     */
    public function approve(Request $request, Lpj $lpj)
    {
        $this->authorize('approve', $lpj);

        // Only pending LPJs can be approved
        if ($lpj->status !== 'pending') {
            return redirect()->route('lpjs.show', $lpj)
                ->with('error', 'Only pending LPJs can be approved.');
        }

        $lpj->status = 'approved';
        $lpj->approved_by = Auth::id();
        $lpj->approved_at = now();
        $lpj->approval_notes = $request->input('approval_notes');
        $lpj->save();

        return redirect()->route('lpjs.show', $lpj)
            ->with('success', 'LPJ approved successfully.');
    }

    /**
     * Reject the specified LPJ.
     */
    public function reject(Request $request, Lpj $lpj)
    {
        $this->authorize('approve', $lpj);

        // Only pending LPJs can be rejected
        if ($lpj->status !== 'pending') {
            return redirect()->route('lpjs.show', $lpj)
                ->with('error', 'Only pending LPJs can be rejected.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $lpj->status = 'rejected';
        $lpj->rejected_by = Auth::id();
        $lpj->rejected_at = now();
        $lpj->rejection_reason = $validated['rejection_reason'];
        $lpj->save();

        return redirect()->route('lpjs.show', $lpj)
            ->with('success', 'LPJ rejected successfully.');
    }

    /**
     * Download the LPJ document.
     */
    public function download(Lpj $lpj)
    {
        $this->authorize('view', $lpj);

        return $this->generatePDF($lpj);
    }
}
