<!-- resources/views/events/index.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Events</h1>
                @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                <a href="{{ route('events.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create Event
                </a>
                @endif
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mt-6 p-4">
                <form action="{{ route('events.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by title, description..." class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                        <select id="department" name="department" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Statuses</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                            @endif
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_filter" class="block text-sm font-medium text-gray-700">Date Range</label>
                        <select id="date_filter" name="date_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Dates</option>
                            <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>Past Events</option>
                            <option value="this_week" {{ request('date_filter') == 'this_week' ? 'selected' : '' }}>This Week</option>
                            <option value="this_month" {{ request('date_filter') == 'this_month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>
                    
                    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                    <div>
                        <label for="created_by" class="block text-sm font-medium text-gray-700">Created By</label>
                        <select id="created_by" name="created_by" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Anyone</option>
                            <option value="me" {{ request('created_by') == 'me' ? 'selected' : '' }}>My Events</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                    </div>
                </form>
                
                @if(request('search') || request('department') || request('status') || request('date_filter') || request('created_by'))
                <div class="mt-4 flex">
                    <a href="{{ route('events.index') }}" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-gray-900">
                        <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear Filters
                    </a>
                </div>
                @endif
            </div>

            <!-- Events List -->
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse($events as $event)
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        @if($event->image_path)
                        <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="h-48 w-full object-cover rounded-md mb-4">
                        @else
                        <div class="h-48 w-full bg-gray-200 rounded-md mb-4 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg font-medium text-gray-900 truncate">{{ $event->title }}</h3>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                $event->status == 'published' ? 'bg-green-100 text-green-800' : 
                                ($event->status == 'completed' ? 'bg-blue-100 text-blue-800' : 
                                ($event->status == 'canceled' ? 'bg-red-100 text-red-800' : 
                                'bg-yellow-100 text-yellow-800')) 
                            }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>
                        
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                            {{ $event->start_date->format('M d, Y - H:i') }}
                        </div>
                        
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            {{ $event->location ?? 'Location TBA' }}
                        </div>
                        
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                            </svg>
                            {{ $event->department->name }}
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 line-clamp-3">
                                {{ $event->description }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('events.show', $event) }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                                View details<span class="sr-only"> for {{ $event->title }}</span>
                            </a>
                            
                            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive']))
                            <div class="flex space-x-2">
                                @if($event->status != 'published' && $event->start_date > now())
                                <form action="{{ route('events.status', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="published">
                                    <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-green-500">
                                        Publish
                                    </button>
                                </form>
                                @endif
                                
                                @if($event->status == 'published' && $event->end_date < now())
                                <form action="{{ route('events.status', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-blue-500">
                                        Mark Completed
                                    </button>
                                </form>
                                @endif
                                
                                @if($event->status != 'canceled' && $event->start_date > now())
                                <form action="{{ route('events.status', $event) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="status" value="canceled">
                                    <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-red-500">
                                        Cancel
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
                        No events found. Try adjusting your filters or check back later.
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $events->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>