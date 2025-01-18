@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Profile</h1>
                <p class="mt-1 text-sm text-gray-600">Update informasi profil dan password Anda</p>
            </div>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="rounded-lg bg-green-50 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Alert Error --}}
        @if (session('error'))
            <div class="rounded-lg bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Main Form --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            {{-- Profile Info --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="p-4 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900">Informasi Profile</h2>
                    <p class="mt-1 text-sm text-gray-600">Update informasi profil Anda</p>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                        @csrf
                        @method('PATCH')

                        {{-- Username --}}
                        <div class="mb-4">
                            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" id="username"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('username') border-red-300 @enderror"
                                value="{{ old('username', $user->username) }}" required>
                            @error('username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Profile Photo --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Foto Profile</label>
                            <div class="mt-1 flex items-center gap-4">
                                <div class="relative inline-block">
                                    {{-- Current Photo or Preview --}}
                                    <div id="imagePreviewContainer">
                                        @if ($user->foto)
                                            <img src="{{ asset('fotoProfile/' . $user->foto) }}"
                                                class="h-16 w-16 rounded-lg border-2 border-gray-200 object-cover"
                                                alt="Profile Photo">
                                        @else
                                            <div id="defaultImage"
                                                class="flex h-16 w-16 items-center justify-center rounded-lg border-2 border-gray-200 bg-gray-50">
                                                <span class="text-lg font-semibold text-gray-500">
                                                    {{ strtoupper(substr($user->username, 0, 2)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <input type="file" name="foto" id="foto" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
                                    @error('foto')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG atau JPEG (Maks. 2MB)</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                <svg class="mr-2 -ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path
                                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Password Update --}}
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="p-4 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
                    <p class="mt-1 text-sm text-gray-600">Pastikan akun Anda menggunakan password yang panjang dan acak untuk
                        tetap aman.</p>

                    <form action="{{ route('profile.update-password') }}" method="POST" class="mt-6">
                        @csrf
                        @method('PATCH')

                        {{-- Current Password --}}
                        <div class="mb-4">
                            <label for="current_password" class="block text-sm font-medium text-gray-700">Password Saat
                                Ini</label>
                            <input type="password" name="current_password" id="current_password"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('current_password') border-red-300 @enderror">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- New Password --}}
                        <div class="mb-4">
                            <label for="new_password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input type="password" name="new_password" id="new_password"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm @error('new_password') border-red-300 @enderror">
                            @error('new_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                                Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                        </div>

                        <div>
                            <button type="submit"
                                class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                <svg class="mr-2 -ml-1 h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v4.59L7.3 9.24a.75.75 0 00-1.1 1.02l3.25 3.5a.75.75 0 001.1 0l3.25-3.5a.75.75 0 10-1.1-1.02l-1.95 2.1V6.75z"
                                        clip-rule="evenodd" />
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const imageInput = document.getElementById('foto');
        const imagePreviewContainer = document.getElementById('imagePreviewContainer');
        const defaultImage = document.getElementById('defaultImage');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    // Create new image preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'h-16 w-16 rounded-lg border-2 border-gray-200 object-cover';
                    img.alt = 'Preview Photo';

                    // Clear previous content
                    imagePreviewContainer.innerHTML = '';

                    // Add new image
                    imagePreviewContainer.appendChild(img);
                }

                reader.readAsDataURL(file);
            } else {
                // If no file selected, show default image/initials
                imagePreviewContainer.innerHTML = `
                    <div class="flex h-16 w-16 items-center justify-center rounded-lg border-2 border-gray-200 bg-gray-50">
                        <span class="text-lg font-semibold text-gray-500">
                            {{ strtoupper(substr($user->username, 0, 2)) }}
                        </span>
                    </div>
                `;
            }
        });
    </script>
@endpush