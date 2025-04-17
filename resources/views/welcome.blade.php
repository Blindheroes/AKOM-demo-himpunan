<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Welcome to HIMATEKOM</title>
        @vite('resources/css/app.css')

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    </head>
    <body class="bg-gray-100 flex flex-col h-screen">

        <!-- Navbar -->
        <nav class="bg-orange-500 text-white py-4 px-6 flex justify-between items-center shadow-md">
            <div class="flex items-center space-x-2">
                <img src="/logo.png" alt="Logo" class="h-8 w-auto" onerror="this.src='https://via.placeholder.com/32x32?text=H'">
                <span class="font-bold text-lg">HIMATEKOM</span>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <span class="mr-4">Halo, {{ auth()->user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white text-orange-500 rounded-lg hover:bg-orange-100 transition font-medium">Dashboard</a>
                    <a href="{{ route('logout') }}" class="px-4 py-2 bg-white text-orange-500 rounded-lg hover:bg-orange-100 transition font-medium">Logout</a>
                @else
                    <a href="{{ route('google.redirect') }}" class="flex items-center px-4 py-2 bg-white text-orange-500 rounded-lg hover:bg-orange-100 transition font-medium">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Login dengan Google
                    </a>
                @endauth
            </div>
        </nav>
    
        <!-- Main Content -->
        <div class="flex-grow flex flex-col justify-center items-center text-center p-4">
            <img src="/logo.png" alt="HIMATEKOM Logo" class="w-40 h-40 mb-4" onerror="this.src='https://via.placeholder.com/160x160?text=HIMATEKOM'">
            <h1 class="text-4xl font-bold text-orange-600">Selamat Datang di HIMATEKOM</h1>
            <p class="text-gray-600 mt-2 text-lg">Himpunan Mahasiswa Teknik Komputer</p>
            
            <!-- Alert Messages -->
            @if (session('success'))
                <div class="mt-8 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="mt-8 p-4 bg-red-100 text-red-700 rounded-lg">
                    {{ session('error') }}
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-6 px-6">
            <div class="container mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-xl font-bold">HIMATEKOM</h3>
                        <p class="text-gray-400">Himpunan Mahasiswa Teknik Komputer</p>
                    </div>
                    <div class="flex space-x-4">
                        <a href="#" class="hover:text-orange-500 transition">Home</a>
                        <a href="#" class="hover:text-orange-500 transition">About</a>
                        <a href="#" class="hover:text-orange-500 transition">Events</a>
                        <a href="#" class="hover:text-orange-500 transition">Contact</a>
                    </div>
                </div>
                <div class="mt-4 text-center text-gray-400 text-sm">
                    &copy; {{ date('Y') }} HIMATEKOM. All rights reserved.
                </div>
            </div>
        </footer>
    </body>
</html>