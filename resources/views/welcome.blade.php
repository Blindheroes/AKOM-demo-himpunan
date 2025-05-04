<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HIMATEKOM') }} - Student Organization</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles -->
        @vite('resources/css/app.css')
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <!-- Header/Navbar -->
        <nav class="bg-orange-500 text-white py-4 px-6 flex justify-between items-center shadow-md">
            <div class="flex items-center space-x-2">
                {{-- <img src="/logo.png" alt="Logo" class="h-8 w-auto" onerror="this.src='https://via.placeholder.com/32x32?text=H'"> --}}
                <span class="font-bold text-lg">HIMATEKOM</span>
            </div>
            <div class="flex items-center space-x-4">
                @auth
                    <span class="mr-4">Hello, {{ auth()->user()->name }}</span>
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
                        Login with Google
                    </a>
                @endauth
            </div>
        </nav>
        
        <!-- Hero Section -->
        <div class="relative">
            <div class="bg-gradient-to-r from-orange-600 to-orange-500 h-96 flex items-center justify-center text-white text-center">
                <div class="absolute inset-0 bg-black opacity-20"></div>
                <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    {{-- <img src="/logo.png" alt="HIMATEKOM Logo" class="w-24 h-24 mx-auto mb-6" onerror="this.src='https://via.placeholder.com/96x96?text=HIMATEKOM'"> --}}
                    <h1 class="text-4xl sm:text-5xl font-bold mb-4">Welcome to HIMATEKOM</h1>
                    <p class="text-xl sm:text-2xl max-w-3xl mx-auto">Himpunan Mahasiswa Teknik Komputer - Computer Engineering Student Association</p>
                    <div class="mt-8">
                        @auth
                            <a href="{{ route('dashboard') }}" class="inline-block bg-white text-orange-600 font-medium px-6 py-3 rounded-lg shadow-md hover:bg-orange-50 transition">
                                Go to Dashboard
                            </a>
                        @else
                            <a href="{{ route('google.redirect') }}" class="inline-block bg-white text-orange-600 font-medium px-6 py-3 rounded-lg shadow-md hover:bg-orange-50 transition">
                                Join Our Community
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- About Section -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">About Our Organization</h2>
                <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                    HIMATEKOM is dedicated to supporting Computer Engineering students through academic resources, professional development, and community building.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Academic Excellence</h3>
                    <p class="text-gray-600">We provide study groups, tutoring, and technical workshops to help members excel in their coursework and research.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Professional Development</h3>
                    <p class="text-gray-600">We connect students with industry partners through networking events, internship opportunities, and career workshops.</p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Community Building</h3>
                    <p class="text-gray-600">We foster a supportive community through social events, outreach programs, and collaborative projects.</p>
                </div>
            </div>
        </section>

        <!-- Latest Events Section -->
        <section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900">Upcoming Events</h2>
                    <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                        Join us for our latest activities and opportunities.
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- This would be populated from the database in a real implementation -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Tech Workshop Series</h3>
                            <p class="text-gray-600 mb-4">Learn the latest technologies with our hands-on workshop series.</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                           <!-- Continuing resources/views/welcome.blade.php -->
                           <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        May 25, 2025
                    </div>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-500 font-medium">
                        Learn more →
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Annual General Meeting</h3>
                    <p class="text-gray-600 mb-4">Join us for our annual meeting to discuss plans for the upcoming year.</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        June 10, 2025
                    </div>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-500 font-medium">
                        Learn more →
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-48 bg-gray-200 flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Coding Challenge</h3>
                    <p class="text-gray-600 mb-4">Test your programming skills in our annual coding competition.</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        July 15, 2025
                    </div>
                    <a href="{{ route('events.index') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-500 font-medium">
                        Learn more →
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('events.index') }}" class="inline-block bg-orange-600 text-white font-medium px-6 py-3 rounded-lg shadow-md hover:bg-orange-700 transition">
                View All Events
            </a>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900">Latest News</h2>
            <p class="mt-4 text-lg text-gray-600 max-w-3xl mx-auto">
                Stay updated with the latest happenings in our organization.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- This would be populated from the database in a real implementation -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row">
                <div class="md:w-1/3 h-48 md:h-auto bg-gray-200 flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div class="p-6 md:w-2/3">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">HIMATEKOM Successfully Hosts Tech Exhibition</h3>
                    <p class="text-gray-600 mb-4">The annual technology exhibition showcased innovative projects from computer engineering students.</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        April 15, 2025
                    </div>
                    <a href="{{ route('news.index') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-500 font-medium">
                        Read more →
                    </a>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row">
                <div class="md:w-1/3 h-48 md:h-auto bg-gray-200 flex items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <div class="p-6 md:w-2/3">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">New Board Members Elected for 2025-2026</h3>
                    <p class="text-gray-600 mb-4">HIMATEKOM welcomes the newly elected board members who will lead the organization for the next academic year.</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                        </svg>
                        April 10, 2025
                    </div>
                    <a href="{{ route('news.index') }}" class="mt-4 inline-block text-orange-600 hover:text-orange-500 font-medium">
                        Read more →
                    </a>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-10">
            <a href="{{ route('news.index') }}" class="inline-block bg-orange-600 text-white font-medium px-6 py-3 rounded-lg shadow-md hover:bg-orange-700 transition">
                View All News
            </a>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-orange-600 text-white">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-3xl font-bold mb-4">Join Our Community Today</h2>
        <p class="text-xl max-w-3xl mx-auto mb-8">
            Become a member of HIMATEKOM and access exclusive resources, events, and opportunities.
        </p>
        @auth
            <a href="{{ route('dashboard') }}" class="inline-block bg-white text-orange-600 font-medium px-8 py-3 rounded-lg shadow-md hover:bg-orange-50 transition">
                Go to Dashboard
            </a>
        @else
            <a href="{{ route('google.redirect') }}" class="inline-block bg-white text-orange-600 font-medium px-8 py-3 rounded-lg shadow-md hover:bg-orange-50 transition">
                Sign Up Now
            </a>
        @endauth
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">HIMATEKOM</h3>
                <p class="text-gray-400">
                    Himpunan Mahasiswa Teknik Komputer - Computer Engineering Student Association
                </p>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('events.index') }}" class="text-gray-400 hover:text-white transition">Events</a></li>
                    <li><a href="{{ route('news.index') }}" class="text-gray-400 hover:text-white transition">News</a></li>
                    <li><a href="{{ route('galleries.index') }}" class="text-gray-400 hover:text-white transition">Gallery</a></li>
                    @auth
                    <li><a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition">Dashboard</a></li>
                    @else
                    <li><a href="{{ route('google.redirect') }}" class="text-gray-400 hover:text-white transition">Login</a></li>
                    @endauth
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Departments</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Education & Development</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Information & Communication</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Research & Technology</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white transition">Social & Community Service</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <ul class="space-y-2">
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mr-2 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-gray-400">Computer Engineering Building, University Campus</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mr-2 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-400">info@himatekom.org</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="h-5 w-5 text-gray-400 mr-2 mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-gray-400">(123) 456-7890</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mt-12 border-t border-gray-700 pt-8 flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} HIMATEKOM. All rights reserved.</p>
            <div class="mt-4 md:mt-0 flex space-x-6">
                <a href="#" class="text-gray-400 hover:text-white">
                    <span class="sr-only">Facebook</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <a href="#" class="text-gray-400 hover:text-white">
                    <span class="sr-only">Instagram</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                    </svg>
                </a>
                
                <a href="#" class="text-gray-400 hover:text-white">
                    <span class="sr-only">Twitter</span>
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd" d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Flash Messages -->
@if(session('success'))
<div class="fixed bottom-5 right-5 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg" role="alert">
    <div class="flex items-center">
        <div class="py-1">
            <svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
            </svg>
        </div>
        <div>
            <p>{{ session('success') }}</p>
        </div>
        <div class="ml-4">
            <button type="button" class="text-green-700" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed bottom-5 right-5 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg" role="alert">
    <div class="flex items-center">
        <div class="py-1">
            <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
            </svg>
        </div>
        <div>
            <p>{{ session('error') }}</p>
        </div>
        <div class="ml-4">
            <button type="button" class="text-red-700" onclick="this.parentElement.parentElement.parentElement.style.display='none'">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <!-- Continuing resources/views/welcome.blade.php -->
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endif
</body>
</html>