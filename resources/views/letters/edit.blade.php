<!-- resources/views/letters/edit.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back button and title -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('letters.show', $letter) }}" class="inline-flex items-center mr-3 px-2 py-1 text-sm text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back
                    </a>
                    <h1 class="text-2xl font-semibold text-gray-900">Edit Letter</h1>
                </div>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($letter->status) }}</span>
            </div>

            <!-- Form -->
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('letters.update', $letter) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Errors -->
                        @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                                    <ul class="mt-2 list-disc list-inside text-sm text-red-700">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Version Alert -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        You are editing version {{ $letter->version }} of this letter. Saving changes will create a new version.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 required">Title</label>
                                <div class="mt-1">
                                    <input type="text" name="title" id="title" value="{{ old('title', $letter->title) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="template_id" class="block text-sm font-medium text-gray-700 required">Template</label>
                                <div class="mt-1">
                                    <select id="template_id" name="template_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                        <option value="">Select a template</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template->id }}" {{ (old('template_id', $letter->template_id) == $template->id) ? 'selected' : '' }}>{{ $template->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="department_id" class="block text-sm font-medium text-gray-700 required">Department</label>
                                <div class="mt-1">
                                    <select id="department_id" name="department_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                        <option value="">Select a department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ (old('department_id', $letter->department_id) == $department->id) ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="date" class="block text-sm font-medium text-gray-700 required">Date</label>
                                <div class="mt-1">
                                    <input type="date" name="date" id="date" value="{{ old('date', $letter->date->format('Y-m-d')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="regarding" class="block text-sm font-medium text-gray-700 required">Regarding</label>
                                <div class="mt-1">
                                    <input type="text" name="regarding" id="regarding" value="{{ old('regarding', $letter->regarding) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="recipient" class="block text-sm font-medium text-gray-700 required">Recipient Name</label>
                                <div class="mt-1">
                                    <input type="text" name="recipient" id="recipient" value="{{ old('recipient', $letter->recipient) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="recipient_position" class="block text-sm font-medium text-gray-700">Recipient Position</label>
                                <div class="mt-1">
                                    <input type="text" name="recipient_position" id="recipient_position" value="{{ old('recipient_position', $letter->recipient_position) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="recipient_institution" class="block text-sm font-medium text-gray-700">Recipient Institution</label>
                                <div class="mt-1">
                                    <input type="text" name="recipient_institution" id="recipient_institution" value="{{ old('recipient_institution', $letter->recipient_institution) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 required">Content</label>
                                <div class="mt-1">
                                    <textarea id="content" name="content" rows="10" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>{{ old('content', $letter->content) }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Write the main content of the letter here. Use line breaks for paragraphs.</p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="attachment" class="block text-sm font-medium text-gray-700">Attachments</label>
                                <div class="mt-1">
                                    <textarea id="attachment" name="attachment" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('attachment', $letter->attachment) }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">List any attachments that accompany this letter.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <div class="flex-1">
                                <span class="text-red-500 text-sm">* Required fields</span>
                            </div>
                            <a href="{{ route('letters.show', $letter) }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Cancel
                            </a>
                            
                            @if($letter->status == 'draft')
                            <button type="submit" name="status" value="draft" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Update Draft
                            </button>
                            <button type="submit" name="status" value="pending" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit for Signing
                            </button>
                            @else
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Letter
                            </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>