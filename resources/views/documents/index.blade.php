<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Documents</h1>
                @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Add Document
                </a>
                @endif
            </div>
            
            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mt-6 p-4">
                <form action="{{ route('documents.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by title, description..." class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="category" name="category" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Categories</option>
                            <option value="report" {{ request('category') == 'report' ? 'selected' : '' }}>Report</option>
                            <option value="handbook" {{ request('category') == 'handbook' ? 'selected' : '' }}>Handbook</option>
                            <option value="guidelines" {{ request('category') == 'guidelines' ? 'selected' : '' }}>Guidelines</option>
                            <option value="minutes" {{ request('category') == 'minutes' ? 'selected' : '' }}>Meeting Minutes</option>
                            <option value="proposal" {{ request('category') == 'proposal' ? 'selected' : '' }}>Proposal</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="visibility" class="block text-sm font-medium text-gray-700">Visibility</label>
                        <select id="visibility" name="visibility" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Visibility</option>
                            <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                            <option value="members" {{ request('visibility') == 'members' ? 'selected' : '' }}>Members</option>
                            <option value="executives" {{ request('visibility') == 'executives' ? 'selected' : '' }}>Executives</option>
                        </select>
                    </div>
                    
                    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive']))
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                    </div>
                </form>
                
                @if(request('search') || request('category') || request('visibility') || request('status'))
                <div class="mt-4 flex">
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-gray-900">
                        <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear Filters
                    </a>
                </div>
                @endif
            </div>
            
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    @forelse($documents ?? [] as $document)
                    <li>
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-md flex items-center justify-center">
                                        <svg class="h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="flex items-center">
                                            <a href="{{ route('documents.show', $document) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">{{ $document->title }}</a>
                                            
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $document->visibility == 'public' ? 'bg-green-100 text-green-800' : 
                                                  ($document->visibility == 'members' ? 'bg-blue-100 text-blue-800' : 
                                                  ($document->visibility == 'executives' ? 'bg-purple-100 text-purple-800' : 
                                                  'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst($document->visibility) }}
                                            </span>
                                            
                                            @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive']))
                                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $document->status == 'published' ? 'bg-green-100 text-green-800' : 
                                                  ($document->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                  ($document->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                                                  'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst($document->status) }}
                                            </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500">
                                            <span>{{ ucfirst($document->category) }}</span> • 
                                            <span>Added by {{ $document->user ? $document->user->name : 'Unknown User' }}</span> • 
                                            <span>{{ $document->created_at->format('M d, Y') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="{{ route('documents.download', $document) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Download
                                    </a>
                                    @if(Auth::check() && Auth::user()->can('update', $document))
                                    <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </li>
                    @empty
                    <li class="px-4 py-6 text-center text-gray-500">
                        No documents available.
                    </li>
                    @endforelse
                </ul>
            </div>
            
            @if(isset($documents) && $documents->hasPages())
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>