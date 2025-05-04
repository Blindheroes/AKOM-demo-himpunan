<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Select LPJ Template</h1>
            </div>
            
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-md p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Event: {{ $event->title }}</h3>
                
                @if($templates->count() > 0)
                    <div class="mb-6">
                        <h4 class="text-md font-medium text-gray-800 mb-2">Available Templates</h4>
                        <p class="text-sm text-gray-600 mb-4">Select a template to use for creating the LPJ for this event.</p>
                        
                        <form action="{{ route('lpjs.store-with-template') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                            
                            <div class="grid grid-cols-1 gap-4">
                                @foreach($templates as $template)
                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center">
                                            <input id="template-{{ $template->id }}" name="template_id" type="radio" value="{{ $template->id }}" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300" required>
                                            <label for="template-{{ $template->id }}" class="ml-3 block text-sm font-medium text-gray-700">
                                                {{ $template->name }}
                                            </label>
                                        </div>
                                        <div class="mt-2 ml-7">
                                            <p class="text-sm text-gray-500">{{ $template->description }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('lpjs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create LPJ
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500">No templates available. Please contact an administrator.</p>
                        <a href="{{ route('lpjs.index') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to LPJs
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>