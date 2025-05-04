<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use App\Http\Requests\EventRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    // public function __construct()
    // {
    //     // Apply auth middleware to all methods except index and show
    //     $this->middleware(['auth'])->except(['index', 'show']);

    //     // Staff or higher role required for create/edit/delete operations
    //     $this->middleware(['role:staff,executive,admin'])->except(['index', 'show', 'register', 'unregister']);
    // }

    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Apply filters
        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date_filter')) {
            switch ($request->date_filter) {
                case 'upcoming':
                    $query->where('start_date', '>', now());
                    break;
                case 'past':
                    $query->where('start_date', '<', now());
                    break;
                case 'this_week':
                    $query->whereBetween('start_date', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereBetween('start_date', [
                        now()->startOfMonth(),
                        now()->endOfMonth()
                    ]);
                    break;
            }
        }

        // Created by filter (for staff and admins)
        if (Auth::check() && in_array(Auth::user()->role, ['staff', 'executive', 'admin']) && $request->filled('created_by')) {
            if ($request->created_by == 'me') {
                $query->where('created_by', Auth::id());
            }
        }

        // Show only published events to regular users
        if (!Auth::check() || (Auth::check() && Auth::user()->role === 'member')) {
            $query->where('status', 'published');
        }

        // Order by date, with featured events first
        $events = $query->orderBy('is_featured', 'desc')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        $departments = Department::all();

        return view('events.index', compact('events', 'departments'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {

        // get all user exept member
        $users = User::where('role', '!=', 'member')->get();

        $departments = Department::all();
        return view('events.create', compact('departments', 'users'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(EventRequest $request)
    {
        // The EventRequest handles validation

        $event = new Event($request->validated());
        $event->slug = Str::slug($request->title) . '-' . time();
        $event->created_by = Auth::id();

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/events');
            $event->image_path = $path;
        }

        // Set status based on user role
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'executive') {
            $event->status = 'published';
            $event->approved_by = Auth::id();
        } else {
            $event->status = 'pending';
        }

        $event->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        // Check if user can view this event
        if (
            $event->status !== 'published' &&
            (!Auth::check() || (Auth::check() && Auth::user()->role === 'member'))
        ) {
            abort(403, 'This event is not currently published.');
        }

        // Check if user is registered
        $isRegistered = false;
        if (Auth::check()) {
            $isRegistered = EventRegistration::where('event_id', $event->id)
                ->where('user_id', Auth::id())
                ->exists();
        }

        return view('events.show', compact('event', 'isRegistered'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        // Check if user can edit the event
        $this->authorize('update', $event);

        $departments = Department::all();
        return view('events.edit', compact('event', 'departments'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        // Check if user can update the event
        $this->authorize('update', $event);

        $event->fill($request->validated());

        // Handle image upload if present
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image_path) {
                Storage::delete($event->image_path);
            }

            $path = $request->file('image')->store('public/events');
            $event->image_path = $path;
        }

        $event->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Check if user can delete the event
        $this->authorize('delete', $event);

        // Delete event image if exists
        if ($event->image_path) {
            Storage::delete($event->image_path);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Register current user for an event.
     */
    public function register(Event $event)
    {
        // Check if event is published and open for registration
        if ($event->status !== 'published') {
            return redirect()->route('events.show', $event)
                ->with('error', 'This event is not open for registration.');
        }

        // Check if registration deadline has passed
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Registration deadline has passed.');
        }

        // Check if event has reached maximum participants
        if ($event->max_participants && $event->registrations()->count() >= $event->max_participants) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Event has reached maximum participants.');
        }

        // Check if user is already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingRegistration) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You are already registered for this event.');
        }

        // Create registration
        $registration = new EventRegistration([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'registration_date' => now(),
            'status' => 'pending'
        ]);

        $registration->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'You have successfully registered for this event.');
    }

    /**
     * Cancel registration for an event.
     */
    public function unregister(Event $event)
    {
        // Find and delete registration
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$registration) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You are not registered for this event.');
        }

        $registration->delete();

        return redirect()->route('events.show', $event)
            ->with('success', 'Your registration has been canceled.');
    }

    /**
     * Change event status (for admins and executives).
     */
    public function changeStatus(Request $request, Event $event)
    {
        // Verify the user has permission to change status
        if (!in_array(Auth::user()->role, ['admin', 'executive'])) {
            abort(403, 'You do not have permission to change event status.');
        }

        $request->validate([
            'status' => 'required|in:draft,pending,approved,published,canceled,completed'
        ]);

        $event->status = $request->status;

        // If publishing or approving, set approved_by
        if (in_array($request->status, ['published', 'approved']) && !$event->approved_by) {
            $event->approved_by = Auth::id();
        }

        $event->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'Event status updated successfully.');
    }
}
