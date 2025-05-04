@extends('admin.layout')

@section('title', 'Dashboard')

@section('header', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- User Stats -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Users
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['users']['total'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm text-right">
                <span class="inline-flex text-xs mr-4">
                    <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 mr-1">Members: {{ $stats['users']['members'] }}</span>
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 mr-1">Staff: {{ $stats['users']['staff'] }}</span>
                    <span class="px-2 py-1 rounded-full bg-purple-100 text-purple-800 mr-1">Executives: {{ $stats['users']['executives'] }}</span>
                    <span class="px-2 py-1 rounded-full bg-red-100 text-red-800">Admins: {{ $stats['users']['admins'] }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Events Stats -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Events
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['events']['total'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm text-right">
                <span class="inline-flex text-xs">
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 mr-1">Published: {{ $stats['events']['published'] }}</span>
                    <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800 mr-1">Upcoming: {{ $stats['events']['upcoming'] }}</span>
                    <span class="px-2 py-1 rounded-full bg-gray-100 text-gray-800">Past: {{ $stats['events']['past'] }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Documents Stats -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Documents
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['documents']['total'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm text-right">
                <span class="inline-flex text-xs">
                    <span class="px-2 py-1 rounded-full bg-green-100 text-green-800 mr-1">Published: {{ $stats['documents']['published'] }}</span>
                    <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">Pending: {{ $stats['documents']['pending'] }}</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Letters Stats -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">
                            Total Letters
                        </dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['letters']['total'] }}</div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm text-right">
                <span class="inline-flex text-xs">
                    <span class="px-2 py-1 rounded-full bg-yellow-100 text-yellow-800">Pending: {{ $stats['letters']['pending'] }}</span>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Activity -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Activity</h3>
        </div>
        <div class="px-4 sm:px-6 py-4">
            <ul class="divide-y divide-gray-200">
                @forelse($recentActivity as $activity)
                <li class="py-3">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($activity->type === 'Event')
                            <div class="bg-orange-100 p-2 rounded-full">
                                <svg class="h-5 w-5 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @elseif($activity->type === 'News')
                            <div class="bg-purple-100 p-2 rounded-full">
                                <svg class="h-5 w-5 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            @else
                            <div class="bg-green-100 p-2 rounded-full">
                                <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">
                                {{ $activity->title }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $activity->type }} created {{ $activity->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </li>
                @empty
                <li class="py-3 text-center text-gray-500">
                    No recent activity
                </li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="px-4 py-5 border-b border-gray-200 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Quick Actions</h3>
        </div>
        <div class="px-4 sm:px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('admin.users.create') }}" class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 flex items-center">
                    <div class="bg-indigo-100 p-2 rounded-full">
                        <svg class="h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-gray-800 font-medium">Add New User</span>
                </a>
                
                <a href="{{ route('admin.departments.create') }}" class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 flex items-center">
                    <div class="bg-orange-100 p-2 rounded-full">
                        <svg class="h-5 w-5 text-orange-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-gray-800 font-medium">Create Department</span>
                </a>
                
                <a href="{{ route('events.create') }}" class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 flex items-center">
                    <div class="bg-green-100 p-2 rounded-full">
                        <svg class="h-5 w-5 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-gray-800 font-medium">Create Event</span>
                </a>
                
                <a href="{{ route('documents.create') }}" class="bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg p-4 flex items-center">
                    <div class="bg-blue-100 p-2 rounded-full">
                        <svg class="h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="ml-3 text-gray-800 font-medium">Upload Document</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection