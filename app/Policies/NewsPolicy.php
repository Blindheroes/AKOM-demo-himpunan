<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any news articles.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view the news list
    }

    /**
     * Determine whether the user can view the news article.
     */
    public function view(User $user, News $news): bool
    {
        // Published news can be viewed by anyone
        if ($news->status === 'published') {
            return true;
        }

        // Only staff, executives, and admins can view unpublished news
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can create news articles.
     */
    public function create(User $user): bool
    {
        // Only staff, executives, and admins can create news
        return in_array($user->role, ['staff', 'executive', 'admin']);
    }

    /**
     * Determine whether the user can update the news article.
     */
    public function update(User $user, News $news): bool
    {
        // Admin and executives can update any news
        if (in_array($user->role, ['admin', 'executive'])) {
            return true;
        }

        // Staff can only update news they authored or from their department
        if ($user->role === 'staff') {
            return $news->author_id === $user->id ||
                $news->department_id === $user->department_id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the news article.
     */
    public function delete(User $user, News $news): bool
    {
        // Admin can delete any news
        if ($user->role === 'admin') {
            return true;
        }

        // Executives can delete any news from their department
        if ($user->role === 'executive') {
            return true;
        }

        // Staff can only delete news they authored and that is in draft status
        if ($user->role === 'staff') {
            return $news->author_id === $user->id &&
                $news->status !== 'published';
        }

        return false;
    }

    /**
     * Determine whether the user can restore the news article.
     */
    public function restore(User $user, News $news): bool
    {
        // Only admin and executives can restore deleted news
        return in_array($user->role, ['admin', 'executive']);
    }

    /**
     * Determine whether the user can permanently delete the news article.
     */
    public function forceDelete(User $user, News $news): bool
    {
        // Only admin can permanently delete news
        return $user->role === 'admin';
    }
}
