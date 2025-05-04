<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\NewsRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of news articles.
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->filled('status') && Auth::check() && in_array(Auth::user()->role, ['staff', 'executive', 'admin'])) {
            $query->where('status', $request->status);
        } else {
            // Show only published news to regular users
            if (!Auth::check() || (Auth::check() && Auth::user()->role === 'member')) {
                $query->where('status', 'published');
            }
        }

        $news = $query->orderBy('published_at', 'desc')->paginate(12);
        $departments = Department::all();

        return view('news.index', compact('news', 'departments'));
    }

    /**
     * Show the form for creating a new news article.
     */
    public function create()
    {
        $departments = Department::all();

        // Staff can only post news for their department
        if (Auth::user()->role === 'staff') {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('news.create', compact('departments'));
    }

    /**
     * Store a newly created news article.
     */
    public function store(NewsRequest $request)
    {
        $news = new News($request->validated());
        $news->slug = Str::slug($request->title) . '-' . time();
        $news->author_id = Auth::id();

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('news-images', 'public');
            $news->featured_image = $path;
        }

        // Set published_at date and status based on action
        if ($request->has('publish')) {
            $news->status = 'published';
            $news->published_at = now();
        } else {
            $news->status = 'draft';
        }

        $news->save();

        return redirect()->route('news.show', $news)
            ->with('success', 'News article created successfully.');
    }

    /**
     * Display the specified news article.
     */
    public function show(News $news)
    {
        // Check if user can view the news
        if (
            $news->status !== 'published' &&
            (!Auth::check() || (Auth::check() && Auth::user()->role === 'member'))
        ) {
            abort(403, 'This news article is not currently published.');
        }

        // Increment view count
        $news->increment('views');

        // Get related news from the same department
        $relatedNews = News::where('department_id', $news->department_id)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }

    /**
     * Show the form for editing the specified news article.
     */
    public function edit(News $news)
    {
        // Authorize the request
        $this->authorize('update', $news);

        $departments = Department::all();

        // Staff can only edit news for their department
        if (Auth::user()->role === 'staff') {
            $departments = Department::where('id', Auth::user()->department_id)->get();
        }

        return view('news.edit', compact('news', 'departments'));
    }

    /**
     * Update the specified news article.
     */
    public function update(NewsRequest $request, News $news)
    {
        // Authorize the request
        $this->authorize('update', $news);

        $news->fill($request->validated());

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image if exists
            if ($news->featured_image) {
                Storage::delete('public/' . $news->featured_image);
            }

            $path = $request->file('featured_image')->store('news-images', 'public');
            $news->featured_image = $path;
        }

        // Handle publishing
        if ($request->has('publish') && $news->status !== 'published') {
            $news->status = 'published';
            $news->published_at = now();
        } elseif ($request->has('unpublish') && $news->status === 'published') {
            $news->status = 'draft';
        }

        $news->save();

        return redirect()->route('news.show', $news)
            ->with('success', 'News article updated successfully.');
    }

    /**
     * Remove the specified news article.
     */
    public function destroy(News $news)
    {
        // Authorize the request
        $this->authorize('delete', $news);

        // Delete featured image if exists
        if ($news->featured_image) {
            Storage::delete('public/' . $news->featured_image);
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News article deleted successfully.');
    }
}
