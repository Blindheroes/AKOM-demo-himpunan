<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use App\Models\Letter;
use App\Models\Document;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display the user dashboard with relevant information.
     */
    public function index()
    {
        $user = Auth::user();

        // Get upcoming events
        $upcomingEvents = Event::where('status', 'published')
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        // Get user's registered events
        $registeredEvents = [];
        if ($user) {
            $registeredEvents = EventRegistration::where('user_id', $user->id)
                ->with('event')
                ->whereHas('event', function ($query) {
                    $query->where('start_date', '>', now());
                })
                ->get()
                ->pluck('event');
        }

        // Get recent news
        $recentNews = News::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Get stats based on user role
        $stats = [];

        if (in_array($user->role, ['staff', 'executive', 'admin'])) {
            // For staff and above, show more detailed stats
            $stats = [
                'totalEvents' => Event::count(),
                'totalMembers' => \App\Models\User::where('role', 'member')->count(),
                'pendingLetters' => Letter::where('status', 'draft')->count(),
                'recentDocuments' => Document::orderBy('created_at', 'desc')->limit(5)->get(),
            ];

            // For department heads, show department-specific stats
            $departmentHeadships = $user->headOfDepartments;
            if ($departmentHeadships->isNotEmpty()) {
                $departmentIds = $departmentHeadships->pluck('id')->toArray();

                $stats['departmentEvents'] = Event::whereIn('department_id', $departmentIds)
                    ->count();

                $stats['departmentMembers'] = \App\Models\User::whereIn('department_id', $departmentIds)
                    ->count();
            }
        }

        return view('dashboard', compact('upcomingEvents', 'registeredEvents', 'recentNews', 'stats'));
    }
}
