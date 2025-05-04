<!-- resources/views/components/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'HIMATEKOM') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-orange-500 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            {{-- <img class="h-10 w-auto" src="/logo.png" alt="Logo" onerror="this.src='https://via.placeholder.com/40x40?text=H'"> --}}
                        </a>
                        <a href="{{ route('dashboard') }}" class="ml-2 text-white font-bold text-xl">HIMATEKOM</a>
                    </div>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden space-x-8 sm:ml-10 sm:flex">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="{{ route('events.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('events.*') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            Events
                        </a>
                        <a href="{{ route('news.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('news.*') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            News
                        </a>
                        <a href="{{ route('galleries.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('galleries.*') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            Gallery
                        </a>
                        <a href="{{ route('documents.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('documents.*') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            Documents
                        </a>
                        <a href="{{ route('letters.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('letters.*') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            Letters
                        </a>
                        @if(auth()->check() && in_array(auth()->user()->role, ['executive', 'admin']))
                        <a href="{{ route('lpjs.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('lpjs.*') ? 'border-white text-white' : 'border-transparent text-orange-100 hover:text-white hover:border-orange-300' }} text-sm font-medium">
                            LPJs
                        </a>
                        @endif
                    </div>
                </div>

                <!-- User Menu -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div>
                            <button type="button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-orange-500 focus:ring-white" id="user-menu-button" aria-expanded="false" aria-haspopup="true" onclick="document.getElementById('user-dropdown').classList.toggle('hidden')">
                                <span class="sr-only">Open user menu</span>
                                @if(auth()->user()->profile_photo_path)
                                <img class="h-8 w-8 rounded-full" src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">
                                @else
                                <div class="h-8 w-8 rounded-full bg-orange-300 flex items-center justify-center text-orange-800 font-medium">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                @endif
                            </button>
                        </div>
                        
                        <!-- Dropdown menu -->
                        <div id="user-dropdown" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                            <div class="px-4 py-2 text-xs text-gray-500">
                                Signed in as<br>
                                <span class="font-medium text-gray-900">{{ auth()->user()->name }}</span>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Profile</a>
                            
                            @if(auth()->user()->signature_authority)
                            <a href="{{ route('signature.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Manage Signature</a>
                            @endif
                            
                            @if(auth()->user()->role === 'admin')
                            <a href="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Admin Panel</a>
                            @endif
                            
                            <div class="border-t border-gray-100"></div>
                            
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Sign out</a>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-orange-100 hover:text-white hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" aria-controls="mobile-menu" aria-expanded="false" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                        <span class="sr-only">Open main menu</span>
                        <!-- Menu open: "hidden", Menu closed: "block" -->
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Menu open: "block", Menu closed: "hidden" -->
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div class="hidden sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('dashboard') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">Dashboard</a>
                <a href="{{ route('events.index') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('events.*') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">Events</a>
                <a href="{{ route('news.index') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('news.*') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">News</a>
                <a href="{{ route('galleries.index') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('galleries.*') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">Gallery</a>
                <a href="{{ route('documents.index') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('documents.*') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">Documents</a>
                <a href="{{ route('letters.index') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('letters.*') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">Letters</a>
                @if(auth()->check() && in_array(auth()->user()->role, ['executive', 'admin']))
                <a href="{{ route('lpjs.index') }}" class="block pl-3 pr-4 py-2 {{ request()->routeIs('lpjs.*') ? 'text-white bg-orange-600' : 'text-orange-100 hover:text-white hover:bg-orange-600' }} text-base font-medium">LPJs</a>
                @endif
            </div>
            
            <!-- Mobile Profile Menu -->
            <div class="pt-4 pb-3 border-t border-orange-600">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->profile_photo_path)
                        <img class="h-10 w-10 rounded-full" src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}">
                        @else
                        <div class="h-10 w-10 rounded-full bg-orange-300 flex items-center justify-center text-orange-800 font-medium">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-orange-200">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-base font-medium text-orange-100 hover:text-white hover:bg-orange-600">Profile</a>
                    @if(auth()->user()->signature_authority)
                    <a href="{{ route('signature.show') }}" class="block px-4 py-2 text-base font-medium text-orange-100 hover:text-white hover:bg-orange-600">Manage Signature</a>
                    @endif
                    @if(auth()->user()->role === 'admin')
                    <a href="/admin" class="block px-4 py-2 text-base font-medium text-orange-100 hover:text-white hover:bg-orange-600">Admin Panel</a>
                    @endif
                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-base font-medium text-orange-100 hover:text-white hover:bg-orange-600">Sign out</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1-1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('info') }}</span>
            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                <svg class="fill-current h-6 w-6 text-blue-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1-1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Page Content -->
    <main class="flex-grow">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-bold">HIMATEKOM</h3>
                    <p class="text-gray-400">Himpunan Mahasiswa Teknik Komputer</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('landingpage') }}" class="hover:text-orange-500 transition">Home</a>
                    <a href="{{ route('events.index') }}" class="hover:text-orange-500 transition">Events</a>
                    <a href="{{ route('news.index') }}" class="hover:text-orange-500 transition">News</a>
                    <a href="{{ route('galleries.index') }}" class="hover:text-orange-500 transition">Gallery</a>
                    <a href="{{ route('documents.index') }}" class="hover:text-orange-500 transition">Documents</a>
                    <a href="{{ route('letters.index') }}" class="hover:text-orange-500 transition">Letters</a>
                </div>
            </div>
            <div class="mt-4 text-center text-gray-400 text-sm">
                &copy; {{ date('Y') }} HIMATEKOM. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('user-dropdown');
            const button = document.getElementById('user-menu-button');
            
            if (dropdown && !dropdown.contains(e.target) && !button.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>