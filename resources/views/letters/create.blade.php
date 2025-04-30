<!-- resources/views/letters/create.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back button and title -->
            <div class="flex items-center">
                <a href="{{ route('letters.index') }}" class="inline-flex items-center mr-3 px-2 py-1 text-sm text-gray-600 hover:text-gray-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Create New Letter</h1>
            </div>

            <!-- Form -->
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="{{ route('letters.store') }}" method="POST">
                        @csrf

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

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 required">Title</label>
                                <div class="mt-1">
                                    <input type="text" name="title" id="title" value="{{ old('title') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="template_id" class="block text-sm font-medium text-gray-700 required">Template</label>
                                <div class="mt-1">
                                    <select id="template_id" name="template_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                        <option value="">Select a template</option>
                                        @foreach($templates as $template)
                                            <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>{{ $template->name }}</option>
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
                                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="date" class="block text-sm font-medium text-gray-700 required">Date</label>
                                <div class="mt-1">
                                    <input type="date" name="date" id="date" value="{{ old('date', now()->format('Y-m-d')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="regarding" class="block text-sm font-medium text-gray-700 required">Regarding</label>
                                <div class="mt-1">
                                    <input type="text" name="regarding" id="regarding" value="{{ old('regarding') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="recipient" class="block text-sm font-medium text-gray-700 required">Recipient Name</label>
                                <div class="mt-1">
                                    <input type="text" name="recipient" id="recipient" value="{{ old('recipient') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="recipient_position" class="block text-sm font-medium text-gray-700">Recipient Position</label>
                                <div class="mt-1">
                                    <input type="text" name="recipient_position" id="recipient_position" value="{{ old('recipient_position') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="recipient_institution" class="block text-sm font-medium text-gray-700">Recipient Institution</label>
                                <div class="mt-1">
                                    <input type="text" name="recipient_institution" id="recipient_institution" value="{{ old('recipient_institution') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 required">Content</label>
                                <div class="mt-1">
                                    <textarea id="content" name="content" rows="10" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>{{ old('content') }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Write the main content of the letter here. Use line breaks for paragraphs.</p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="attachment" class="block text-sm font-medium text-gray-700">Attachments</label>
                                <div class="mt-1">
                                    <textarea id="attachment" name="attachment" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('attachment') }}</textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">List any attachments that accompany this letter.</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end">
                            <div class="flex-1">
                                <span class="text-red-500 text-sm">* Required fields</span>
                            </div>
                            <button type="submit" name="status" value="draft" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Save as Draft
                            </button>
                            <button type="submit" name="status" value="pending" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Submit for Signing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>