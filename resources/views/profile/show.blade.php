<!-- resources/views/profile/show.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>

            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Personal Information</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Update your personal information and account settings.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if($user->profile_photo_path)
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                        @else
                        <div class="h-16 w-16 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-500 text-xl font-medium">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="border-t border-gray-200">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <!-- Name -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIM -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="nim" class="block text-sm font-medium text-gray-700">Student ID (NIM)</label>
                                    <input type="text" name="nim" id="nim" value="{{ old('nim', $user->nim) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('nim')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-span-6">
                                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                    <textarea name="address" id="address" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('address', $user->address) }}</textarea>
                                    @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Profile Photo -->
                                <div class="col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Profile Photo</label>
                                    <div class="mt-1 flex items-center">
                                        @if($user->profile_photo_path)
                                        <div class="mr-3">
                                            <img class="h-12 w-12 rounded-full object-cover" src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}">
                                        </div>
                                        @else
                                        <div class="mr-3 h-12 w-12 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-500 text-lg font-medium">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        @endif
                                        <!-- Continuing resources/views/profile/show.blade.php -->
                                        <div class="flex-grow">
                                            <input type="file" name="profile_photo" id="profile_photo" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300">
                                            <p class="mt-1 text-xs text-gray-500">JPG, PNG, or GIF up to 2MB</p>
                                        </div>
                                    </div>
                                    @error('profile_photo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="px-4 py-5 bg-gray-50 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900">Change Password</h3>
                            <p class="mt-1 text-sm text-gray-500">Leave blank if you don't want to change your password</p>
                            
                            <div class="mt-6 grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                    <input type="password" name="password" id="password" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>

                        <!-- Notification Preferences -->
                        <div class="px-4 py-5 sm:p-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Notification Preferences</h3>
                            <p class="mt-1 text-sm text-gray-500">Choose how you want to receive notifications</p>
                            
                            <div class="mt-6 space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="notification_preferences[email]" name="notification_preferences[]" type="checkbox" value="email" 
                                            {{ isset($user->notification_preferences) && in_array('email', $user->notification_preferences) ? 'checked' : '' }}
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="notification_preferences[email]" class="font-medium text-gray-700">Email Notifications</label>
                                        <p class="text-gray-500">Receive email notifications about events, announcements, and updates.</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="notification_preferences[web]" name="notification_preferences[]" type="checkbox" value="web"
                                            {{ isset($user->notification_preferences) && in_array('web', $user->notification_preferences) ? 'checked' : '' }}
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="notification_preferences[web]" class="font-medium text-gray-700">Web Notifications</label>
                                        <p class="text-gray-500">Receive on-site notifications when logged in.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information -->
            <div class="mt-10 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h2 class="text-lg font-medium text-gray-900">Account Information</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Information about your membership and account status.
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Department</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->department ? $user->department->name : 'Not assigned' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Position</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->position ?? 'Not assigned' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->join_date ? $user->join_date->format('F j, Y') : ($user->created_at ? $user->created_at->format('F j, Y') : 'N/A') }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Account Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Signature Authority</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->signature_authority ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $user->signature_authority ? 'Authorized' : 'Not Authorized' }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>