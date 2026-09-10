<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class EditProfile extends Component
{
    public $existingProfilePicture;
    public $existingSignature;

    public function mount()
    {
        $this->existingProfilePicture = auth()->user()->profile_picture;
        $this->existingSignature = auth()->user()->signature_image_path;
    }

    /**
     * Called from Alpine/JS after cropping, with a base64 data URL.
     */
    public function saveProfilePicture($base64Image)
    {
        $path = $this->storeCroppedImage(
            $base64Image,
            'profile-pictures',
            allowedMimes: ['jpeg', 'jpg', 'png', 'webp'],
            maxKb: 2048
        );

        // delete old file
        if ($this->existingProfilePicture) {
            Storage::disk('public')->delete($this->existingProfilePicture);
        }

        auth()->user()->update(['profile_picture' => $path]);
        $this->existingProfilePicture = $path;

        $this->dispatch('profile-picture-updated');
    }

    public function saveSignature($base64Image)
    {
        // signature is always saved as PNG to preserve transparency
        $path = $this->storeCroppedImage(
            $base64Image,
            'signatures',
            allowedMimes: ['png'],
            maxKb: 1024
        );

        if ($this->existingSignature) {
            Storage::disk('public')->delete($this->existingSignature);
        }

        auth()->user()->update(['signature_image_path' => $path]);
        $this->existingSignature = $path;

        $this->dispatch('signature-updated');
    }

    public function deleteProfilePicture()
    {
        if ($this->existingProfilePicture) {
            Storage::disk('public')->delete($this->existingProfilePicture);
            auth()->user()->update(['profile_picture' => null]);
            $this->existingProfilePicture = null;
        }
    }

    public function deleteSignature()
    {
        if ($this->existingSignature) {
            Storage::disk('public')->delete($this->existingSignature);
            auth()->user()->update(['signature_image_path' => null]);
            $this->existingSignature = null;
        }
    }

    /**
     * Decode a base64 data URL, validate it, and store it.
     */
    private function storeCroppedImage(string $base64Image, string $folder, array $allowedMimes, int $maxKb): string
    {
        // data URL format: data:image/png;base64,xxxxx
        if (!preg_match('/^data:image\/(\w+);base64,/', $base64Image, $matches)) {
            abort(422, 'Invalid image data.');
        }

        $extension = strtolower($matches[1]);
        if (!in_array($extension, $allowedMimes)) {
            abort(422, 'Unsupported image type.');
        }

        $data = substr($base64Image, strpos($base64Image, ',') + 1);
        $decoded = base64_decode($data);

        if ($decoded === false) {
            abort(422, 'Could not decode image.');
        }

        if (strlen($decoded) > $maxKb * 1024) {
            abort(422, "Image exceeds {$maxKb}KB limit.");
        }

        $filename = $folder . '/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }


    public $currentPassword;
    public $newPassword;
    public $newPassword_confirmation;

    public function openPasswordModal()
    {
        $this->resetValidation();

        $this->reset([
            'currentPassword',
            'newPassword',
            'newPassword_confirmation',
        ]);
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => [
                'required',
                'current_password',
            ],

            'newPassword' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset([
            'currentPassword',
            'newPassword',
            'newPassword_confirmation',
        ]);

        $this->dispatch('close-password-modal');

        $this->dispatch('notify', 
            type: 'success',
            title: 'Password Updated',
            message: "Your password has been updated successfully."
        );
    }

    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.edit-profile');
    }
}
