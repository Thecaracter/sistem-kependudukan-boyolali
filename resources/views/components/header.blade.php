<header class="sticky top-0 z-30 bg-white shadow-sm border-b border-gray-100">
    <div class="flex h-16 items-center justify-between px-4 sm:px-6 lg:px-8">
        <!-- Left side -->
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = true"
                class="lg:hidden rounded-lg p-2 text-gray-500 hover:bg-gray-100 transition-colors">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm text-gray-500">Hari ini:</span>
                <span class="text-sm font-medium text-gray-900">
                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                </span>
            </div>
        </div>

        <!-- Right side - Profile Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center gap-3 rounded-lg py-1.5 px-2 text-sm focus:outline-none hover:bg-gray-100 transition-colors">
                <div class="flex items-center gap-3">
                    @if (Auth::user()->foto)
                        <img src="{{ asset('fotoProfile/' . Auth::user()->foto) }}"
                            class="h-8 w-8 rounded-lg border-2 border-gray-200 shadow-sm object-cover"
                            alt="Foto {{ Auth::user()->username }}">
                    @else
                        <div
                            class="h-8 w-8 rounded-lg border-2 border-gray-200 shadow-sm bg-gray-100 flex items-center justify-center">
                            <span class="text-sm font-semibold text-gray-600">
                                {{ strtoupper(substr(Auth::user()->username, 0, 2)) }}
                            </span>
                        </div>
                    @endif
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-gray-700">
                            {{ Auth::user()->username }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ Auth::user()->getRoleNames()->first() }}
                        </p>
                    </div>
                </div>
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                class="absolute right-0 mt-2 w-48 rounded-xl bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 border border-gray-100">
                <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
