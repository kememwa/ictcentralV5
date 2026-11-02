@php
    // Put your images in public/images/login/ (JPG/WebP, about 1600px wide, under 300 KB each)
    $slides = [
        [
            'src'     => asset('images/login/data-center.jpg'),
            'caption' => 'Reliable systems behind every department',
            'pos'     => '50% 50%',
        ],
        [
            'src'     => asset('images/login/warehouse.jpg'),
            'caption' => 'From the factory floor to the shelf',
            'pos'     => '30% 50%',
        ],
        [
            'src'     => asset('images/login/team.jpg'),
            'caption' => 'Requests and approvals, handled faster',
            'pos'     => '50% 25%',
        ],
        [
            'src'     => asset('images/login/nairobi.jpg'),
            'caption' => 'Kim-Fay, powered by ICT',
            'pos'     => '50% 50%',
        ],
    ];
@endphp

<div class="w-full min-h-screen grid lg:grid-cols-2 bg-white">

    <!-- Left: rotating images (large screens only) -->
    <div
        class="relative hidden lg:block overflow-hidden bg-blue-900"
        x-data="{
            current: 0,
            slides: @js($slides),
            timer: null,
            init() { this.start() },
            start() { this.timer = setInterval(() => this.next(), 6000) },
            next() { this.current = (this.current + 1) % this.slides.length },
            go(i) { this.current = i; clearInterval(this.timer); this.start() }
        }"
    >
        <!-- Images (crossfade) -->
        <template x-for="(slide, i) in slides" :key="i">
            <img
                :src="slide.src"
                alt=""
                x-show="current === i"
                x-transition:enter="transition-opacity duration-1000"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity duration-1000"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                :style="'object-position: ' + slide.pos"
                class="absolute inset-0 w-full h-full object-cover"
            />
        </template>

        <!-- Dark overlay so text stays readable -->
        <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 via-blue-900/30 to-blue-900/40"></div>

        <!-- Logo -->
        <div class="absolute top-10 left-12 xl:left-16">
            <div class="inline-flex p-2 bg-white rounded-lg">
                <img src="{{ asset('images/kimfay.png') }}" class="h-9" alt="Kim-Fay logo" />
            </div>
        </div>

        <!-- Caption + dots -->
        <div class="absolute bottom-12 left-12 right-12 xl:left-16 xl:right-16 text-white">
            <p class="text-3xl font-bold leading-snug max-w-md" x-text="slides[current].caption"></p>

            <div class="flex gap-2 mt-6">
                <template x-for="(slide, i) in slides" :key="i">
                    <button
                        type="button"
                        @click="go(i)"
                        :aria-label="'Show image ' + (i + 1)"
                        class="h-1.5 rounded-full transition-all duration-300"
                        :class="current === i ? 'w-8 bg-white' : 'w-4 bg-white/40 hover:bg-white/60'"
                    ></button>
                </template>
            </div>
        </div>
    </div>

    <!-- Right: form -->
    <div class="flex items-center px-6 py-10 sm:px-12 lg:px-16">
        <div class="w-full max-w-md mx-auto">

            <!-- Logo (mobile only, the image panel shows it on large screens) -->
            <img src="{{ asset('images/kimfay.png') }}" class="h-12 mb-8 lg:hidden" alt="Kim-Fay logo" />

            <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
            <p class="mt-1 mb-8 text-sm text-gray-500">ICT Central</p>

            <form class="space-y-6" wire:submit.prevent="login">

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input
                        type="email"
                        wire:model="email"
                        id="email"
                        autocomplete="email"
                        placeholder="Enter your email"
                        class="block w-full p-3 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg placeholder-gray-400 focus:ring-blue-600 focus:border-blue-600"
                        required
                    />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <div class="relative">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            name="password"
                            wire:model="password"
                            id="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="block w-full p-3 pr-12 text-sm text-gray-900 bg-gray-50 border border-gray-300 rounded-lg placeholder-gray-400 focus:ring-blue-600 focus:border-blue-600"
                            required
                        />

                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                        >
                            {{-- Eye open --}}
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>

                            {{-- Eye closed --}}
                            <svg x-show="showPassword" x-cloak xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-2.917M6.228 6.228A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.958 9.958 0 01-4.132 5.411M6.228 6.228L3 3m3.228 3.228l3.65 3.65m5.244 5.244L21 21M9.879 9.879a3 3 0 104.242 4.242" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Remember me & Forgot password -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            type="checkbox"
                            wire:model="remember"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-600"
                        />
                        <label for="remember" class="ms-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <a wire:navigate href="{{ route('forgot-password') }}"
                       class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit -->
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="login"
                    class="w-full flex items-center justify-center text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-200 rounded-lg px-5 py-3 transition-colors disabled:opacity-70 disabled:cursor-not-allowed">

                    <span wire:loading.remove wire:target="login">Sign in to your account</span>

                    <span wire:loading wire:target="login" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Signing in...
                    </span>
                </button>
            </form>
        </div>
    </div>

</div>