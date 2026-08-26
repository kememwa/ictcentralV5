<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Livewire\Attributes\Layout;
use App\Models\Onboarding;

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

    public function login(){

        $credentials=[
            'email'=>$this->email,
            'password'=>$this->password,
        ];

        if(Auth::guard('web')->attempt($credentials, $this->remember)){
            session()->regenerate();

            // dd($this->remember);
            return redirect()->intended('/home');
        } 
        elseif (Auth::guard('onboarding')->attempt($credentials, false)) {
            session()->regenerate();
            return redirect()->intended('/getting-started');
        } else {
            // Authentication failed
            $this->addError('email', 'Invalid email or password.');
        }
    }

    #[layout('layouts.auth')]
    public function render()
    {
        return view('livewire.login');
    }
}
