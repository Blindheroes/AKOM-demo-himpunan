<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view the events list
    }

    /**
     * Determine whether the user can view the event.
     */
    public function view(User $user, Event $event): bool
    {
        // Published events can be viewed by anyone
        if ($event->status === 'published') {
            return true;
        }

        // Only staff, executives, and admins can view unpublished events
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        // Only staff, executives, and admins can create events
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        // Admin and executives can update any event
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Staff can only update events they created or events from their department
        if ($user->role === 'staff') {
            return $event->created_by === $user->id ||
                $event->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        // Admin can delete any event
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can delete any event except completed ones
        if ($user->role === 'executive') {
            return $event->status !== 'completed';
        }

        // Staff can only delete events they created and that are in draft status
        if ($user->role === 'staff') {
            return $event->created_by === $user->id &&
                in_array($event->status, ['draft', 'pending']);
        }

        return false;
    }

    /**
     * Determine whether the user can restore the event.
     */
    public function restore(User $user, Event $event): bool
    {
        // Only admin and executives can restore deleted events
        return in_array($user->role, ['admin', 'executive']);
    }

    /**
     * Determine whether the user can permanently delete the event.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        // Only admin can permanently delete events
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can register for the event.
     */
    public function register(User $user, Event $event): bool
    {
        // Check if event is published
        if ($event->status !== 'published') {
            return false;
        }

        // Check if registration deadline has passed
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return false;
        }

        // Check if event has reached maximum participants
        if ($event->max_participants && $event->registrations()->count() >= $event->max_participants) {
            return false;
        }

        return true;
    }
}
