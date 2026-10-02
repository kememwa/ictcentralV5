<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;

class ForgotPassword extends Component
{
    public $email = '';

    public function sendResetLink()
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('notify',
                type: 'success',
                title: 'Success',
                message: 'Password reset link has been sent to your email.',
            );
            
            $this->reset('email');

            $this->js("
                setTimeout(() => {
                    window.location.href = '" . route('login') . "';
                }, 3500);
            ");

            return;
        }

        $this->addError('email', __($status));
    }


    #[layout('layouts.auth')]
    public function render()
    {
        return view('livewire.forgot-password');
    }
}
