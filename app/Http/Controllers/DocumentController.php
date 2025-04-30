<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Services\FileUploadService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\DocumentRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DocumentController extends Controller
{
    use AuthorizesRequests;
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of documents.
     */
    public function index(Request $request)
    {
        $query = Document::query();

        // Apply filters
        if ($request->has('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Apply visibility restrictions based on user role
        $userRole = Auth::check() ? Auth::user()->role : 'guest';

        switch ($userRole) {
            case 'admin':
                // Admin can see all documents
                break;
            case 'executive':
                // Executives can see public, members, and executive documents
                $query->whereIn('visibility', ['public', 'members', 'executives']);
                break;
            case 'staff':
                // Staff can see public, members, and documents from their department
                $query->where(function ($q) {
                    $q->whereIn('visibility', ['public', 'members'])
                        ->orWhere(function ($q2) {
                            $q2->where('visibility', 'executives')
                                ->where('department_id', Auth::user()->department_id);
                        });
                });
                break;
            case 'member':
                // Members can see public and member documents
                $query->whereIn('visibility', ['public', 'members']);
                break;
            default:
                // Guests can only see public documents
                $query->where('visibility', 'public');
                break;
        }

        // Only show published documents to regular users
        if (!in_array($userRole, ['admin', 'executive'])) {
            $query->where('status', 'published');
        }

        $documents = $query->orderBy('created_at', 'desc')->paginate(15);
        $departments = Department::all();
        $categories = Document::select('category')->distinct()->pluck('category');

        return view('documents.index', compact('documents', 'departments', 'categories'));
    }

    /**
     * Show the form for creating a new document.
     */
    public function create()
    {
        $departments = Department::all();

        // Staff can only upload documents for their department
        if (Auth::user()->role === 'staff') {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        $categories = ['report', 'proposal', 'minutes', 'regulation', 'certificate', 'other'];
        $visibilities = ['public', 'members', 'executives', 'admin'];

        return view('documents.create', compact('departments', 'categories', 'visibilities'));
    }

    /**
     * Store a newly created document.
     */
    public function store(DocumentRequest $request)
    {
        $document = new Document($request->validated());
        $document->uploaded_by = Auth::id();

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Define allowed extensions based on document category
            $allowedExtensions = match ($request->category) {
                'certificate' => ['pdf', 'jpg', 'jpeg', 'png'],
                default => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
            };

            // Store file with security checks
            $path = $this->fileUploadService->store(
                $file,
                'documents',
                $allowedExtensions,
                10 * 1024 * 1024 // 10MB max
            );

            if (!$path) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid file. Please ensure your file meets the requirements.');
            }

            $document->file_path = $path;
            $document->file_type = $file->getMimeType();
            $document->file_size = $file->getSize();
        } else {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Document file is required.');
        }

        // Set document status based on user role
        if (in_array(Auth::user()->role, ['admin', 'executive'])) {
            $document->status = 'published';
            $document->approved_by = Auth::id();
            $document->approval_date = now();
        } else {
            $document->status = 'pending';
        }

        $document->save();

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document uploaded successfully.');
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document)
    {
        // Check if user can view the document
        $this->authorize('view', $document);

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document)
    {
        // Authorize the request
        $this->authorize('update', $document);

        $departments = Department::all();

        // Staff can only associate with their department
        if (Auth::user()->role === 'staff') {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        $categories = ['report', 'proposal', 'minutes', 'regulation', 'certificate', 'other'];
        $visibilities = ['public', 'members', 'executives', 'admin'];

        return view('documents.edit', compact('document', 'departments', 'categories', 'visibilities'));
    }

    /**
     * Update the specified document.
     */
    public function update(DocumentRequest $request, Document $document)
    {
        // Authorize the request
        $this->authorize('update', $document);

        $document->fill($request->validated());

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Define allowed extensions based on document category
            $allowedExtensions = match ($request->category) {
                'certificate' => ['pdf', 'jpg', 'jpeg', 'png'],
                default => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'],
            };

            // Store file with security checks
            $path = $this->fileUploadService->store(
                $file,
                'documents',
                $allowedExtensions,
                10 * 1024 * 1024 // 10MB max
            );

            if (!$path) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid file. Please ensure your file meets the requirements.');
            }

            // Delete old file
            if ($document->file_path) {
                Storage::delete($document->file_path);
            }

            $document->file_path = $path;
            $document->file_type = $file->getMimeType();
            $document->file_size = $file->getSize();
            $document->version += 1;
        }

        // Update approval status if user is admin or executive
        if (in_array(Auth::user()->role, ['admin', 'executive']) && $request->has('status')) {
            $document->status = $request->status;

            if ($request->status === 'approved' || $request->status === 'published') {
                $document->approved_by = Auth::id();
                $document->approval_date = now();
            }
        }

        $document->save();

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document updated successfully.');
    }

    /**
     * Remove the specified document.
     */
    public function destroy(Document $document)
    {
        // Authorize the request
        $this->authorize('delete', $document);

        // Delete the document file
        if ($document->file_path) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    /**
     * Download the document file.
     */
    public function download(Document $document)
    {
        // Authorize the request
        $this->authorize('view', $document);

        // Check if file exists
        if (!$document->file_path || !Storage::exists($document->file_path)) {
            return redirect()->route('documents.show', $document)
                ->with('error', 'Document file not found.');
        }

        return Storage::download($document->file_path, $document->title . '.' . pathinfo($document->file_path, PATHINFO_EXTENSION));
    }

    /**
     * Approve the document (for executives and admins).
     */
    public function approve(Document $document)
    {
        // Authorize the request
        $this->authorize('approve', $document);

        $document->status = 'published';
        $document->approved_by = Auth::id();
        $document->approval_date = now();
        $document->save();

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document approved and published successfully.');
    }

    /**
     * Reject the document (for executives and admins).
     */
    public function reject(Request $request, Document $document)
    {
        // Authorize the request
        $this->authorize('approve', $document);

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $document->status = 'rejected';
        $document->save();

        // Send notification to uploader (implementation depends on your notification system)

        return redirect()->route('documents.show', $document)
            ->with('success', 'Document rejected.');
    }
}
