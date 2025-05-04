@extends('admin.layout')

@section('title', 'Edit Department')

@section('header', 'Edit Department')

@section('content')
<form action="{{ route('admin.departments.update', $department) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="space-y-6">
        <!-- Basic Info -->
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Department Information</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Basic information about the department and its leadership.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Department Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description', $department->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-span-6">
                            <label for="head_id" class="block text-sm font-medium text-gray-700">Department Head</label>
                            <select id="head_id" name="head_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">No Department Head</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('head_id', $department->head_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('head_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Stats -->
        <div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Department Statistics</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Information about department members and activities.
                    </p>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <dl class="grid grid-cols-1 gap-y-6 sm:grid-cols-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Total Members</dt>
                            <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $department->users_count }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created On</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $department->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $department->updated_at->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end">
            <a href="{{ route('admin.departments.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Update Department
            </button>
        </div>
    </div>
</form>
@endsection