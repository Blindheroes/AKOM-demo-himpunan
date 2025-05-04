@extends('admin.layout')

@section('title', 'Departments')

@section('header', 'Department Management')

@section('actions')
<a href="{{ route('admin.departments.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
    </svg>
    Add Department
</a>
@endsection

@section('content')
<div class="flex flex-col">
    <!-- Departments Table -->
    <div class="overflow-x-auto">
        <div class="align-middle inline-block min-w-full">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Head
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Members
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($departments as $department)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-full bg-gray-100">
                                        <span class="text-gray-600 font-medium text-lg">{{ substr($department->name, 0, 1) }}</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $department->name }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($department->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($department->head)
                                <div class="flex items-center">
                                    @if($department->head->profile_photo_path)
                                    <img src="{{ Storage::url($department->head->profile_photo_path) }}" alt="{{ $department->head->name }}" class="w-8 h-8 rounded-full mr-2">
                                    @else
                                    <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center mr-2">
                                        <span class="text-gray-600 font-medium">{{ substr($department->head->name, 0, 1) }}</span>
                                    </div>
                                    @endif
                                    <div class="text-sm text-gray-900">{{ $department->head->name }}</div>
                                </div>
                                @else
                                <span class="text-sm text-gray-500">No department head assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $department->users_count }}</div>
                                <div class="text-xs text-gray-500">Members</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.departments.edit', $department) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                @if($department->users_count == 0)
                                <form action="{{ route('admin.departments.destroy', $department) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this department?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No departments found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $departments->links() }}
    </div>
</div>
@endsection