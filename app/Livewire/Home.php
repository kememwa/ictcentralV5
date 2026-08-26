<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Mail;
use App\Mail\DiscrepancyReport;
use App\Models\Device;


class Home extends Component
{


public $deviceId;
public $comments;
public $categoryKey;
public $affectedDeviceId;

public function reportIssue()
{
    $this->dispatch('open-report-modal');
}


public function acceptDevice($deviceId)
{
    $this->deviceId = $deviceId;
    Device::where('id', $this->deviceId)->update(['User_Accepted' => 1]);

    $this->dispatch('notify', 
            type: 'success',
            title: 'Device Accepted',
            message: "Device accepted successfully."
        );

    return redirect()->route('home');
}


    
    public $discrepancyCategories = [
        'duplicate_tag_number' => 'Duplicate Tag Number',
        'faulty_mouse' => 'Faulty Mouse',
        'broken_screen' => 'Broken Screen',
        'keyboard_issues' => 'Keyboard Issues',
        'battery_problems' => 'Battery Problems',
        'slow_performance' => 'Slow Performance',
        'missing_device' => 'Missing Device',
        'wrong_specifications' => 'Wrong Specifications',
        'other' => 'Other (Please specify in comments)'
    ];


    protected $rules = [
        'comments' => 'required|string|max:255',
    ];


    public function sendReport()
    {

        $this->validate();


        try {
            $user = auth()->user();

            $device = Device::findorFail($this->affectedDeviceId);
            $comments = $this->comments ?? 'N/A';
            $category = $this->categoryKey ?? 'N/A';
            
            Mail::to('application.support@kimfay.com')->queue(
                new DiscrepancyReport(
                    $user,
                    $comments,
                    $device,
                    $category
                )
            );
            
            $this->dispatch('notify', 
                type: 'success',
                title: 'Mail sent',
                message: "Mail sent successfully"
            );

            // Reset form fields
            $this->reset(['affectedDeviceId', 'categoryKey', 'comments']);
            $this->dispatch('close-report-modal');
          
        } catch (\Exception $e) {
            logger()->error('Email failed: '.$e->getMessage());
            
            $this->dispatch('notify', 
                type: 'error',
                title: 'Mail not sent',
                message: "Mail couldn't be sent: ".$e->getMessage()
            );
            
            throw $e; // Re-throw for Alpine.js to catch
        }
    }

    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.home', [
            'userDevices' => auth()->user()->devices ?? [],
            'discrepancyCategories' => $this->discrepancyCategories
        ]);
    }
}