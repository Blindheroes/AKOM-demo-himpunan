<!-- resources/views/news/show.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- News Header -->
                <div class="px-4 py-5 sm:px-6 flex justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $news->title }}</h1>
                        <div class="mt-2 flex items-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $news->department->name }}
                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                {{ $news->published_at->format('F j, Y') }}
                            </span>
                            <span class="ml-2 text-sm text-gray-500">
                                <svg class="inline-block h-4 w-4 text-gray-400 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $news->views }} views
                            </span>
                        </div>
                    </div>
                    
                    @if(Auth::check() && Auth::user()->can('update', $news))
                    <div class="flex space-x-2">
                        <a href="{{ route('news.edit', $news) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </a>
                        
                        @if(Auth::user()->can('delete', $news))
                        <form action="{{ route('news.destroy', $news) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this news article?');" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                </div>
                
                <!-- Featured Image -->
                @if($news->featured_image)
                <div class="border-t border-gray-200">
                    <img src="{{ Storage::url($news->featured_image) }}" alt="{{ $news->title }}" class="w-full h-64 sm:h-96 object-cover">
                </div>
                @endif
                
                <!-- Author Information -->
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            @if($news->author && $news->author->profile_photo_path)
                            <img class="h-12 w-12 rounded-full" src="{{ Storage::url($news->author->profile_photo_path) }}" alt="{{ $news->author->name }}">
                            @else
                            <div class="h-12 w-12 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-500 font-medium">
                                {{ substr($news->author->name ?? 'A', 0, 1) }}
                            </div>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Author</h3>
                            <p class="text-sm text-gray-600">{{ $news->author->name ?? 'Anonymous' }}</p>
                            @if($news->author && $news->author->department)
                            <p class="text-xs text-gray-500">{{ $news->author->department->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- News Content -->
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <div class="prose max-w-none">
                        @if($news->excerpt)
                        <p class="text-lg font-medium text-gray-500 mb-4">{{ $news->excerpt }}</p>
                        @endif
                        
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </div>
            </div>
            
            <!-- Related News -->
            @if($relatedNews->count() > 0)
            <div class="mt-10">
                <h2 class="text-xl font-semibold text-gray-900">Related News</h2>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedNews as $article)
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
                                    {{ $article->excerpt ?? Str::limit(strip_tags($article->content), 100) }}
                                </p>
                            </a>
                        </div>
                        <div class="px-6 pb-4">
                            <a href="{{ route('news.show', $article) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                Read more
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>