<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Device;
use Livewire\Component;
use App\Models\Division;
use App\Models\Department;
use App\Models\Designation;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ManageUser extends Component
{
    use WithPagination;
    
    public $name;
    public $email;
    public $password;
    public $editingUserId;
    public $role;
    public $editRole;
    public $showSelectedDesignation = false;
    public $selectedRoles = [];
    public $editSelectedRoles = [];
    

    
    public $departments = [];
    public $divisions = [];
    public $designations = [];
    public $selectedUserId;
    public $selectedRole;
    public $users = [];
    public $roles = [];
    public $currentUserRole = '';

    protected $listeners = [
        'userUpdatedOrAdded' => '$refresh',
        'open-add-user-modal' => 'prepareAddUser',
    ];

    public function mount()
    {
        $this->loadDropDownData();
    }

    protected function loadDropDownData()
    {
        $this->users = User::orderBy('name')->get();
        $this->roles = Role::orderBy('name')->get()->pluck('name')->toArray();
    }

    public function prepareAddUser()
    {
        $this->reset(['name', 'email', 'selectedRoles', 'selectedDesignation', 'editSelectedRoles', 'editingUserId', 'searchHead', 'showSelectedDesignation']);
    }

    public $selectedDesignation = null; // Selected designation ID

    public $searchHead = ''; // Search text

    public $headResults = []; // Matching users

    public $showHeadDropdown = false;


    //search for division from the divisions table
    public function updatedSearchHead()
    {
        $search = trim($this->searchHead);

        if (strlen($search) < 2) {
            $this->headResults = [];
            $this->showHeadDropdown = false;
            return;
        }

        $this->showHeadDropdown = true;

        $this->headResults = Designation::query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    
    public function selectHead($id)
    {
        $designation = Designation::find($id);

        if (!$designation) {
            return;
        }

        $this->selectedDesignation = $designation->id;

        $this->searchHead = $designation->name;

        $this->showHeadDropdown = false;
        $this->showSelectedDesignation = true;

        $this->headResults = [];
    }

    public function removeHead()
    {
        $this->selectedDesignation = null;
        $this->searchHead = '';
    }


    public function addUser()
    {

            // $this->prepareAddUser();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'selectedDesignation' => 'nullable|exists:designations,id',
            'selectedRoles' => 'required|array',
            'selectedRoles.*' => 'exists:roles,name',
        ]);

        try{

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'designation_id' => $this->selectedDesignation,
            'password' => Hash::make('P@ssw0rd123'), // Default password
        ]);
        
        $user->syncRoles($this->selectedRoles);
        
        $this->reset(['name', 'email', 'selectedDesignation', 'selectedRoles']);
          $this->dispatch('close-add-user-modal');
        $this->dispatch('userUpdatedOrAdded');

        $this->dispatch('notify', 
            type: 'success',
            title: 'User Added',
            message: "User created successfully"
        );

        } catch (\Exception $e) {
        $this->addError('name', 'Failed to create user. Please try again later.');
        
        // Log the error for debugging
        \Log::error('User creation failed: ' . $e->getMessage());
        }
    }


    public function editUser($userId)
    {
        $this->prepareAddUser();
        $user = User::with(['roles', 'designation'])->find($userId);
        
        $this->editingUserId = $userId;
        $this->selectedDesignation = $user->designation->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department_id = $user->dep_id;
        $this->division_id = $user->division_id;
        $this->searchHead = $user->designation->name;
        $this->editSelectedRoles = $user->roles->pluck('name')->toArray();
    }
    
    public function updateUser()
    {

        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$this->editingUserId,
            'selectedDesignation' => 'required|exists:designations,id',
            'editSelectedRoles' => 'required|array',
            'editSelectedRoles.*' => 'exists:roles,name',
        ]);

        $user = User::find($this->editingUserId);

        if (!$user) {
            $this->dispatch('notify', type: 'error', title: 'Error', message: "User not found.");
            return;
        }

        

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'designation_id' => $this->selectedDesignation,
        ]);

        $user->syncRoles($this->editSelectedRoles);
        $this->reset(['editingUserId', 'name', 'email', 'selectedDesignation', 'editSelectedRoles']);
        $this->dispatch('close-edit-user-modal');
        $this->dispatch('userUpdatedOrAdded');
        $this->dispatch('notify', 
            type: 'success',
            title: 'Update User',
            message: "User has been updated successfully!"
        );
        
        // Close the modal by dispatching an event
        
    }

    public function deleteUser($userId)
    {
        User::find($userId)->delete();
        $this->dispatch('userUpdatedOrAdded');
        $this->dispatch('notify', 
            type: 'success',
            title: 'Update User',
            message: "User has been deactivated!"
        );
    }

  #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.manage-user');
    }
}