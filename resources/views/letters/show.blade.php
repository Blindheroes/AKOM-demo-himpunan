<!-- resources/views/letters/show.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back button and title -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('letters.index') }}" class="inline-flex items-center mr-3 px-2 py-1 text-sm text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900 truncate">{{ $letter->title }}</h1>
                </div>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full
                    {{ 
                        $letter->status == 'draft' ? 'bg-yellow-100 text-yellow-800' : 
                        ($letter->status == 'pending' ? 'bg-blue-100 text-blue-800' : 
                        ($letter->status == 'signed' ? 'bg-green-100 text-green-800' :
                        ($letter->status == 'sent' ? 'bg-purple-100 text-purple-800' : 
                        'bg-gray-100 text-gray-800')))
                    }}">
                    {{ ucfirst($letter->status) }}
                </span>
            </div>

            <!-- Action buttons -->
            @if(Auth::check())
            <div class="mt-4 flex flex-wrap gap-2">
                @if(in_array($letter->status, ['draft', 'pending']) && (Auth::user()->can('update', $letter)))
                <a href="{{ route('letters.edit', $letter) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Edit
                </a>
                @endif

                @if(in_array($letter->status, ['draft', 'pending']) && Auth::user()->can('sign', $letter))
                <form action="{{ route('letters.sign', $letter) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                        </svg>
                        Sign
                    </button>
                </form>
                @endif

                @if($letter->status == 'signed' && Auth::user()->can('update', $letter))
                <form action="{{ route('letters.mark-sent', $letter) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                        </svg>
                        Mark as Sent
                    </button>
                </form>
                @endif

                @if($letter->status == 'sent' && Auth::user()->can('update', $letter))
                <form action="{{ route('letters.archive', $letter) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        Archive
                    </button>
                </form>
                @endif

                @if($letter->document_path)
                <a href="{{ route('letters.download', $letter) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Download PDF
                </a>
                @endif

                @if(in_array($letter->status, ['draft']) && Auth::user()->can('delete', $letter))
                <form action="{{ route('letters.destroy', $letter) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this letter?')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Delete
                    </button>
                </form>
                @endif
            </div>
            @endif

            <!-- Letter Details -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
                <div class="px-4 py-5 sm:px-6 border-b">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Letter Information</h3>
                </div>
                <div class="border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Letter Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $letter->letter_number ?? 'Not Assigned' }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Template</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $letter->template->name }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $letter->date->format('d F Y') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Regarding</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $letter->regarding }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Recipient</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $letter->recipient }}
                                @if($letter->recipient_position)
                                    <br>{{ $letter->recipient_position }}
                                @endif
                                @if($letter->recipient_institution)
                                    <br>{{ $letter->recipient_institution }}
                                @endif
                            </dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $letter->department->name }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Created By</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ optional($letter->creator)->name ?? 'Unknown User' }}</dd>
                        </div>
                        @if($letter->signed_by)
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Signed By</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ optional($letter->signer)->name ?? 'Unknown User' }}
                                @if($letter->signing_date)
                                <br><span class="text-xs text-gray-500">Signed on {{ $letter->signing_date->format('d F Y, H:i') }}</span>
                                @endif
                            </dd>
                        </div>
                        @endif
                        <div class="bg-gray-50 px-4 py-5 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Content</dt>
                            <dd class="mt-1 text-sm text-gray-900 prose max-w-none">
                                {!! nl2br(e($letter->content)) !!}
                            </dd>
                        </div>
                        @if($letter->attachment)
                        <div class="bg-white px-4 py-5 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Attachments</dt>
                            <dd class="mt-1 text-sm text-gray-900 prose max-w-none">
                                {!! nl2br(e($letter->attachment)) !!}
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Metadata -->
            <div class="mt-6 text-sm text-gray-500">
                <p>Letter ID: {{ $letter->id }} &bull; Version: {{ $letter->version }}</p>
                <p>Created: {{ $letter->created_at->format('d F Y, H:i') }} &bull; Last Updated: {{ $letter->updated_at->format('d F Y, H:i') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>