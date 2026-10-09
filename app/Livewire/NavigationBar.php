<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Auth;

class NavigationBar extends Component
{

    public function logout()
    {
        $guard = Auth::guard('web')->check()
        ? 'web'
        : 'onboarding';

        $activityId = session('login_activity_id');

        if ($activityId) {
            LoginActivity::whereKey($activityId)
                ->where('status', 'logged_in')
                ->update([
                    'logged_out_at' => now(),
                    'last_activity_at' => now(),
                    'status' => 'logged_out',
                ]);
        }

        Auth::guard($guard)->logout();

        session()->invalidate();
        session()->regenerateToken();
    
        session()->flash('message', 'You have been logged out successfully.');
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.navigation-bar');
    }
}
