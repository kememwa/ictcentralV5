<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Device;
use App\Models\AssignDeviceLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use App\Models\User;
use App\Models\DeviceIssue;

class Devices extends Component
{
    use WithPagination;

    public string $search = '';
   

    public $showEditModal = false;
    public $reassignDeviceModal = false;
    public $showAssignModal = false;
    public $showDeleteModal = false;

    public $user_id;
    public $name;
    public $tag_number;
    public $category;
    public $value;
    public $deviceId;
    public $currentUser;
    public $reason;
    public $comment;    
    public $purchase_date;
    public $model;
    public $serialNumber;
    public $is_in_good_condition; // Default to true
    public $condition_comment = '';
    public $branch;
    
    protected $listeners = ['refresh-devices' => '$refresh', 
    'editDevice' => 'loadDevice'];


public $users = [];
public string $userSearch = ''; // NEW (for modal)
public $selectedUser;


public function updatedUserSearch()
{
    if (strlen($this->userSearch) < 2) {
        $this->users = [];
        return;
    }

    $this->users = User::select('id', 'name')
        ->where('name', 'like', "%{$this->userSearch}%")
        ->limit(20)
        ->get();
}

public function openDeleteModal($deviceId){
 $device = Device::findOrFail($deviceId);
    $this->deviceId = $device->id;
    $this->name = $device->name;
    $this->tag_number = $device->tag_number;
    $this->category = $device->type;
    $this->showDeleteModal = true;
    $this->reason ='';
}


public function deleteDevice()
{
    $device = Device::findOrFail($this->deviceId);

    $latestAssignment = AssignDeviceLog::where('device_id', $this->deviceId)
    ->where('action_type', 'unassign')
    ->latest('action_date')
    ->first();


    $device->delete();

    AssignDeviceLog::create([
        'device_id' => $this->deviceId,
        'user_id' => $latestAssignment['user_id'],
        'action_by' => auth()->id(),
        'action_type' => 'delete',
        'action_date' => now(),
        'comment' => $this->reason,     
    ]);

    // Flash message for Livewire UI
    $this->dispatch('notify', 
                type: 'success',
                title: 'Device Deleted',
                message: "Device Deleted successfully."
    );

    $this->dispatch('refresh-devices');
    $this->showDeleteModal = false;

    $this->reset(['deviceId', 'name', 'tag_number', 'category']); // optional cleanup
}
// opens the assign modal
public function openAssignModal($deviceId)
{
    $device = Device::findOrFail($deviceId);
    $this->deviceId = $device->id;
    $this->name = $device->name;
    $this->tag_number = $device->tag_number;
    $this->category = $device->type;
    $this->showAssignModal = true;
}

//performs the assign action
public function assignDevice(){

$this->user_id = $this->selectedUser;

    $this->validate([
        'user_id' => 'required|exists:users,id',
        'reason' => 'required|string|max:255',
        'comment' => 'required|string|max:500',
    ]);

    $device = Device::findOrFail($this->deviceId);
    $device->update([
        'user_id' => $this->user_id,
    ]);

    AssignDeviceLog::create([
        'device_id' => $this->deviceId,
        'user_id' => $this->user_id,
        'action_by' => auth()->id(),
        'action_type' => 'assign',
        'action_date' => now(),
        'reason' => $this->reason,
        'comment' => $this->comment,
    ]);

        // Flash message for Livewire UI
    $this->dispatch('notify', 
                type: 'success',
                title: 'Device Assigned',
                message: "Device Assigned successfully."
    );

    $this->dispatch('refresh-devices');
    $this->showAssignModal = false;

    $this->reset(['user_id', 'deviceId','reason', 'comment']); // optional cleanup
}

