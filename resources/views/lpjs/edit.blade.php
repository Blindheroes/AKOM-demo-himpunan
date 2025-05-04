<!-- resources/views/lpjs/edit.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- LPJ Header -->
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-2xl font-semibold text-gray-900">Edit LPJ: {{ $lpj->title }}</h1>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Fill in all the required sections below based on the template structure.
                    </p>
                </div>

                <!-- Edit Form -->
                <form action="{{ route('lpjs.update', $lpj) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="px-4 py-5 sm:p-6">
                        @if (session('success'))
                            <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">
                                            {{ session('success') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">
                                            {{ session('error') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="mb-5">
                            <h2 class="font-medium text-lg text-gray-900 mb-2">LPJ Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p><span class="font-medium">Event:</span> {{ $lpj->event->title }}</p>
                                    <p><span class="font-medium">Template:</span> {{ $lpj->template->name }}</p>
                                </div>
                                <div>
                                    <p><span class="font-medium">Status:</span> 
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                            $lpj->status == 'approved' ? 'bg-green-100 text-green-800' : 
                                            ($lpj->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                                            ($lpj->status == 'submitted' ? 'bg-blue-100 text-blue-800' : 
                                            'bg-yellow-100 text-yellow-800')) 
                                        }}">
                                            {{ ucfirst($lpj->status) }}
                                        </span>
                                    </p>
                                    <p><span class="font-medium">Created:</span> {{ $lpj->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h2 class="font-medium text-lg text-gray-900 mb-2">LPJ Content</h2>
                            <p class="text-sm text-gray-500 mb-4">
                                Fill in all the required sections below according to the template structure. All fields are required.
                            </p>

                            <div class="space-y-6">
                                @php
                                    // Improved content handling with multiple fallbacks
                                    $contentData = [];
                                    $hasValidStructure = false;
                                    
                                    // First try: Get from LPJ content if it's in the expected format
                                    if (!empty($lpj->content) && is_array($lpj->content)) {
                                        // Check if the content has a valid structure (direct mapping)
                                        $hasDirectStructure = false;
                                        foreach ($lpj->content as $section => $fields) {
                                            if (is_array($fields)) {
                                                $hasDirectStructure = true;
                                                break;
                                            }
                                        }
                                        
                                        if ($hasDirectStructure) {
                                            $contentData = $lpj->content;
                                            $hasValidStructure = true;
                                        }
                                        // Check if it has a 'sections' array like the template
                                        elseif (isset($lpj->content['sections']) && is_array($lpj->content['sections'])) {
                                            foreach ($lpj->content['sections'] as $section) {
                                                if (isset($section['title']) && isset($section['fields'])) {
                                                    $sectionContent = [];
                                                    foreach ($section['fields'] as $field) {
                                                        if (isset($field['name'])) {
                                                            $sectionContent[$field['name']] = '';
                                                        }
                                                    }
                                                    $contentData[$section['title']] = $sectionContent;
                                                    $hasValidStructure = true;
                                                }
                                            }
                                        }
                                    }
                                    
                                    // Second try: Get from template structure if content is not valid
                                    if (!$hasValidStructure && $lpj->template) {
                                        $templateStructure = null;
                                        
                                        // Try to get the template structure
                                        if (is_array($lpj->template->structure)) {
                                            $templateStructure = $lpj->template->structure;
                                        } elseif (is_string($lpj->template->structure)) {
                                            $decoded = json_decode($lpj->template->structure, true);
                                            if (json_last_error() === JSON_ERROR_NONE) {
                                                $templateStructure = $decoded;
                                            }
                                        }
                                        
                                        if ($templateStructure) {
                                            // Handle case where template has 'sections' key
                                            if (isset($templateStructure['sections']) && is_array($templateStructure['sections'])) {
                                                foreach ($templateStructure['sections'] as $section) {
                                                    if (isset($section['title']) && isset($section['fields'])) {
                                                        $sectionContent = [];
                                                        foreach ($section['fields'] as $field) {
                                                            if (isset($field['name'])) {
                                                                $sectionContent[$field['name']] = '';
                                                            }
                                                        }
                                                        $contentData[$section['title']] = $sectionContent;
                                                        $hasValidStructure = true;
                                                    }
                                                }
                                            }
                                            // Handle case where template has direct key-value structure
                                            elseif (is_array($templateStructure)) {
                                                $hasDirectStructure = false;
                                                foreach ($templateStructure as $section => $fields) {
                                                    if (is_array($fields) && is_string($section)) {
                                                        $contentData[$section] = array_fill_keys(array_keys($fields), '');
                                                        $hasDirectStructure = true;
                                                    }
                                                }
                                                $hasValidStructure = $hasDirectStructure;
                                            }
                                        }
                                    }
                                    
                                    // Third try: Create a simple default structure for this LPJ
                                    if (!$hasValidStructure) {
                                        $contentData = [
                                            'Event Information' => [
                                                'event_name' => $lpj->event->title ?? '',
                                                'event_date' => $lpj->event->start_date ?? now()->format('Y-m-d'),
                                                'description' => ''
                                            ],
                                            'Implementation' => [
                                                'summary' => '',
                                                'participants' => '',
                                            ],
                                            'Financial Report' => [
                                                'budget' => '',
                                                'expenses' => '',
                                                'balance' => '',
                                            ],
                                            'Conclusion' => [
                                                'achievements' => '',
                                                'recommendations' => '',
                                            ]
                                        ];
                                        $hasValidStructure = true;
                                    }
                                @endphp
                                
                                @if($hasValidStructure && count($contentData) > 0)
                                    @foreach($contentData as $section => $content)
                                        <div class="bg-gray-50 p-4 rounded-md mb-4">
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $section }}</h3>
                                            
                                            @if(is_array($content))
                                                @foreach($content as $field => $value)
                                                    <div class="mb-3">
                                                        <label for="content[{{ $section }}][{{ $field }}]" class="block text-sm font-medium text-gray-700 mb-1">
                                                            {{ ucfirst(str_replace('_', ' ', $field)) }}
                                                        </label>
                                                        
                                                        <textarea 
                                                            id="content[{{ $section }}][{{ $field }}]" 
                                                            name="content[{{ $section }}][{{ $field }}]" 
                                                            rows="3" 
                                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                        >{{ is_string($value) ? $value : json_encode($value) }}</textarea>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="mb-3">
                                                    <textarea 
                                                        id="content[{{ $section }}]" 
                                                        name="content[{{ $section }}]" 
                                                        rows="4" 
                                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                                    >{{ is_string($content) ? $content : json_encode($content) }}</textarea>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="bg-yellow-50 p-4 rounded-md">
                                        <p class="text-yellow-800">
                                            No content sections found in this LPJ template. Please contact an administrator.
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="pt-5 border-t border-gray-200">
                            <div class="flex justify-end">
                                <a href="{{ route('lpjs.show', $lpj) }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </a>

                                <button type="submit" name="status" value="draft" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save as Draft
                                </button>

                                <button type="submit" name="status" value="pending" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    Submit for Approval
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>