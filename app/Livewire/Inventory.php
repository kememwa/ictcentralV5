<?php
namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Device;
use Livewire\Attributes\Layout;
use App\Models\User;

class Inventory extends Component
{
   use WithPagination;

   public function resetForm()
{
    $this->resetErrorBag();
    $this->resetValidation();
    $this->reset(['user_id', 'name', 'type', 'purchase_date', 'cost', 'model', 'tag_number', 'serial_number', 'selectedUser', 'userSearch', 'users']);
}

    public string $search = '';

    public $user_id;
    public $name;
    public $type;
    public $purchase_date;
    public $cost;
    public $model;
    public $tag_number;
    public $serial_number;


    protected $rules = [
        'user_id' => 'nullable|exists:users,id',
        'name' => 'required|string|max:255',
        'type' => 'required|string|min:2',
        'purchase_date' => 'required|date',
        'cost' => 'required|numeric|min:0',
        'model' => 'required|string|max:50',
        'tag_number' => 'required|string|max:50|unique:devices,tag_number',
        'serial_number' => 'required|string|max:50|unique:devices,serial_number',  
    ];



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



    public function openDeviceModal()
    {
        $this->resetForm();

        $this->userSearch = '';
        $this->users = [];
        $this->user_id = null;

        $this->dispatch('open-device-modal');
    }

    public function createDevice()
    {

    //dd($this->selectedUser, $this->name, $this->type, $this->purchase_date, $this->cost, $this->model, strtoupper($this->tag_number), strtoupper($this->serial_number));
    //first do the validation
    $this->validate();

    Device::create([
        'user_id' => $this->selectedUser ?? null,
        'name' => strtoupper($this->name),
        'type' => $this->type,
        'purchase_date' => $this->purchase_date,
        'cost' => $this->cost,
        'model' => strtoupper($this->model),
        'tag_number' => strtoupper($this->tag_number),
        'serial_number' => strtoupper($this->serial_number),
    ]);

    $this->resetForm();
    // Flash message for Livewire UI
    $this->dispatch('notify', 
                type: 'success',
                title: 'Device Created',
                message: "Device Created successfully."
    );

    $this->dispatch('refresh-devices');

    $this->dispatch('close-device-modal');

     
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

 

    #[Layout('layouts.dashboard')]
    public function render()
    {
         return view('livewire.inventory', [
            'devices' => Device::where('name', 'like', '%' . $this->search . '%')
                               ->orWhere('type', 'like', '%' . $this->search . '%')
                               ->paginate(4),
        ]);
    }
}
