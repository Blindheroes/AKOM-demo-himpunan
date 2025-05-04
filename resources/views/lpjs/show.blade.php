<!-- resources/views/lpjs/show.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <!-- LPJ Header -->
                <div class="px-4 py-5 sm:px-6 flex justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">{{ $lpj->title }}</h1>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mt-2 {{ 
                            $lpj->status == 'approved' ? 'bg-green-100 text-green-800' : 
                            ($lpj->status == 'rejected' ? 'bg-red-100 text-red-800' : 
                            ($lpj->status == 'submitted' ? 'bg-blue-100 text-blue-800' : 
                            'bg-yellow-100 text-yellow-800')) 
                        }}">
                            {{ ucfirst($lpj->status) }}
                        </span>
                    </div>
                    
                    @if(Auth::check() && Auth::user()->can('update', $lpj))
                    <div class="flex space-x-2">
                        <a href="{{ route('lpjs.edit', $lpj) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </a>
                        
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'executive')
                        @if($lpj->status === 'submitted')
                        <form action="{{ route('lpjs.approve', $lpj) }}" method="POST" class="inline">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Approve
                            </button>
                        </form>
                        
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="document.getElementById('reject-modal').classList.remove('hidden')">
                            <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Reject
                        </button>
                        @endif
                        @endif
                        
                        @if(Auth::user()->can('delete', $lpj))
                        <form action="{{ route('lpjs.destroy', $lpj) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this LPJ?');" class="inline">
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
                
                <!-- LPJ Information -->
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Event</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <a href="{{ route('events.show', $lpj->event) }}" class="text-indigo-600 hover:text-indigo-900">
                                        {{ $lpj->event->title }}
                                    </a>
                                </div>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Template</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                    </svg>
                                    {{ $lpj->template->name }}
                                </div>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->creator->name }}
                                </div>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                                    </svg>
                                    {{ $lpj->event->department->name }}
                                </div>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Created At</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->created_at->format('F j, Y g:i A') }}
                                </div>
                            </dd>
                        </div>
                        
                        @if($lpj->status === 'submitted' || $lpj->status === 'approved' || $lpj->status === 'rejected')
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Submitted At</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->submitted_at ? $lpj->submitted_at->format('F j, Y g:i A') : 'Not submitted yet' }}
                                </div>
                            </dd>
                        </div>
                        @endif
                        
                        @if($lpj->status === 'approved')
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Approved By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->approver ? $lpj->approver->name : 'N/A' }}
                                </div>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Approved At</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->approved_at ? $lpj->approved_at->format('F j, Y g:i A') : 'N/A' }}
                                </div>
                            </dd>
                        </div>
                        
                        @if($lpj->approval_notes)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Approval Notes</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-green-50 p-3 rounded">
                                {{ $lpj->approval_notes }}
                            </dd>
                        </div>
                        @endif
                        @endif
                        
                        @if($lpj->status === 'rejected')
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Rejected By</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->rejector ? $lpj->rejector->name : 'N/A' }}
                                </div>
                            </dd>
                        </div>
                        
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Rejected At</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="flex items-center">
                                    <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $lpj->rejected_at ? $lpj->rejected_at->format('F j, Y g:i A') : 'N/A' }}
                                </div>
                            </dd>
                        </div>
                        
                        @if($lpj->rejection_reason)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                            <dd class="mt-1 text-sm text-gray-900 bg-red-50 p-3 rounded">
                                {{ $lpj->rejection_reason }}
                            </dd>
                        </div>
                        @endif
                        @endif
                        
                        <!-- LPJ Content Section -->
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">LPJ Content</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <div class="bg-gray-50 p-4 rounded-md">
                                    @php
                                        // More robust content handling for various edge cases
                                        $contentData = $lpj->content;
                                        
                                        // Handle case where content might be string
                                        if (is_string($contentData)) {
                                            // Try to decode if it looks like JSON
                                            if (substr($contentData, 0, 1) === '{' || substr($contentData, 0, 1) === '[') {
                                                $decoded = json_decode($contentData, true);
                                                if (json_last_error() === JSON_ERROR_NONE) {
                                                    $contentData = $decoded;
                                                } else {
                                                    // If JSON decode fails, check if it's a serialized structure
                                                    $sections = [];
                                                    if (strpos($contentData, 'sections') !== false) {
                                                        // Extract section data
                                                        preg_match('/sections\s*\[\s*([^\]]+)\]/', $contentData, $matches);
                                                        if (!empty($matches[1])) {
                                                            $sections = [
                                                                'sections' => explode(',', $matches[1])
                                                            ];
                                                        } else {
                                                            // If we can't parse it, just display as raw string
                                                            $sections = [
                                                                'Content' => $contentData
                                                            ];
                                                        }
                                                        $contentData = $sections;
                                                    } else {
                                                        // If no sections found, just display as raw content
                                                        $contentData = [
                                                            'Content' => $contentData
                                                        ];
                                                    }
                                                }
                                            } else {
                                                // Not JSON-like, treat as plain content
                                                $contentData = [
                                                    'Content' => $contentData
                                                ];
                                            }
                                        }
                                        
                                        // Special handling for 'sections' key which might be in some templates
                                        if (is_array($contentData) && isset($contentData['sections']) && !empty($contentData['sections'])) {
                                            // If it contains a sections key, restructure for proper display
                                            $restructured = [];
                                            foreach ($contentData['sections'] as $section) {
                                                if (isset($section['title'])) {
                                                    $restructured[$section['title']] = isset($section['fields']) ? $section['fields'] : [];
                                                }
                                            }
                                            if (!empty($restructured)) {
                                                $contentData = $restructured;
                                            }
                                        }
                                        
                                        // Ensure we have something to display
                                        if (empty($contentData) || !is_array($contentData)) {
                                            $contentData = ['Note' => 'No structured content available'];
                                        }
                                    @endphp
                                    
                                    @foreach($contentData as $section => $content)
                                        <div class="mb-4">
                                            <h3 class="text-lg font-medium text-gray-900">{{ is_string($section) ? $section : 'Section ' . $loop->iteration }}</h3>
                                            <div class="mt-2 prose max-w-none">
                                                @if(is_array($content))
                                                    <ul class="list-disc pl-5">
                                                        @foreach($content as $key => $value)
                                                            <li>
                                                                @if(is_numeric($key) || !is_string($key))
                                                                    {!! nl2br(e(is_string($value) ? $value : json_encode($value, JSON_PRETTY_PRINT))) !!}
                                                                @else
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                                    {!! nl2br(e(is_string($value) ? $value : json_encode($value, JSON_PRETTY_PRINT))) !!}
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @elseif(is_object($content))
                                                    <ul class="list-disc pl-5">
                                                        @foreach((array)$content as $key => $value)
                                                            <li>
                                                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> 
                                                                {!! nl2br(e(is_string($value) ? $value : json_encode($value, JSON_PRETTY_PRINT))) !!}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {!! nl2br(e(is_string($content) ? $content : json_encode($content, JSON_PRETTY_PRINT))) !!}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
                
                <!-- Download Button -->
                <div class="px-4 py-5 sm:px-6 bg-gray-50 border-t border-gray-200">
                    <div class="flex justify-end">
                        <a href="{{ route('lpjs.download', $lpj) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Rejection Modal -->
            <div id="reject-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form action="{{ route('lpjs.reject', $lpj) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                            Reject LPJ
                                        </h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500 mb-4">
                                                Please provide a reason for rejection. This will be shown to the creator of the LPJ.
                                            </p>
                                            <div>
                                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700">Rejection Reason</label>
                                                <textarea id="rejection_reason" name="rejection_reason" rows="4" class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Reject
                                </button>
                                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="document.getElementById('reject-modal').classList.add('hidden')">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>