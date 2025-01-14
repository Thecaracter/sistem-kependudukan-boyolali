<!-- Overlay untuk mobile -->
<div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<div x-cloak :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
    class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-xl">

    <!-- Logo -->
    <div class="flex h-16 items-center justify-between px-6 bg-green-800">
        <div class="flex items-center gap-2">
            <div class="flex items-center">
                <img src="{{ asset('assets/images/logo.png') }}" class="h-12 w-14 object-contain" alt="Logo Sidespin">
            </div>
            <h1 class="text-xl font-bold text-white">SIDESPIN</h1>
        </div>
        <button @click="sidebarOpen = false"
            class="lg:hidden text-white hover:bg-green-700 p-2 rounded-lg transition-colors">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- User Info -->
    <div class="mt-6 px-6 pb-6 border-b border-primary-600/20">
        <div class="flex items-center gap-4 p-3 rounded-xl bg-primary-800/30 backdrop-blur-sm">
            @if (Auth::user()->foto)
                <img src="{{ asset('fotoProfile/' . Auth::user()->foto) }}"
                    class="h-12 w-12 rounded-lg border-2 border-primary-400/30 shadow-lg object-cover"
                    alt="Foto {{ Auth::user()->username }}">
            @else
                <div
                    class="h-12 w-12 rounded-lg border-2 border-primary-400/30 shadow-lg bg-primary-700 flex items-center justify-center">
                    <span class="text-lg font-bold text-primary-200">
                        {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                    </span>
                </div>
            @endif
            <div class="overflow-hidden">
                <p class="truncate text-sm font-semibold text-white mb-0.5">
                    {{ Auth::user()->username }}
                </p>
                <p class="truncate text-xs text-primary-200/80 bg-primary-700/30 px-2 py-0.5 rounded-full inline-block">
                    {{ Auth::user()->getRoleNames()->first() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-4">
        <div class="space-y-1.5">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all group 
               {{ request()->routeIs('dashboard') ? 'bg-primary-800/70 text-white shadow-lg border border-primary-700' : 'text-primary-100 hover:bg-primary-800/40 hover:text-white' }}">
                <svg class="h-5 w-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- User Management -->
            @canany(['view-users', 'view-roles'])
                <div x-data="{ open: {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-medium 
                    transition-all text-primary-100 hover:bg-primary-800/40 hover:text-white group
                    {{ request()->routeIs('users.*') || request()->routeIs('roles.*') ? 'bg-primary-800/50' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="font-medium">Manajemen User</span>
                        </div>
                        <svg :class="{ 'rotate-90': open }" class="h-5 w-5 transform transition-transform duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-1.5 space-y-1 px-3">
                        @can('view-users')
                            <a href="{{ route('users.index') }}"
                                class="block rounded-lg px-4 py-2.5 text-sm text-primary-100 hover:bg-primary-800/40 hover:text-white
                            transition-all hover:pl-5 font-medium {{ request()->routeIs('users.*') ? 'bg-primary-800/40 text-white' : '' }}">
                                Data User
                            </a>
                        @endcan

                        @can('view-roles')
                            <a href="{{ route('roles.index') }}"
                                class="block rounded-lg px-4 py-2.5 text-sm text-primary-100 hover:bg-primary-800/40 hover:text-white
                            transition-all hover:pl-5 font-medium {{ request()->routeIs('roles.*') ? 'bg-primary-800/40 text-white' : '' }}">
                                Data Role
                            </a>
                        @endcan
                    </div>
                </div>
            @endcanany

            <!-- Kartu Keluarga -->
            @can('view-kartu-keluarga')
                <div x-data="{ open: {{ request()->routeIs('kartu-keluarga.*') ? 'true' : 'false' }} }" class="relative">
                    <button @click="open = !open"
                        class="flex w-full items-center justify-between rounded-xl px-4 py-3 text-sm font-medium 
                 transition-all text-primary-100 hover:bg-primary-800/40 hover:text-white group
                 {{ request()->routeIs('kartu-keluarga.*') ? 'bg-primary-800/50' : '' }}">
                        <div class="flex items-center gap-3">
                            <svg class="h-5 w-5 transition-transform group-hover:scale-110" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium">Data Kartu Keluarga</span>
                        </div>
                        <svg :class="{ 'rotate-90': open }" class="h-5 w-5 transform transition-transform duration-200"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0" class="mt-1.5 space-y-1 px-3">
                        <a href="{{ route('kartu-keluarga.index') }}"
                            class="block rounded-lg px-4 py-2.5 text-sm text-primary-100 hover:bg-primary-800/40 hover:text-white
                     transition-all hover:pl-5 font-medium {{ request()->routeIs('kartu-keluarga.index') ? 'bg-primary-800/40 text-white' : '' }}">
                            Data KK
                        </a>
                    </div>
                </div>
            @endcan

            <!-- Verifikasi Penduduk -->
            @can('verify-documents')
                <a href="{{ route('verifikasi.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all group 
           {{ request()->routeIs('verifikasi.*') ? 'bg-primary-800/70 text-white shadow-lg border border-primary-700' : 'text-primary-100 hover:bg-primary-800/40 hover:text-white' }}">
                    <svg class="h-5 w-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-medium">Verifikasi Penduduk</span>
                </a>
            @endcan\
            <!-- QR Scanner Menu - Add this after Kartu Keluarga and before Verifikasi Penduduk -->
            @can('view-identitas-rumah')
                <a href="{{ route('qr-scanner.index') }}"
                    class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all group 
    {{ request()->routeIs('qr-scanner.*') ? 'bg-primary-800/70 text-white shadow-lg border border-primary-700' : 'text-primary-100 hover:bg-primary-800/40 hover:text-white' }}">
                    <svg class="h-5 w-5 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                    </svg>
                    <span class="font-medium">Scan QR</span>
                </a>
            @endcan
        </div>
    </nav>
</div>
