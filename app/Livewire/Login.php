<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Layout;
use App\Models\Onboarding;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Password;

class Login extends Component
{

 public $email="";
 public $password="";
 public $remember = false;


    public function mount()
    {
    if (Auth::guard('web')->check()) {
            return redirect()->to('/home');
        }

        if (Auth::guard('onboarding')->check()) {
            return redirect()->to('/getting-started');
        }
    }

    private function recordLoginActivity(string $guard): void
    {
        $user = Auth::guard($guard)->user();

        $activity = LoginActivity::create([
            'user_id' => $user->getAuthIdentifier(),
            'email' => $user->email,
            'guard' => $guard,
            'session_id' => session()->getId(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'logged_in_at' => now(),
            'last_activity_at' => now(),
            'status' => 'logged_in',
        ]);

        // Remember which login record belongs to this session.
        session()->put('login_activity_id', $activity->id);
    }

    public function login(){

        $credentials=[
            'email'=>$this->email,
            'password'=>$this->password,
        ];

        if(Auth::guard('web')->attempt($credentials, $this->remember)){
            session()->regenerate();

             $this->recordLoginActivity('web');
            // dd($this->remember);
            return redirect()->intended('/home');
        } 
        elseif (Auth::guard('onboarding')->attempt($credentials, false)) {
            session()->regenerate();

            $this->recordLoginActivity('onboarding');
            
            return redirect()->intended('/getting-started');
        } else {
            // Authentication failed
            $this->addError('email', 'Invalid email or password.');
        }
    }

    public $resetEmail = '';

    public function sendResetLink()
    {
        $this->validate([
            'resetEmail' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $this->resetEmail,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->dispatch('notify',
                type: 'success',
                title: 'Success',
                message: 'Password reset link has been sent to your email.',
            );
            
            $this->reset('resetEmail');

             $this->dispatch('password-reset-link-sent');

            return;
        }

        $this->addError('resetEmail', __($status));
    }


    #[layout('layouts.auth')]
    public function render()
    {
        return view('livewire.login');
    }
}
