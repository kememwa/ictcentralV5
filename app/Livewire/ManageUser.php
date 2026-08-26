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
        $this->reset(['name', 'email', 'selectedRoles']);
    }

    public $selectedDesignation = null; // Selected designation ID

    public $searchHead = ''; // Search text

    public $headResults = []; // Matching users

    public $showHeadDropdown = false;

    //search variables for line manager

    public $selectedLineManager = null; // Selected designation ID

    public $searchLineManager = ''; // Search text

    public $lineManagerResults = []; // Matching users

    public $showLineManagerDropdown = false;

    //search for line manager
    public function updatedSearchLineManager()
    {
        $search = trim($this->searchLineManager);

        if (strlen($search) < 2) {
            $this->lineManagerResults = [];
            $this->showLineManagerDropdown = false;
            return;
        }

        $this->showHeadDropdown = false;

        $this->showLineManagerDropdown = true;

        $this->lineManagerResults = User::where('name', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

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

    //line manager select
    public function selectLineManager($id)
    {
        $lineManager = User::find($id);

        if (!$lineManager) {
            return;
        }

        $this->selectedLineManager = $lineManager->id;

        $this->searchLineManager = $lineManager->name;

        $this->showLineManagerDropdown = false;

        $this->lineManagerResults = [];
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

        $this->headResults = [];
    }

    public function removeHead()
    {
        $this->selectedDesignation = null;
        $this->searchHead = '';
    }

    public function removeLineManagerHead()
    {
        $this->selectedLineManager = null;
        $this->searchLineManager = '';
    }


    public function addUser()
    {

            // $this->prepareAddUser();
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'selectedDesignation' => 'nullable|exists:designations,id',
            'selectedLineManager' => 'nullable|exists:users,id',
            'selectedRoles' => 'required|array',
            'selectedRoles.*' => 'exists:roles,name',
        ]);

        try{

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'designation_id' => $this->selectedDesignation,
            'line_manager_id' => $this->selectedLineManager,
            'password' => Hash::make('123456'),
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
    $this->name = $user->name;
    $this->email = $user->email;
    $this->department_id = $user->dep_id;
    $this->division_id = $user->division_id;
    $this->designation_id = $user->designation_id;
    $this->editSelectedRoles = $user->roles->pluck('name')->toArray();
}
public function updateUser()
{
    $validated = $this->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.$this->editingUserId,
        'department_id' => 'required|exists:departments,id',
        'division_id' => 'required|exists:divisions,id',
        'designation_id' => 'required|exists:designations,id',
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
        'dep_id' => $this->department_id,
        'division_id' => $this->division_id,
        'designation_id' => $this->designation_id,
    ]);

    $user->syncRoles($this->editSelectedRoles);
    $this->reset(['editingUserId', 'name', 'email', 'department_id', 'division_id', 'designation_id', 'editSelectedRoles']);
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