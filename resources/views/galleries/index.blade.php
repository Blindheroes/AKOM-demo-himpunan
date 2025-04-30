<!-- resources/views/galleries/index.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Galleries</h1>
                @if(Auth::check() && in_array(Auth::user()->role, ['admin', 'executive', 'staff']))
                <a href="{{ route('galleries.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create Gallery
                </a>
                @endif
            </div>

            <!-- Featured Galleries Section -->
            @if($galleries->where('is_featured', true)->count() > 0)
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900">Featured Galleries</h2>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($galleries->where('is_featured', true)->take(2) as $featured)
                    <div class="bg-white shadow-md rounded-lg overflow-hidden">
                        @if($featured->images->count() > 0)
                        <img src="{{ Storage::url($featured->images->first()->image_path) }}" alt="{{ $featured->title }}" class="w-full h-56 object-cover">
                        @else
                        <div class="w-full h-56 bg-gray-200 flex items-center justify-center">
                            <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        <div class="p-6">
                            <a href="{{ route('galleries.show', $featured) }}" class="block">
                                <h3 class="text-xl font-semibold text-gray-900">{{ $featured->title }}</h3>
                                <p class="mt-3 text-base text-gray-500 line-clamp-2">
                                    {{ $featured->description ?? 'Photo gallery from ' . $featured->title }}
                                </p>
                            </a>
                            <div class="mt-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-1 text-sm text-gray-500">{{ $featured->images->count() }} photos</span>
                                </div>
                                @if($featured->event)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $featured->event->title }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- All Galleries Section -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900">All Galleries</h2>
                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($galleries as $gallery)
                    <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col">
                        <div class="relative">
                            @if($gallery->images->count() > 0)
                            <img src="{{ Storage::url($gallery->images->first()->image_path) }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover">
                            @else
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                            @if($gallery->images->count() > 1)
                            <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                +{{ $gallery->images->count() - 1 }} more
                            </div>
                            @endif
                        </div>
                        <div class="p-6 flex-grow">
                            <a href="{{ route('galleries.show', $gallery) }}" class="block">
                                <h3 class="text-lg font-medium text-gray-900">{{ $gallery->title }}</h3>
                                <p class="mt-3 text-sm text-gray-500 line-clamp-2">
                                    {{ $gallery->description ?? 'Photo gallery from ' . $gallery->title }}
                                </p>
                            </a>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @if($gallery->event)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ Str::limit($gallery->event->title, 15) }}
                                </span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $gallery->images->count() }} photos
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $gallery->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="px-6 pb-4">
                            <a href="{{ route('galleries.show', $gallery) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                View gallery
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full bg-white shadow rounded-lg p-6 text-center text-gray-500">
                        No galleries found. Check back later for updates.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $galleries->links() }}
            </div>
        </div>
    </div>
</x-app-layout>