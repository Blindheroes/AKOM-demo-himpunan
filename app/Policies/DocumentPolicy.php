<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(?User $user)
    {
        // Anyone can view the index page (specific visibility will be filtered in the controller)
        return true;
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(?User $user, Document $document)
    {
        // For publicly visible documents
        if ($document->visibility === 'public') {
            return true;
        }

        // Must be logged in for non-public documents
        if (!$user) {
            return false;
        }

        // Admins can view all documents
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can view all documents except admin-only
        if ($user->role === 'executive') {
            return $document->visibility !== 'admin';
        }

        // Staff can view members documents, and executive documents for their department
        if ($user->role === 'staff') {
            if (in_array($document->visibility, ['members'])) {
                return true;
            }

            if ($document->visibility === 'executives' && $user->department_id === $document->department_id) {
                return true;
            }

            return false;
        }

        // Regular members can view only member-visible documents
        if ($user->role === 'member') {
            return $document->visibility === 'members';
        }

        return false;
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'executive', 'staff']);
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document)
    {
        // Admins can update any document
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can update documents from their department or that they uploaded
        if ($user->role === 'executive') {
            return $document->department_id === $user->department_id || $document->uploaded_by === $user->id;
        }

        // Staff can only update documents they uploaded
        if ($user->role === 'staff') {
            return $document->uploaded_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document)
    {
        // Admins can delete any document
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can delete documents from their department or that they uploaded
        if ($user->role === 'executive') {
            return $document->department_id === $user->department_id || $document->uploaded_by === $user->id;
        }

        // Staff can only delete documents they uploaded
        if ($user->role === 'staff') {
            return $document->uploaded_by === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can approve documents.
     */
    public function approve(User $user, Document $document)
    {
        return in_array($user->role, ['admin', 'executive']);
    }
}
