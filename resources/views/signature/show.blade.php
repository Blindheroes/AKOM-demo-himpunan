<!-- resources/views/signature/show.blade.php -->
<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-semibold text-gray-900">Digital Signature Management</h1>

            <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h2 class="text-lg font-medium text-gray-900">Your Digital Signature</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Your digital signature is used for signing letters and documents. You can upload, update, or deactivate your signature here.
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    @if($signature)
                    <div class="flex flex-col md:flex-row md:items-center">
                        <div class="flex-shrink-0 border border-gray-200 rounded-lg p-4 bg-gray-50 mb-4 md:mb-0 md:mr-6">
                            <img src="{{ Storage::url($signature->signature_path) }}" alt="Your Signature" class="h-24 object-contain">
                        </div>
                        <div>
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">Status:</span> 
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $signature->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $signature->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-700 mt-1">
                                <span class="font-medium">Last Updated:</span> {{ $signature->updated_at->format('F j, Y g:i A') }}
                            </p>
                            
                            <div class="mt-4 flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0">
                                <form action="{{ route('signature.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="is_active" value="{{ $signature->is_active ? '0' : '1' }}">
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ $signature->is_active ? 'Deactivate' : 'Activate' }} Signature
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center justify-center h-32 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 text-center">You haven't uploaded a signature yet.</p>
                    </div>
                    @endif
                </div>

                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ $signature ? 'Update' : 'Upload' }} Signature</h3>
                    <form action="{{ route('signature.store') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div>
                            <label for="signature_image" class="block text-sm font-medium text-gray-700">
                                Signature Image
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="signature_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="signature_image" name="signature_image" type="file" class="sr-only" accept="image/*" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB (transparent background recommended)
                                    </p>
                                </div>
                            </div>
                            @error('signature_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ $signature ? 'Update' : 'Upload' }} Signature
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-10 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Signature Usage Guide</h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Information about how to use your digital signature effectively.
                        </p>
                    </div>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <div class="prose max-w-none">
                        <h3>Best Practices for Digital Signatures</h3>
                        <p>To ensure security and proper usage of your digital signature:</p>
                        <ul>
                            <li>Create a clear signature with a transparent background for best results.</li>
                            <li>Deactivate your signature when not in use for an extended period.</li>
                            <li>Review all documents carefully before signing.</li>
                            <li>Your signature can only be used by you - never share your account credentials.</li>
                            <li>If you suspect unauthorized use of your signature, deactivate it immediately and contact the administrators.</li>
                        </ul>
                        
                        <h3>Where Your Signature Will Be Used</h3>
                        <p>Your digital signature may be used in the following documents:</p>
                        <ul>
                            <li>Official letters</li>
                            <li>Event approvals</li>
                            <li>Financial documents</li>
                            <li>Other official organization documents</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>