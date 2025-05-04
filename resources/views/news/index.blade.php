<!-- resources/views/news/index.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">News</h1>
                @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                <a href="{{ route('news.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create News
                </a>
                @endif
            </div>

            <!-- Filters -->
            <div class="bg-white shadow rounded-lg mt-6 p-4">
                <form action="{{ route('news.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by title, content..." class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
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
                    
                    @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    @endif
                    
                    <div>
                        <label for="featured" class="block text-sm font-medium text-gray-700">Featured</label>
                        <select id="featured" name="featured" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">All News</option>
                            <option value="1" {{ request('featured') == '1' ? 'selected' : '' }}>Featured Only</option>
                        </select>
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                    </div>
                </form>
                
                @if(request('department') || request('status') || request('featured') || request('search'))
                <div class="mt-4 flex">
                    <a href="{{ route('news.index') }}" class="inline-flex items-center px-3 py-1 text-sm text-gray-700 hover:text-gray-900">
                        <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Clear Filters
                    </a>
                </div>
                @endif
            </div>

            <!-- Featured News Section (if any) -->
            @if($news->where('is_featured', true)->count() > 0 && !request('featured'))
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900">Featured News</h2>
                <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($news->where('is_featured', true)->take(2) as $featured)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        @if($featured->featured_image)
                        <img src="{{ Storage::url($featured->featured_image) }}" alt="{{ $featured->title }}" class="w-full h-56 object-cover">
                        @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        @endif
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $featured->department->name }}
                                    </span>
                                </div>
                                <div class="ml-2">
                                    <p class="text-xs text-gray-500">
                                        {{ $featured->published_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('news.show', $featured) }}" class="block mt-2">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $featured->title }}</h3>
                                <p class="mt-3 text-base text-gray-500 line-clamp-3">
                                    {{ $featured->excerpt ?? Str::limit(strip_tags($featured->content), 150) }}
                                </p>
                            </a>
                            <div class="mt-4 flex items-center">
                                <div class="flex-shrink-0">
                                    @if($featured->author && $featured->author->profile_photo_path)
                                    <img class="h-10 w-10 rounded-full" src="{{ Storage::url($featured->author->profile_photo_path) }}" alt="{{ $featured->author->name }}">
                                    @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-500 font-medium">
                                        {{ substr($featured->author->name ?? 'A', 0, 1) }}
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $featured->author->name ?? 'Anonymous' }}</p>
                                    <div class="flex space-x-1 text-sm text-gray-500">
                                        <span>{{ $featured->views }} views</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- All News Section -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900">{{ request('featured') ? 'Featured News' : 'All News' }}</h2>
                


                <!-- Continuing resources/views/news/index.blade.php -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($news as $article)
                        @if(!$article->is_featured || request('featured'))
                        <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col">
                            @if($article->featured_image)
                            <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-48 object-cover">
                            @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                            @endif
                            <div class="p-6 flex-grow">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $article->department->name }}
                                        </span>
                                    </div>
                                    <div class="ml-2">
                                        <p class="text-xs text-gray-500">
                                            {{ $article->published_at->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ route('news.show', $article) }}" class="block mt-2">
                                    <h3 class="text-lg font-medium text-gray-900">{{ $article->title }}</h3>
                                    <p class="mt-3 text-sm text-gray-500 line-clamp-3">
                                        {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 150) }}
                                    </p>
                                </a>
                            </div>
                            <div class="px-6 pb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="ml-1 text-xs text-gray-500">{{ $article->views }} views</span>
                                    </div>
                                    <a href="{{ route('news.show', $article) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                        Read more
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @empty
                    <div class="col-span-full bg-white shadow rounded-lg p-6 text-center text-gray-500">
                        No news articles found. Check back later for updates.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $news->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>