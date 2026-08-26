<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Department;
use App\Models\User;

class Departments extends Component
{
public string $search = '';
public string $actionFilter = '';

public $department_name = '';

public $department_head = null; // Selected user ID

public $searchHead = ''; // Search text

public $headResults = []; // Matching users

public $showHeadDropdown = false;


protected $rules = [
    'department_name' => 'required|string|max:255|unique:departments,name',
    'department_head' => 'nullable|exists:users,id',
];

protected $messages = [
    'department_name.required' => 'Department name is required.',
    'department_name.unique' => 'This department already exists.',
    'department_head.exists' => 'Selected head of department is invalid.',
];


//search for head of department
public function updatedSearchHead()
{
    $search = trim($this->searchHead);

    if (strlen($search) < 2) {
        $this->headResults = [];
        $this->showHeadDropdown = false;
        return;
    }

    $this->showHeadDropdown = true;

    $this->headResults = User::query()
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->limit(8)
        ->get();
}

public function selectHead($id)
{
    $user = User::find($id);

    if (!$user) {
        return;
    }

    $this->department_head = $user->id;

    $this->searchHead = $user->name;

    $this->showHeadDropdown = false;

    $this->headResults = [];
}

public function removeHead()
{
    $this->department_head = null;
    $this->searchHead = '';
}

// Create the department
public function createDepartment()
{
    $this->validate();

    try {
        $department = Department::create([
            'name' => $this->department_name,
            'hod_id' => $this->department_head, // Store only the ID
        ]);

        // Reset form
        $this->reset(['department_name', 'searchHead', 'department_head', 'showHeadDropdown', 'headResults']);
        
        // Close modal
        $this->dispatch('close-department-modal');

        // Success notification
        $this->dispatch('notify', 
                type: 'success',
                title: 'Department Created',
                message: "Department created successfully."
        );


    } catch (\Exception $e) {
        $this->addError('department_name', 'Failed to create department. Please try again.');
        
        // Log the error for debugging
        \Log::error('Department creation failed: ' . $e->getMessage());
    }
}


    #[layout('layouts.dashboard')]
    public function render()
    {
        return view('livewire.department',[
            'departments' => Department::where('name', 'like', '%' . $this->search . '%')  
                                          ->latest()
                                          ->paginate(6),
        ]);
    }
}
