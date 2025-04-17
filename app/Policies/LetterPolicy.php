<?php

namespace App\Policies;

use App\Models\Letter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any letters.
     */
    public function viewAny(User $user): bool
    {
        // Only authenticated users can view letters
        return true;
    }

    /**
     * Determine whether the user can view the letter.
     */
    public function view(User $user, Letter $letter): bool
    {
        // Admin and executives can view any letter
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Staff can only view letters from their department or ones they created
        if ($user->role === 'staff') {
            return $letter->department_id === $user->department_id ||
                $letter->created_by === $user->id;
        }

        // Members can only view published/sent letters from their department
        if ($user->role === 'member') {
            return $letter->department_id === $user->department_id &&
                in_array($letter->status, ['sent', 'archived']);
        }

        return false;
    }

    /**
     * Determine whether the user can create letters.
     */
    public function create(User $user): bool
    {
        // Staff, executives, and admins can create letters
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can update the letter.
     */
    public function update(User $user, Letter $letter): bool
    {
        // Signed or sent letters cannot be edited
        if (in_array($letter->status, ['signed', 'sent', 'archived'])) {
            return false;
        }

        // Admin and executives can update any draft/pending letter
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Staff can only update letters they created
        if ($user->role === 'staff') {
            return $letter->created_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the letter.
     */
    public function delete(User $user, Letter $letter): bool
    {
        // Signed, sent or archived letters cannot be deleted
        if (in_array($letter->status, ['signed', 'sent', 'archived'])) {
            return false;
        }

        // Admin can delete any letter
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can delete any draft/pending letter
        if ($user->role === 'executive') {
            return in_array($letter->status, ['draft', 'pending']);
        }

        // Staff can only delete letters they created and that are in draft status
        if ($user->role === 'staff') {
            return $letter->created_by === $user->id &&
                $letter->status === 'draft';
        }

        return false;
    }

    /**
     * Determine whether the user can sign the letter.
     */
    public function sign(User $user, Letter $letter): bool
    {
        // Only users with signature authority can sign letters
        if (!$user->signature_authority) {
            return false;
        }

        // Letter must be in draft or pending status
        if (!in_array($letter->status, ['draft', 'pending'])) {
            return false;
        }

        // User must have an active signature
        if (!$user->signature || !$user->signature->is_active) {
            return false;
        }

        // Admin and executives with signature authority can sign any letter
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Department heads can sign letters from their department
        if ($user->role === 'staff') {
            $departmentHeadId = $letter->department->head_id ?? null;
            return $user->id === $departmentHeadId;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the letter.
     */
    public function restore(User $user, Letter $letter): bool
    {
        // Only admin and executives can restore deleted letters
        return in_array($user->role, ['admin', 'executive']);
    }

    /**
     * Determine whether the user can permanently delete the letter.
     */
    public function forceDelete(User $user, Letter $letter): bool
    {
        // Only admin can permanently delete letters
        return $user->role === 'admin';
    }
}
