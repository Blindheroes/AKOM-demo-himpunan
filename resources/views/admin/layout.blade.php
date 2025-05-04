<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Top Navigation -->
    <header class="bg-gray-800 text-white">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <span class="font-bold text-lg">HIMATEKOM Admin</span>
            </div>
            <div class="flex items-center space-x-4">
                <span>{{ Auth::user()->name }}</span>
                <a href="{{ route('dashboard') }}" class="text-sm hover:underline">Main Site</a>
                <a href="{{ route('logout') }}" class="text-sm hover:underline">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside class="bg-gray-900 text-gray-100 w-64 py-4 hidden md:block flex-shrink-0 h-screen">
            <nav class="mt-5">
                <div class="px-6 py-2 text-xs font-bold uppercase tracking-wide text-gray-400">
                    Main
                </div>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="px-6 py-2 mt-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                    Management
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.departments.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.departments.*') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-building mr-3"></i>
                    <span>Departments</span>
                </a>
                
                <div class="px-6 py-2 mt-4 text-xs font-bold uppercase tracking-wide text-gray-400">
                    System
                </div>
                <a href="{{ route('admin.settings') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.settings') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-cogs mr-3"></i>
                    <span>Settings</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center px-6 py-3 hover:bg-gray-800 {{ request()->routeIs('admin.reports') ? 'bg-gray-800 border-l-4 border-orange-500' : '' }}">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-4">
            <!-- Page Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-800">@yield('header')</h1>
                @yield('actions')
            </div>
            
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
                <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @endif

            <!-- Page Content -->
            <div class="bg-white shadow rounded-lg p-6">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t py-4">
        <div class="container mx-auto px-4">
            <p class="text-center text-gray-600 text-sm">
                &copy; {{ date('Y') }} HIMATEKOM - Computer Engineering Student Association Management System
            </p>
        </div>
    </footer>

    @stack('scripts')
    <script>
        // Close alert messages
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('[role="alert"] button');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.style.display = 'none';
                });
            });
        });
    </script>
</body>
</html>