<div class="space-y-6">

 {{-- ===== Page Header ===== --}}
    <x-page-header
        title="Edit User Profile"
        subtitle="Manage your profile information, profile picture, signature and password.">
    </x-page-header>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <!-- Profile Header -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center gap-4">

                <!-- Profile Picture -->
                <div class="relative">
                    <img
                        src="{{ $existingProfilePicture ? Storage::url($existingProfilePicture) : asset('images/default-avatar.png') }}"
                        alt="Profile Picture"
                        class="w-20 h-20 rounded-full object-cover border-4 border-white shadow"
                    >

                    <!-- Upload Button -->
                    <label
                        for="profile_picture"
                        class="absolute bottom-0 right-0 flex items-center justify-center
                               w-7 h-7 bg-blue-600 text-white rounded-full cursor-pointer
                               hover:bg-blue-700 transition"
                        title="Change profile picture"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-4 h-4"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 4v16m8-8H4"/>
                        </svg>
                    </label>

                    <input
                        type="file"
                        id="profile_picture"
                        class="hidden"
                        accept="image/*"
                    >
                </div>

            </div>
        </div>


        <!-- Basic Information -->
        <div class="p-6">

            <div class="mb-5">
                <h3 class="text-lg font-semibold text-gray-800">
                    Basic Information
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Basic account information can only be changed by administrators.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name
                    </label>

                    <input
                        type="text"
                        value="{{ auth()->user()->name }}"
                        disabled
                        class="w-full rounded-lg border-gray-300 bg-gray-100
                               text-gray-500 cursor-not-allowed
                               focus:ring-0 focus:border-gray-300"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>

                    <input
                        type="email"
                        value="{{ auth()->user()->email }}"
                        disabled
                        class="w-full rounded-lg border-gray-300 bg-gray-100
                               text-gray-500 cursor-not-allowed
                               focus:ring-0 focus:border-gray-300"
                    >
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        value="{{ auth()->user()->phone ?? '' }}"
                        disabled
                        class="w-full rounded-lg border-gray-300 bg-gray-100
                               text-gray-500 cursor-not-allowed
                               focus:ring-0 focus:border-gray-300"
                    >
                </div>

                <!-- Department -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Department
                    </label>

                    <input
                        type="text"
                        value="{{ auth()->user()->department->name ?? '' }}"
                        disabled
                        class="w-full rounded-lg border-gray-300 bg-gray-100
                               text-gray-500 cursor-not-allowed
                               focus:ring-0 focus:border-gray-300"
                    >
                </div>

            </div>

            <!-- Admin Notice -->
            <div class="mt-6 flex items-start gap-3 p-4 rounded-lg bg-yellow-50
                        border border-yellow-200">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-5 h-5 text-yellow-600 mt-0.5"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 9v2m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>

                <div>
                    <p class="text-sm font-medium text-yellow-800">
                        Administrator access required
                    </p>

                    <p class="text-sm text-yellow-700 mt-1">
                        Contact an administrator if you need to change your
                        name, email, phone number or other account information.
                    </p>
                </div>

            </div>

        </div>


        <!-- Personal Profile -->
        <div class="border-t border-gray-200 p-6">

            <div class="mb-5">
                <h3 class="text-lg font-semibold text-gray-800">
                    Personal Profile
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    You can manage your profile picture and digital signature.
                </p>
            </div>

            <!-- ============ PROFILE PICTURE ============ -->
            <div
                x-data="imageCropper({ aspectRatio: 1, target: 'profile', wireSaveMethod: 'saveProfilePicture' })"
                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg mb-4"
            >
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                        <img
                            src="{{ $existingProfilePicture ? Storage::url($existingProfilePicture) : asset('images/default-avatar.png') }}"
                            alt="Profile Picture"
                            class="w-full h-full object-cover"
                            wire:key="profile-pic-{{ $existingProfilePicture }}"
                        >
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Profile Picture</h4>
                        <p class="text-xs text-gray-500 mt-1">JPG, PNG or WEBP. Maximum size 2MB.</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <label class="px-4 py-2 text-sm font-medium text-blue-600 border border-blue-600 rounded-lg cursor-pointer hover:bg-blue-50 transition">
                        {{ $existingProfilePicture ? 'Change' : 'Upload' }}
                        <input type="file" class="hidden" accept="image/png,image/jpeg,image/webp" x-on:change="handleFileSelect($event)">
                    </label>

                    @if ($existingProfilePicture)
                        <button type="button" wire:click="deleteProfilePicture" wire:confirm="Remove your profile picture?"
                            class="px-3 py-2 text-sm text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                            Remove
                        </button>
                    @endif
                </div>

                <!-- Cropper Modal -->
                <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg" @click.outside="closeModal()">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-800">Crop Profile Picture</h3>
                            <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
                        </div>

                        <div class="p-4">
                            <div class="max-h-96 overflow-hidden">
                                <img x-ref="cropperImage" :src="imageSrc" style="max-width:100%; display:block;">
                            </div>
                        </div>

                        <div class="p-4 border-t border-gray-200 flex justify-between items-center">
                            <div class="flex gap-2">
                                <button type="button" @click="cropper.rotate(-90)" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50">⟲ Rotate</button>
                                <button type="button" @click="cropper.rotate(90)" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50">⟳ Rotate</button>
                                <button type="button" @click="cropper.zoom(0.1)" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50">+</button>
                                <button type="button" @click="cropper.zoom(-0.1)" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50">−</button>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="closeModal()" class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="button" @click="crop()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ SIGNATURE ============ -->
            <div
                x-data="imageCropper({ aspectRatio: NaN, target: 'signature', wireSaveMethod: 'saveSignature' })"
                class="p-4 border border-gray-200 rounded-lg"
            >
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">Digital Signature</h4>
                        <p class="text-xs text-gray-500 mt-1">Crop tightly around just the signature. Saved as a transparent PNG.</p>
                    </div>
                </div>

                <div class="flex items-center justify-center h-32 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 mb-4">
                    @if ($existingSignature)
                        <img src="{{ Storage::url($existingSignature) }}" alt="Signature" class="max-h-full max-w-full object-contain" wire:key="sig-{{ $existingSignature }}">
                    @else
                        <span class="text-sm text-gray-400">No signature uploaded</span>
                    @endif
                </div>

                <div class="flex items-center gap-3">
                    <label class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        Upload Signature
                        <input type="file" class="hidden" accept="image/png,image/jpeg" x-on:change="handleFileSelect($event)">
                    </label>
                    <span class="text-xs text-gray-500">PNG recommended, transparent background works best</span>

                    @if ($existingSignature)
                        <button type="button" wire:click="deleteSignature" wire:confirm="Remove your signature?"
                            class="px-3 py-2 text-sm text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition">
                            Remove
                        </button>
                    @endif
                </div>

                <!-- Same modal structure as above, reused via the x-data scope -->
                <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
                        <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="font-semibold text-gray-800">Crop Signature</h3>
                            <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        <div class="p-4">
                            <div class="max-h-96 overflow-hidden">
                                <img x-ref="cropperImage" :src="imageSrc" style="max-width:100%; display:block;">
                            </div>
                        </div>
                        <div class="p-4 border-t border-gray-200 flex justify-between items-center">
                            <div class="flex gap-2">
                                <button type="button" @click="cropper.zoom(0.1)" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50">+</button>
                                <button type="button" @click="cropper.zoom(-0.1)" class="px-3 py-1.5 text-sm border rounded-lg hover:bg-gray-50">−</button>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" @click="closeModal()" class="px-4 py-2 text-sm border rounded-lg hover:bg-gray-50">Cancel</button>
                                <button type="button" @click="crop()" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <!-- Password -->
        <div class="border-t border-gray-200 p-6">

            <div class="mb-5">
                <h3 class="text-lg font-semibold text-gray-800">
                    Password & Security
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Keep your account secure by regularly updating your password.
                </p>
            </div>

            <div class="flex items-center justify-between p-4
                        rounded-lg border border-gray-200">

                <div class="flex items-center gap-4">

                    <div class="w-10 h-10 rounded-lg bg-gray-100
                                flex items-center justify-center">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-gray-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"/>
                        </svg>

                    </div>

                    <div>
                        <h4 class="text-sm font-semibold text-gray-800">
                            Password
                        </h4>

                        <p class="text-xs text-gray-500 mt-1">
                            Change your account password.
                        </p>
                    </div>

                </div>

                <button
                    type="button"
                    @click="$dispatch('open-password-modal')"
                    class="px-4 py-2 text-sm font-medium text-white
                        bg-gray-800 rounded-lg hover:bg-gray-900 transition"
                >
                    Change Password
                </button>

            </div>

        </div>


        <!-- Bottom Quote -->
        <div class="border-t border-gray-200 px-6 py-5 bg-gray-50">

            <p class="text-center text-sm italic text-gray-500">
                "Always remember that you are absolutely unique.
                Just like everyone else."
            </p>

            <p class="text-center text-xs text-gray-400 mt-1">
                — Margaret Mead
            </p>

        </div>

    </div>


    <!-- Change Password Modal -->
    <div
        x-data="{ open: false }"
        x-init="
            $watch('open', value => {
                document.body.style.overflow = value ? 'hidden' : '';
            })
        "
        x-on:open-password-modal.window="
            $wire.openPasswordModal();
            open = true;
        "
        x-on:close-password-modal.window="
            open = false;
        "
        x-show="open"
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center
            justify-center bg-black/50 px-4"
    >
        <div
            @click.outside="open = false"
            class="w-full max-w-md bg-white rounded-xl shadow-2xl"
        >

            <!-- Header -->
            <div class="flex items-center justify-between
                        px-6 py-4 border-b border-gray-200">

                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Change Password
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Update your account password.
                    </p>
                </div>

                <button
                    type="button"
                    @click="open = false"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>


            <!-- Form -->
            <form wire:submit="updatePassword">
                <div class="p-6 space-y-5">

                    <!-- Current Password -->
                    <div x-data="{ show: false }">

                        <label
                            for="current_password"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Current Password
                        </label>

                        <div class="relative">

                            <input
                                :type="show ? 'text' : 'password'"
                                id="current_password"
                                wire:model="currentPassword"
                                autocomplete="current-password"
                                class="w-full h-11 pr-12 border-gray-300
                                    rounded-lg
                                    focus:border-blue-500
                                    focus:ring-blue-500"
                                placeholder="Enter current password"
                            >

                            <!-- Eye Toggle -->
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0
                                    flex items-center px-3
                                    text-gray-400 hover:text-gray-600"
                                :title="show ? 'Hide password' : 'Show password'"
                            >

                                <!-- Show Password -->
                                <svg
                                    x-show="!show"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        -1.274 4.057-5.064 7-9.542 7
                                        -4.477 0-8.268-2.943-9.542-7z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0
                                        3 3 0 016 0z"
                                    />
                                </svg>

                                <!-- Hide Password -->
                                <svg
                                    x-show="show"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 3l18 18"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.584 10.587a2 2 0 002.829 2.829"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.88 5.09A9.96 9.96 0 0112 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        a10.05 10.05 0 01-4.132 5.411"
                                    />
                                </svg>

                            </button>

                        </div>

                        @error('currentPassword')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- New Password -->
                    <div x-data="{ show: false }">

                        <label
                            for="new_password"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            New Password
                        </label>

                        <div class="relative">

                            <input
                                :type="show ? 'text' : 'password'"
                                id="new_password"
                                wire:model="newPassword"
                                autocomplete="new-password"
                                class="w-full h-11 pr-12 border-gray-300
                                    rounded-lg
                                    focus:border-blue-500
                                    focus:ring-blue-500"
                                placeholder="Enter new password"
                            >

                            <!-- Eye Toggle -->
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0
                                    flex items-center px-3
                                    text-gray-400 hover:text-gray-600"
                                :title="show ? 'Hide password' : 'Show password'"
                            >

                                <!-- Show Password -->
                                <svg
                                    x-show="!show"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        -1.274 4.057-5.064 7-9.542 7
                                        -4.477 0-8.268-2.943-9.542-7z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0
                                        3 3 0 016 0z"
                                    />
                                </svg>

                                <!-- Hide Password -->
                                <svg
                                    x-show="show"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 3l18 18"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.584 10.587a2 2 0 002.829 2.829"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.88 5.09A9.96 9.96 0 0112 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        a10.05 10.05 0 01-4.132 5.411"
                                    />
                                </svg>

                            </button>

                        </div>

                        @error('newPassword')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- Confirm Password -->
                    <div x-data="{ show: false }">

                        <label
                            for="new_password_confirmation"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Confirm New Password
                        </label>

                        <div class="relative">

                            <input
                                :type="show ? 'text' : 'password'"
                                id="new_password_confirmation"
                                wire:model="newPassword_confirmation"
                                autocomplete="new-password"
                                class="w-full h-11 pr-12 border-gray-300
                                    rounded-lg
                                    focus:border-blue-500
                                    focus:ring-blue-500"
                                placeholder="Confirm new password"
                            >

                            <!-- Eye Toggle -->
                            <button
                                type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0
                                    flex items-center px-3
                                    text-gray-400 hover:text-gray-600"
                                :title="show ? 'Hide password' : 'Show password'"
                            >

                                <!-- Show Password -->
                                <svg
                                    x-show="!show"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        -1.274 4.057-5.064 7-9.542 7
                                        -4.477 0-8.268-2.943-9.542-7z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0
                                        3 3 0 016 0z"
                                    />
                                </svg>

                                <!-- Hide Password -->
                                <svg
                                    x-show="show"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-5 h-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 3l18 18"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.584 10.587a2 2 0 002.829 2.829"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9.88 5.09A9.96 9.96 0 0112 5
                                        c4.478 0 8.268 2.943 9.542 7
                                        a10.05 10.05 0 01-4.132 5.411"
                                    />
                                </svg>

                            </button>

                        </div>

                        @error('newPasswordConfirmation')
                            <p class="mt-1 text-xs text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <!-- Password Requirements -->
                    <div class="p-3 rounded-lg bg-gray-50
                                border border-gray-200">

                        <p class="text-xs font-medium text-gray-700 mb-1">
                            Password requirements
                        </p>

                        <p class="text-xs text-gray-500">
                            Use at least 8 characters with a mixture of
                            uppercase, lowercase, numbers and symbols.
                        </p>

                    </div>

                </div>


                <!-- Footer -->
                <div class="flex items-center justify-end gap-3
                            px-6 py-4 border-t border-gray-200">

                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 text-sm font-medium
                            text-gray-700 border border-gray-300
                            rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="updatePassword"
                        class="px-4 py-2 text-sm font-medium
                            text-white bg-gray-800 rounded-lg
                            hover:bg-gray-900
                            disabled:opacity-50"
                    >

                        <span
                            wire:loading.remove
                            wire:target="updatePassword"
                        >
                            Update Password
                        </span>

                        <span
                            wire:loading
                            wire:target="updatePassword"
                        >
                            Updating...
                        </span>

                    </button>

                </div>
            </form>


        </div>
    </div>

    

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('imageCropper', ({ aspectRatio, target, wireSaveMethod }) => ({
        cropper: null,
        imageSrc: null,
        showModal: false,

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) return;

            // basic client-side size guard (mirrors backend limits)
            const maxMb = target === 'signature' ? 1 : 2;
            if (file.size > maxMb * 1024 * 1024) {
                alert(`File too large. Max ${maxMb}MB.`);
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                this.imageSrc = e.target.result;
                this.showModal = true;
                this.$nextTick(() => this.initCropper());
            };
            reader.readAsDataURL(file);

            event.target.value = ''; // allow re-selecting the same file later
        },

        initCropper() {
            if (this.cropper) {
                this.cropper.destroy();
            }

            const image = this.$refs.cropperImage;

            this.cropper = new Cropper(image, {
                aspectRatio: aspectRatio,           // 1 = square for profile pic, NaN = free crop for signature
                viewMode: 1,
                autoCropArea: 1,
                dragMode: 'move',
                background: false,
                responsive: true,
                checkOrientation: true,
            });
        },

        crop() {
            if (!this.cropper) return;

            const isSignature = target === 'signature';

            const canvas = this.cropper.getCroppedCanvas({
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
                // transparent background for signature, white for profile pic
                fillColor: isSignature ? 'transparent' : '#ffffff',
                ...(target === 'profile' ? { width: 512, height: 512 } : {}),
            });

            const mimeType = isSignature ? 'image/png' : 'image/jpeg';
            const quality = isSignature ? undefined : 0.9;
            const dataUrl = canvas.toDataURL(mimeType, quality);

            this.$wire.call(wireSaveMethod, dataUrl);
            this.closeModal();
        },

        closeModal() {
            this.showModal = false;
            this.imageSrc = null;
            if (this.cropper) {
                this.cropper.destroy();
                this.cropper = null;
            }
        },
    }));
});
</script>