    public function loadDevice($id)
    {
        $device = Device::findOrFail($id);


        $this->deviceId = $device->id;
        $this->currentUser = $device->user?->name ?? 'Unassigned';
        $this->model = $device->model;
        $this->name = $device->name;
        $this->purchase_date = $device->purchase_date;
        $this->category = $device->type;
        $this->serialNumber = $device->serial_number;
        $this->tag_number = $device->tag_number;
        $this->branch = $device->branch;

        $this->is_in_good_condition = true;

        $this->showEditModal = true;
    }

    // when unassign button is clicked, this function will be called the modal part
    public function unassignDevice()
    {
        $device = Device::findOrFail($this->deviceId);

        $this->validate([
            'reason' => 'required|string|max:255',
            'comment' => 'required|string|max:500',
        ]);

       $device->update([
            'user_id' => null,
            'line_manager_approval' => 0,
            'user_accepted' => 0,
        ]);


        AssignDeviceLog::create([
            'device_id' => $device->id,
            'user_id' => $this->user_id,
            'action_by' => auth()->id(),
            'action_type' => 'unassign',
            'action_date' => now(),
            'reason' => $this->reason,
            'comment' => $this->comment,
        ]);

        $this->dispatch('notify', 
                type: 'success',
                title: 'Device Unassigned',
                message: "Device Unassigned successfully."
        );
        $this->dispatch('refresh-devices');

        $this->reassignDeviceModal = false;

        $this->reset(['reason', 'comment', 'deviceId', 'user_id']); // optional cleanup
    }



// when unassign buttton is clicked, this function will be called and modal will be opened
    public function reassignDevice($deviceId)
    {

        $device = Device::findOrFail($deviceId);
        $this->currentUser = $device->user?->name;
        $this->deviceId = $device->id;
        $this->user_id = $device->user_id;
        $this->name = $device->name;
        $this->tag_number = $device->tag_number;
        $this->category = $device->type;
        $this->reassignDeviceModal = true;

    }
   
    public function update()
    {

    if($this->is_in_good_condition == false && empty($this->condition_comment)){
        $this->addError('condition_comment', 'Please provide a comment when the device is not in good condition.');
        return;

    }
    elseif($this->is_in_good_condition == false && !empty($this->condition_comment)){
        //validate the comment and then create a new issue and update the device condition
        $this->validate([ 
            'tag_number' => 'required',
            'is_in_good_condition' => 'boolean',
            'condition_comment' => 'required_if:is_in_good_condition,false|string|max:500',
        ]);

        Device::find($this->deviceId)->update([
            'good_condition' => false,
        ]);

        DeviceIssue::create([
            'device_id' => $this->deviceId,
            'comment' => $this->condition_comment,
            'status' => 'Active',
        ]);

        // Flash message for Livewire UI
        $this->dispatch('notify', 
                type: 'success',
                title: 'Device Updated with Issue',
                message: "Device Updated successfully. An issue has been logged for this device."
        );

        $this->dispatch('refresh-devices');

        $this->reset(['tag_number', 'is_in_good_condition', 'condition_comment']); // optional cleanup

        $this->showEditModal = false;
    }
    else{
        
        $this->validate([ 
            'tag_number' => 'required',
            'is_in_good_condition' => 'boolean',
            'condition_comment' => 'required_if:is_in_good_condition,false|string|max:500',
        ]);

        Device::find($this->deviceId)->update([
            'tag_number' => strtoupper($this->tag_number),
            'branch' => $this->branch,
        ]);

        // Flash message for Livewire UI
        $this->dispatch('notify', 
                type: 'success',
                title: 'Device Updated',
                message: "Device Updated successfully."
        );

        $this->dispatch('refresh-devices');
        $this->reset(['tag_number']); // optional cleanup

        $this->showEditModal = false;
    }
    
    }     
    


    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
    

        return view('livewire.devices', [
            'devices' => Device::where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('type', 'like', '%' . $this->search . '%')
                               ->orWhereHas('user', function ($query) {
                                   $query->where('name', 'like', '%' . $this->search . '%');
                               })->latest()
                               ->paginate(6),
        ]);
    }
}
