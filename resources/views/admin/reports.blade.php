@extends('admin.layout')

@section('title', 'Reports & Analytics')

@section('header', 'Reports & Analytics')

@section('content')
<div class="space-y-8">
    <!-- Overview Statistics -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">System Overview</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Events Overview -->
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">Event Activity</h3>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900">{{ $monthlyEvents->sum('count') }}</div>
                            <div class="text-sm text-green-600">
                                Last 12 months
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members Growth -->
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">User Growth</h3>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900">{{ $userStats->sum('count') }}</div>
                            <div class="text-sm text-blue-600">
                                Last 12 months
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Department Activity -->
            <div class="bg-purple-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">Active Departments</h3>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900">{{ $departmentStats->count() }}</div>
                            <div class="text-sm text-purple-600">
                                Organizing events
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Engagement Rate -->
            <div class="bg-orange-50 rounded-lg p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-sm font-medium text-gray-900">Department Engagement</h3>
                        <div class="mt-1 flex items-baseline justify-between">
                            <div class="text-2xl font-semibold text-gray-900">
                                {{ $departmentStats->sum('events_count') / ($departmentStats->count() ?: 1) }}
                            </div>
                            <div class="text-sm text-orange-600">
                                Events per department
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Activity Chart -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Event Activity</h2>
        
        <div class="h-80">
            <canvas id="eventActivityChart"></canvas>
        </div>
    </div>

    <!-- Department Performance -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Department Performance</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Department
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Members
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Events
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Events Per Member
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($departmentStats as $department)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $department->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $department->users_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $department->events_count }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $department->users_count > 0 ? round($department->events_count / $department->users_count, 2) : 0 }}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Growth Chart -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">User Growth</h2>
        
        <div class="h-80">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Event Activity Chart
        const eventCtx = document.getElementById('eventActivityChart').getContext('2d');
        const eventMonths = @json($monthlyEvents->pluck('month'));
        const eventCounts = @json($monthlyEvents->pluck('count'));
        
        new Chart(eventCtx, {
            type: 'bar',
            data: {
                labels: eventMonths,
                datasets: [{
                    label: 'Events Created',
                    data: eventCounts,
                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                    borderColor: 'rgb(34, 197, 94)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // User Growth Chart
        const userCtx = document.getElementById('userGrowthChart').getContext('2d');
        const userMonths = @json($userStats->pluck('month'));
        const userCounts = @json($userStats->pluck('count'));
        
        new Chart(userCtx, {
            type: 'line',
            data: {
                labels: userMonths,
                datasets: [{
                    label: 'New Users',
                    data: userCounts,
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection