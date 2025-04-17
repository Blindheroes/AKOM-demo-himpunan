<?php

namespace App\Policies;

use App\Models\Lpj;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LpjPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any LPJs.
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can access the LPJ listing page
        return true;
    }

    /**
     * Determine whether the user can view a specific LPJ.
     */
    public function view(User $user, Lpj $lpj): bool
    {
        // Admin and executives can view any LPJ
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Staff can view LPJs they created or that belong to their department
        if ($user->role === 'staff') {
            return $lpj->created_by === $user->id ||
                ($lpj->event && $lpj->event->department_id === $user->department_id);
        }

        // Members can only view published/approved LPJs
        if ($user->role === 'member') {
            return in_array($lpj->status, ['approved', 'published']);
        }

        return false;
    }

    /**
     * Determine whether the user can create LPJs.
     */
    public function create(User $user): bool
    {
        // Only staff, executives, and admins can create LPJs
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can update the LPJ.
     */
    public function update(User $user, Lpj $lpj): bool
    {
        // Approved/published LPJs cannot be edited
        if (in_array($lpj->status, ['approved', 'published'])) {
            return false;
        }

        // Admin can update any LPJ that's not approved/published
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can update any LPJ that's not approved/published
        if ($user->role === 'executive') {
            return true;
        }

        // Staff can only update LPJs they created that are in draft/pending status
        if ($user->role === 'staff') {
            return $lpj->created_by === $user->id &&
                in_array($lpj->status, ['draft', 'pending']);
        }

        return false;
    }

    /**
     * Determine whether the user can delete the LPJ.
     */
    public function delete(User $user, Lpj $lpj): bool
    {
        // Approved/published LPJs cannot be deleted
        if (in_array($lpj->status, ['approved', 'published'])) {
            return false;
        }

        // Admin can delete any LPJ that's not approved/published
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can delete any LPJ that's not approved/published
        if ($user->role === 'executive') {
            return true;
        }

        // Staff can only delete LPJs they created that are in draft status
        if ($user->role === 'staff') {
            return $lpj->created_by === $user->id && $lpj->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can approve the LPJ.
     */
    public function approve(User $user, Lpj $lpj): bool
    {
        // Only executives and admins can approve LPJs
        if (!in_array($user->role, ['executive', 'admin'])) {
            return false;
        }

        // LPJ must be in pending status
        if ($lpj->status !== 'pending') {
            return false;
        }

        // For executives, they can only approve LPJs from their department
        if ($user->role === 'executive' && $lpj->event) {
            $departmentHeadships = $user->headOfDepartments->pluck('id')->toArray();
            return in_array($lpj->event->department_id, $departmentHeadships);
        }

        // Admins can approve any pending LPJ
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can reject the LPJ.
     */
    public function reject(User $user, Lpj $lpj): bool
    {
        // Rejection follows the same rules as approval
        return $this->approve($user, $lpj);
    }

    /**
     * Determine whether the user can generate PDF for the LPJ.
     */
    public function generatePdf(User $user, Lpj $lpj): bool
    {
        // Anyone who can view the LPJ can generate a PDF
        return $this->view($user, $lpj);
    }

    /**
     * Determine whether the user can restore the LPJ.
     */
    public function restore(User $user, Lpj $lpj): bool
    {
        // Only executives and admins can restore deleted LPJs
        return in_array($user->role, ['executive', 'admin']);
    }

    /**
     * Determine whether the user can permanently delete the LPJ.
     */
    public function forceDelete(User $user, Lpj $lpj): bool
    {
        // Only admin can permanently delete LPJs
        return $user->role === 'admin';
    }
}
