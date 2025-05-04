<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\News;
use App\Models\Letter;
use App\Models\Document;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with key metrics.
     */
    public function dashboard()
    {
        // Get system statistics
        $stats = [
            'users' => [
                'total' => User::count(),
                'members' => User::where('role', 'member')->count(),
                'staff' => User::where('role', 'staff')->count(),
                'executives' => User::where('role', 'executive')->count(),
                'admins' => User::where('role', 'admin')->count(),
            ],
            'departments' => Department::count(),
            'events' => [
                'total' => Event::count(),
                'published' => Event::where('status', 'published')->count(),
                'upcoming' => Event::where('status', 'published')
                    ->where('start_date', '>', now())
                    ->count(),
                'past' => Event::where('status', 'published')
                    ->where('start_date', '<', now())
                    ->count(),
            ],
            'documents' => [
                'total' => Document::count(),
                'published' => Document::where('status', 'published')->count(),
                'pending' => Document::where('status', 'pending')->count(),
            ],
            'news' => [
                'total' => News::count(),
                'published' => News::where('status', 'published')->count(),
            ],
            'letters' => [
                'total' => Letter::count(),
                'pending' => Letter::where('status', 'pending')->count(),
            ]
        ];

        // Get recent activity
        $recentActivity = collect()
            ->merge(Event::select('id', 'title', 'created_at', DB::raw("'Event' as type"))
                ->latest()->limit(5)->get())
            ->merge(News::select('id', 'title', 'created_at', DB::raw("'News' as type"))
                ->latest()->limit(5)->get())
            ->merge(Document::select('id', 'title', 'created_at', DB::raw("'Document' as type"))
                ->latest()->limit(5)->get())
            ->sortByDesc('created_at')
            ->take(10);

        return view('admin.dashboard', compact('stats', 'recentActivity'));
    }

    /**
     * Display user management page.
     */
    public function users()
    {
        $users = User::with('department')->paginate(15);
        $departments = Department::all();
        $roles = ['member', 'staff', 'executive', 'admin'];

        return view('admin.users.index', compact('users', 'departments', 'roles'));
    }

    /**
     * Display user creation form.
     */
    public function createUser()
    {
        $departments = Department::all();
        $roles = ['member', 'staff', 'executive', 'admin'];

        return view('admin.users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|in:member,staff,executive,admin',
            'department_id' => 'nullable|exists:departments,id',
            'signature_authority' => 'boolean',
        ]);

        $user = User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display user edit form.
     */
    public function editUser(User $user)
    {
        $departments = Department::all();
        $roles = ['member', 'staff', 'executive', 'admin'];

        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|string|in:member,staff,executive,admin',
            'department_id' => 'nullable|exists:departments,id',
            'signature_authority' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete the specified user.
     */
    public function destroyUser(User $user)
    {
        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Display department management page.
     */
    public function departments()
    {
        $departments = Department::withCount('users')->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Display department creation form.
     */
    public function createDepartment()
    {
        $users = User::whereIn('role', ['staff', 'executive'])->get();
        return view('admin.departments.create', compact('users'));
    }

    /**
     * Store a newly created department.
     */
    public function storeDepartment(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
        ]);

        Department::create($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display department edit form.
     */
    public function editDepartment(Department $department)
    {
        $users = User::whereIn('role', ['staff', 'executive'])->get();
        return view('admin.departments.edit', compact('department', 'users'));
    }

    /**
     * Update the specified department.
     */
    public function updateDepartment(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
        ]);

        $department->update($validated);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Delete the specified department.
     */
    public function destroyDepartment(Department $department)
    {
        // Check if department has users
        if ($department->users()->count() > 0) {
            return redirect()->route('admin.departments.index')
                ->with('error', 'Cannot delete department with associated users.');
        }

        $department->delete();

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    /**
     * Display system settings page.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'contact_email' => 'required|email',
            'logo' => 'nullable|image|max:2048',
            'footer_text' => 'nullable|string',
        ]);

        // Store settings in database or config files

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Display reports and analytics page.
     */
    public function reports()
    {
        // Get monthly event statistics
        $monthlyEvents = Event::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get department participation
        $departmentStats = Department::withCount(['users', 'events'])
            ->orderBy('name')
            ->get();

        // Get user registration stats
        $userStats = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.reports', compact('monthlyEvents', 'departmentStats', 'userStats'));
    }
}
