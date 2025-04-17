<?php

namespace App\Policies;

use App\Models\Gallery;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GalleryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any galleries.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view the galleries list
    }

    /**
     * Determine whether the user can view the gallery.
     */
    public function view(?User $user, Gallery $gallery): bool
    {
        // Published galleries can be viewed by anyone
        if ($gallery->status === 'published') {
            return true;
        }

        // From here on, user must be authenticated
        if (!$user) {
            return false;
        }

        // Only staff, executives, and admins can view unpublished galleries
        if (in_array($user->role, ['staff', 'executive', 'admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create galleries.
     */
    public function create(User $user): bool
    {
        // Only staff, executives, and admins can create galleries
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can update the gallery.
     */
    public function update(User $user, Gallery $gallery): bool
    {
        // Admin and executives can update any gallery
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Staff can only update galleries they created
        if ($user->role === 'staff') {
            return $gallery->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the gallery.
     */
    public function delete(User $user, Gallery $gallery): bool
    {
        // Admin can delete any gallery
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can delete any gallery
        if ($user->role === 'executive') {
            return true;
        }

        // Staff can only delete galleries they created
        if ($user->role === 'staff') {
            return $gallery->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the gallery.
     */
    public function restore(User $user, Gallery $gallery): bool
    {
        // Only admin and executives can restore deleted galleries
        return in_array($user->role, ['admin', 'executive']);
    }

    /**
     * Determine whether the user can permanently delete the gallery.
     */
    public function forceDelete(User $user, Gallery $gallery): bool
    {
        // Only admin can permanently delete galleries
        return $user->role === 'admin';
    }
}
