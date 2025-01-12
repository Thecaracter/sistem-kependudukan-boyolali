@extends('layouts.auth')

@section('title', 'Login - ' . config('app.name'))

@section('content')
    <div class="w-full max-w-md" x-data="{
        loading: false,
        username: '',
        password: '',
        showPassword: false,
        error: null,
        async handleLogin() {
            this.loading = true;
            this.error = null;
    
            try {
                const response = await fetch('/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({
                        username: this.username,
                        password: this.password
                    })
                });
    
                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Invalid credentials');
                }
    
                window.location.href = '/dashboard';
            } catch (err) {
                this.error = err.message;
            } finally {
                this.loading = false;
            }
        }
    }">
        <div class="bg-white shadow-xl rounded-xl px-8 pt-8 pb-8">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" class="w-35 h-20">
                </div>
                <h1 class="text-2xl font-bold text-primary-800">SIDESPIN</h1>
                <p class="text-secondary-600 mt-2">Sistem Informasi Desa Pintar</p>
            </div>

            <!-- Error Alert -->
            <div x-show="error" x-cloak
                class="bg-danger-50 border-l-4 border-danger-500 text-danger-700 p-4 rounded-md mb-6" x-text="error">
            </div>

            <form @submit.prevent="handleLogin" class="space-y-6">
                <!-- Username Input -->
                <div>
                    <label class="block text-secondary-700 text-sm font-medium mb-2">
                        Username
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" x-model="username"
                            class="pl-10 w-full rounded-lg border-secondary-300 focus:ring-primary-500 focus:border-primary-500"
                            required>
                    </div>
                </div>

                <!-- Password Input dengan Show/Hide -->
                <div>
                    <label class="block text-secondary-700 text-sm font-medium mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-secondary-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" x-model="password"
                            class="pl-10 pr-10 w-full rounded-lg border-secondary-300 focus:ring-primary-500 focus:border-primary-500"
                            required>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" @click="showPassword = !showPassword"
                                class="text-secondary-400 hover:text-secondary-600 focus:outline-none">
                                <!-- Eye Icon untuk Show -->
                                <svg x-show="!showPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <!-- Eye Slash Icon untuk Hide -->
                                <svg x-show="showPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 rounded-lg text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors duration-200 disabled:opacity-50"
                        :disabled="loading">
                        <span x-show="!loading">Masuk ke Sistem</span>
                        <div x-show="loading" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Memproses...
                        </div>
                    </button>
                </div>
            </form>

            <!-- Footer -->
            <div class="mt-8 pt-6 border-t border-secondary-200">
                <p class="text-center text-sm text-secondary-600">
                    © {{ date('Y') }} SIDESPIN. Hak Cipta Dilindungi.
                </p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
@endpush
