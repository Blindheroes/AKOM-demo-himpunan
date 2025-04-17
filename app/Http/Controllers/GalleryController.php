<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImages;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\GalleryRequest;
use App\Services\FileUploadService;

class GalleryController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of galleries.
     */
    public function index()
    {
        $query = Gallery::query();

        // Show only published galleries to regular users
        if (!Auth::check() || (Auth::check() && Auth::user()->role === 'member')) {
            $query->where('status', 'published');
        }

        $galleries = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('galleries.index', compact('galleries'));
    }

    /**
     * Show the form for creating a new gallery.
     */
    public function create()
    {
        $events = Event::where('status', 'completed')->get();
        return view('galleries.create', compact('events'));
    }

    /**
     * Store a newly created gallery.
     */
    public function store(GalleryRequest $request)
    {
        $gallery = new Gallery($request->validated());
        $gallery->created_by = Auth::id();

        // Set gallery status based on user role
        if (in_array(Auth::user()->role, ['admin', 'executive'])) {
            $gallery->status = 'published';
        } else {
            $gallery->status = 'draft';
        }

        $gallery->save();

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $this->uploadGalleryImages($request->file('images'), $gallery);
        }

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Gallery created successfully. You can now add more images.');
    }

    /**
     * Display the specified gallery.
     */
    public function show(Gallery $gallery)
    {
        // Check if user can view the gallery
        if (
            $gallery->status !== 'published' &&
            (!Auth::check() || (Auth::check() && Auth::user()->role === 'member'))
        ) {
            abort(403, 'This gallery is not currently published.');
        }

        // Get related galleries if gallery is associated with an event
        $relatedGalleries = collect();
        if ($gallery->event_id) {
            $relatedGalleries = Gallery::where('event_id', $gallery->event_id)
                ->where('id', '!=', $gallery->id)
                ->where('status', 'published')
                ->limit(3)
                ->get();
        }

        // Get gallery images ordered by sort_order
        $images = $gallery->images()->orderBy('sort_order')->get();

        return view('galleries.show', compact('gallery', 'images', 'relatedGalleries'));
    }

    /**
     * Show the form for editing the specified gallery.
     */
    public function edit(Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('update', $gallery);

        $events = Event::where('status', 'completed')->get();
        $images = $gallery->images()->orderBy('sort_order')->get();

        return view('galleries.edit', compact('gallery', 'events', 'images'));
    }

    /**
     * Update the specified gallery.
     */
    public function update(GalleryRequest $request, Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('update', $gallery);

        $gallery->fill($request->validated());
        $gallery->save();

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $this->uploadGalleryImages($request->file('images'), $gallery);
        }

        // Update image sort order
        if ($request->has('image_order')) {
            foreach ($request->image_order as $id => $order) {
                GalleryImages::where('id', $id)->update(['sort_order' => $order]);
            }
        }

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Gallery updated successfully.');
    }

    /**
     * Remove the specified gallery.
     */
    public function destroy(Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('delete', $gallery);

        // Delete all gallery images
        foreach ($gallery->images as $image) {
            if ($image->image_path) {
                Storage::delete('public/' . $image->image_path);
            }
            $image->delete();
        }

        $gallery->delete();

        return redirect()->route('galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }

    /**
     * Add new images to a gallery.
     */
    public function storeImage(Request $request, Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('update', $gallery);

        $validated = $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:5120', // 5MB max per image
            'captions' => 'nullable|array',
            'captions.*' => 'nullable|string|max:255',
        ]);

        // Upload images
        if ($request->hasFile('images')) {
            $this->uploadGalleryImages($request->file('images'), $gallery, $request->captions ?? []);
        }

        return redirect()->route('galleries.edit', $gallery)
            ->with('success', 'Images added successfully.');
    }

    /**
     * Remove an image from a gallery.
     */
    public function destroyImage(GalleryImages $image)
    {
        // Authorize the request (check if user can update the gallery)
        $gallery = $image->gallery;
        $this->authorize('update', $gallery);

        // Delete the image file
        if ($image->image_path) {
            Storage::delete('public/' . $image->image_path);
        }

        $image->delete();

        return redirect()->route('galleries.edit', $gallery)
            ->with('success', 'Image removed successfully.');
    }

    /**
     * Helper method to upload multiple gallery images.
     */
    private function uploadGalleryImages($images, Gallery $gallery, array $captions = [])
    {
        $lastOrder = $gallery->images()->max('sort_order') ?? 0;

        foreach ($images as $index => $image) {
            // Store image with security checks
            $path = $this->fileUploadService->store(
                $image,
                'galleries',
                ['jpg', 'jpeg', 'png', 'gif'],
                5 * 1024 * 1024 // 5MB max
            );

            if ($path) {
                // Get caption if provided
                $caption = $captions[$index] ?? null;

                // Create gallery image
                GalleryImages::create([
                    'gallery_id' => $gallery->id,
                    'image_path' => $path,
                    'caption' => $caption,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }
        }
    }

    /**
     * Publish a gallery (for staff, executives, and admins).
     */
    public function publish(Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('update', $gallery);

        $gallery->status = 'published';
        $gallery->save();

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Gallery published successfully.');
    }

    /**
     * Unpublish a gallery (set to draft).
     */
    public function unpublish(Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('update', $gallery);

        $gallery->status = 'draft';
        $gallery->save();

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Gallery unpublished (set to draft).');
    }

    /**
     * Archive a gallery.
     */
    public function archive(Gallery $gallery)
    {
        // Authorize the request
        $this->authorize('update', $gallery);

        $gallery->status = 'archived';
        $gallery->save();

        return redirect()->route('galleries.show', $gallery)
            ->with('success', 'Gallery archived successfully.');
    }
}
