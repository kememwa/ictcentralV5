<div>
    
    <div class="w-full max-w-md p-6 bg-white rounded-2xl shadow-xl border border-gray-100">

        <!-- Header -->
        <div class="text-center mb-8">

            <div class="flex justify-center items-center mb-4">
                <img
                    src="{{ asset('images/kimfay.png') }}"
                    class="h-12 mr-3"
                    alt="Kim-Fay Logo"
                />

                <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                    ICT Central
                </span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900">
                Reset Password
            </h2>

            <p class="text-gray-600 mt-2">
                Create a new password for your account.
            </p>

        </div>

        <!-- Reset Password Form -->
        <form
            wire:submit.prevent="resetPassword"
            class="space-y-6"
        >

            <!-- Email -->
            <div>
                <label
                    for="email"
                    class="block mb-2 text-sm font-medium text-gray-700"
                >
                    Email Address
                </label>

                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>

                    <input
                        type="email"
                        id="email"
                        wire:model="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3"
                        readonly
                    />

                </div>

                @error('email')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror
            </div>


            <!-- New Password -->
            <div>

                <label
                    for="password"
                    class="block mb-2 text-sm font-medium text-gray-700"
                >
                    New Password
                </label>

                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>

                    </div>

                    <input
                        type="password"
                        id="password"
                        wire:model="password"
                        placeholder="Enter your new password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3"
                        required
                    />

                </div>

                @error('password')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            <!-- Confirm Password -->
            <div>

                <label
                    for="password_confirmation"
                    class="block mb-2 text-sm font-medium text-gray-700"
                >
                    Confirm New Password
                </label>

                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">

                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                            />
                        </svg>

                    </div>

                    <input
                        type="password"
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        placeholder="Confirm your new password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                            focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3"
                        required
                    />

                </div>

            </div>


            <!-- Reset Button -->
            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="resetPassword"
                class="w-full flex items-center justify-center text-white
                    bg-gradient-to-r from-blue-600 to-indigo-600
                    hover:from-blue-700 hover:to-indigo-700
                    focus:ring-4 focus:outline-none focus:ring-blue-300
                    font-medium rounded-lg text-sm px-5 py-3
                    disabled:opacity-70 disabled:cursor-not-allowed
                    transition-all duration-200 shadow-md"
            >

                <!-- Normal -->
                <span
                    wire:loading.remove
                    wire:target="resetPassword"
                    class="flex items-center"
                >
                    Reset Password
                </span>

                <!-- Loading -->
                <span
                    wire:loading
                    wire:target="resetPassword"
                    class="flex items-center"
                >

                    <svg
                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        />

                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                        />
                    </svg>

                    Resetting...

                </span>

            </button>

        </form>


        <!-- Back to Login -->
        <div class="text-center mt-6">

            <a
                wire:navigate
                href="{{ route('login') }}"
                class="text-sm text-gray-600 hover:text-blue-600 transition-colors"
            >
                ← Back to Login
            </a>

        </div>

    </div>
    
</div>
