<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Device;
use App\Mail\DeviceApproved;
use Illuminate\Support\Facades\Mail;

class PendingApproval extends Component
{
    use WithPagination;

    public function approveDevice($deviceId)
    {

     try {
            $device = Device::findOrFail($deviceId);
            

            $user = User::find($device->user_id);

            if (!$user || !$user->email) {
                // Throw exception manually so that the catch block handles notification
                throw new \Exception('User email not found.');
            }

            else{
                            // Send email
            Mail::to($user->email)->send(new DeviceApproved([
                'device' => $device,
                'user'   => $user
            ]));

            // Update the device's line manager approval status
            $device->update(['line_manager_approval' => 1]);

            // Dispatch a success notification
            $this->dispatch('notify', 
                type: 'success',
                title: 'Device Approved',
                message: "Device Approved successfully."
            );
            }



        } catch (\Exception $e) {
                // If any error occurs, throw an error notification
                $this->dispatch('notify', 
                    type: 'error',
                    title: 'Approval Failed',
                    message: $e->getMessage()
                );
        }
    }


    #[Layout('layouts.dashboard')]
    public function render()
    {
        $managerId = auth()->id();   // <-- this gets the currently logged-in user's ID

        
        return view('livewire.pending-approval', [
        'pendingDevices' => Device::where('line_manager_approval', 0)
            ->whereHas('user', function ($query) use ($managerId) {
                $query->where('line_manager_id', $managerId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5),
        ]);
    }
}