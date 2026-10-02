<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;

class ResetPassword extends Component
{
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->query('email', '');
    }

    public function resetPassword()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {

                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();

            }
        );

        if ($status === Password::PASSWORD_RESET) {

            $this->dispatch('notify',
                type: 'Success',
                title: 'Success',
                message: 'Your password has been reset successfully. You can now log in with your new password.',
            );

            return redirect()->route('login');
        }

        $this->addError('email', __($status));
    }

    #[layout('layouts.auth')]
    public function render()
    {
        return view('livewire.reset-password');
    }
}
