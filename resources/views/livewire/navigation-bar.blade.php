<div>
    <nav
        x-data="{ showLogoutModal: false }" x-cloak
        class="fixed top-0 right-0 left-0 sm:left-64 z-20 h-16 border-b border-slate-200 bg-white/80 backdrop-blur-md"
    >
        <div class="flex h-full items-center justify-between px-4 sm:px-6">

            {{-- LEFT: Mobile menu toggle + Greeting --}}
            <div class="flex items-center gap-3">
                <button
                    @click.stop="sidebarOpen = !sidebarOpen"
                    class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 hover:text-slate-700 sm:hidden"
                    aria-label="Toggle menu"
                >

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="hidden sm:block">
                    <p class="text-xs text-slate-500">Hello?</p>
                    <p class="text-sm font-semibold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                </div>
                <p class="text-sm font-semibold text-slate-900 sm:hidden">Hi, {{ auth()->user()->name }}</p>
            </div>

            {{-- RIGHT: Logout --}}
            <button
                @click="showLogoutModal = true"
                class="inline-flex items-center gap-1.5 rounded-lg bg-red-600 px-3 py-2 text-xs font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:text-sm"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span class="hidden sm:inline">Logout</span>
            </button>
        </div>

        {{-- Logout Modal — TELEPORTED TO BODY so it centers on the WHOLE page --}}
        <template x-teleport="body">
            <div
                x-show="showLogoutModal"
                x-cloak
                @keydown.escape.window="showLogoutModal = false"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            >
                {{-- Backdrop --}}
                <div
                    x-show="showLogoutModal"
                    x-transition.opacity
                    @click="showLogoutModal = false"
                    class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
                ></div>

                {{-- Modal card --}}
                <div
                    x-show="showLogoutModal"
                    x-transition
                    class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
                >
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-slate-900">Confirm Logout</h2>
                    <p class="mt-1.5 text-sm text-slate-600">
                        Are you sure you want to log out? You'll need to sign in again to access the application.
                    </p>

                    <div class="mt-6 flex justify-end gap-2">
                        <button
                            @click="showLogoutModal = false"
                            class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50"
                        >
                            Cancel
                        </button>
                        <button
                            wire:click="logout"
                            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"
                        >
                            Yes, log out
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </nav>
</div>
