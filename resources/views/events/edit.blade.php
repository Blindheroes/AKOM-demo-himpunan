<!-- resources/views/events/edit.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">Edit Event: {{ $event->title }}</h1>
                <a href="{{ route('events.show', $event) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                    </svg>
                    Back
                </a>
            </div>

            <!-- Form Card -->
            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Error Display -->
                    @if($errors->any())
                    <div class="mb-6 bg-red-50 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <!-- Title -->
                        <div class="sm:col-span-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <div class="mt-1">
                                <input type="text" name="title" id="title" value="{{ old('title', $event->title) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <div class="mt-1">
                                <textarea name="description" id="description" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('description', $event->description) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Brief description of the event. This will be displayed in the event card.</p>
                        </div>

                        <!-- Content -->
                        <div class="sm:col-span-6">
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <div class="mt-1">
                                <textarea name="content" id="content" rows="6" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old('content', $event->content) }}</textarea>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Detailed information about the event.</p>
                        </div>

                        <!-- Start Date -->
                        <div class="sm:col-span-3">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date and Time</label>
                            <div class="mt-1">
                                <input type="datetime-local" name="start_date" id="start_date" value="{{ old('start_date', $event->start_date->format('Y-m-d\TH:i')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- End Date -->
                        <div class="sm:col-span-3">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date and Time</label>
                            <div class="mt-1">
                                <input type="datetime-local" name="end_date" id="end_date" value="{{ old('end_date', $event->end_date->format('Y-m-d\TH:i')) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="sm:col-span-3">
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <div class="mt-1">
                                <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="sm:col-span-3">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <div class="mt-1">
                                <select id="department_id" name="department_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $event->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Organizer -->
                        <div class="sm:col-span-3">
                            <label for="organizer_id" class="block text-sm font-medium text-gray-700">Organizer</label>
                            <div class="mt-1">
                                <input type="text" name="organizer_id" id="organizer_id" value="{{ old('organizer_id', $event->organizer_id) }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">User ID of the organizer.</p>
                        </div>

                        <!-- Maximum Participants -->
                        <div class="sm:col-span-3">
                            <label for="max_participants" class="block text-sm font-medium text-gray-700">Maximum Participants</label>
                            <div class="mt-1">
                                <input type="number" name="max_participants" id="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="0" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Leave empty if there's no limit.</p>
                        </div>

                        <!-- Registration Deadline -->
                        <div class="sm:col-span-3">
                            <label for="registration_deadline" class="block text-sm font-medium text-gray-700">Registration Deadline</label>
                            <div class="mt-1">
                                <input type="datetime-local" name="registration_deadline" id="registration_deadline" value="{{ old('registration_deadline', $event->registration_deadline ? $event->registration_deadline->format('Y-m-d\TH:i') : '') }}" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Leave empty to allow registration until event starts.</p>
                        </div>

                        <!-- Budget -->
                        <div class="sm:col-span-3">
                            <label for="budget" class="block text-sm font-medium text-gray-700">Budget (IDR)</label>
                            <div class="mt-1">
                                <input type="number" name="budget" id="budget" value="{{ old('budget', $event->budget) }}" min="0" step="1000" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Estimated budget for the event.</p>
                        </div>

                        <!-- Featured Event Toggle -->
                        <div class="sm:col-span-3">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="is_featured" name="is_featured" type="checkbox" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }} class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="is_featured" class="font-medium text-gray-700">Featured Event</label>
                                    <p class="text-gray-500">Mark this event as featured to display it prominently on the website.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Current Event Image -->
                        @if($event->image_path)
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700">Current Image</label>
                            <div class="mt-2">
                                <img src="{{ Storage::url($event->image_path) }}" alt="{{ $event->title }}" class="h-64 w-full object-cover rounded-md">
                            </div>
                        </div>
                        @endif

                        <!-- Image Upload -->
                        <div class="sm:col-span-6">
                            <label for="image" class="block text-sm font-medium text-gray-700">Update Event Image</label>
                            <div class="mt-1">
                                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Upload a new image for the event (max 2MB). Leave empty to keep the current image.</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="sm:col-span-6">
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Update Event
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>