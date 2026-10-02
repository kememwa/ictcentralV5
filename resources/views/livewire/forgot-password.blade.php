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
            Forgot Password?
        </h2>

        <p class="text-gray-600 mt-2">
            Enter your email address and we'll send you a reset link.
        </p>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="mb-6 flex items-start p-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">
            <svg
                class="w-5 h-5 mr-2 mt-0.5 shrink-0"
                fill="currentColor"
                viewBox="0 0 20 20"
            >
                <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l3-3z"
                    clip-rule="evenodd"
                />
            </svg>

            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form -->
    <form wire:submit.prevent="sendResetLink" class="space-y-6">

        <!-- Email -->
        <div>
            <label
                for="email"
                class="block mb-2 text-sm font-medium text-gray-700"
            >
                Your email
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
                    placeholder="name@company.com"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-3"
                    required
                />

            </div>

            @error('email')
                <p class="mt-2 text-sm text-red-600 flex items-center">
                    <svg
                        class="w-4 h-4 mr-1"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>

                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Submit -->
        <button
            type="submit"
            wire:loading.attr="disabled"
            wire:target="sendResetLink"
            class="w-full flex items-center justify-center text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center disabled:opacity-70 disabled:cursor-not-allowed transition-all duration-200 shadow-md"
        >

            <!-- Normal -->
            <span
                wire:loading.remove
                wire:target="sendResetLink"
                class="flex items-center"
            >
                Send Reset Link
            </span>

            <!-- Loading -->
            <span
                wire:loading
                wire:target="sendResetLink"
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

                Sending...
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