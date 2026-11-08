<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Department;
use App\Models\User;
use Illuminate\Validation\Rule;

class Departments extends Component
{
public string $search = '';
public string $actionFilter = '';

public $department_name = '';

public $department_head = null; // Selected user ID

public $searchHead = ''; // Search text

public $headResults = []; // Matching users

public $showHeadDropdown = false;

//editing department variables

public $editingDepartmentId = null;
public $showEditDepartmentModal = false;

public $edit_department_name = '';
public $edit_department_head = null; // Selected user ID for editing

public $edit_searchHead = '';
public $edit_showHeadDropdown = false;
public $edit_headResults = [];


// Open the edit department modal and load existing details
public function editDepartment($id)
{
    $department = Department::with('hod')->findOrFail($id);

    $this->resetValidation();

    $this->editingDepartmentId = $department->id;
    $this->edit_department_name = $department->name;

    $this->edit_department_head = $department->hod_id;
    $this->edit_searchHead = $department->hod?->name ?? '';

    $this->edit_showHeadDropdown = false;
    $this->edit_headResults = [];

    $this->showEditDepartmentModal = true;
}


// Search for HOD when editing
public function updatedEditSearchHead()
{
    $search = trim($this->edit_searchHead);

    if (strlen($search) < 3) {
        $this->edit_headResults = [];
        $this->edit_showHeadDropdown = false;
        return;
    }

    $this->edit_showHeadDropdown = true;

    $this->edit_headResults = User::query()
        ->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->orderBy('name')
        ->limit(8)
        ->get();
}


// Select HOD
public function selectEditHead($id)
{
    $user = User::find($id);

    if (!$user) {
        return;
    }

    $this->edit_department_head = $user->id;
    $this->edit_searchHead = $user->name;

    $this->edit_showHeadDropdown = false;
    $this->edit_headResults = [];
}


// Remove selected HOD
public function removeEditHead()
{
    $this->edit_department_head = null;
    $this->edit_searchHead = '';

    $this->edit_showHeadDropdown = false;
    $this->edit_headResults = [];

    $this->resetValidation('edit_department_head');
}


// Update department
public function updateDepartment()
{
    $this->validate([
        'edit_department_name' => 'required|string|max:255',
        'edit_department_head' => 'required|exists:users,id',
    ]);

    try {
        $department = Department::findOrFail($this->editingDepartmentId);

        $department->update([
            'name' => $this->edit_department_name,
            'hod_id' => $this->edit_department_head,
        ]);

        // Close and reset edit modal
        $this->reset([
            'editingDepartmentId',
            'showEditDepartmentModal',
            'edit_department_name',
            'edit_department_head',
            'edit_searchHead',
            'edit_showHeadDropdown',
            'edit_headResults',
        ]);

        $this->dispatch('close-edit-department-modal');

        $this->dispatch(
            'notify',
            type: 'success',
            title: 'Department Updated',
            message: 'Department updated successfully.'
        );

    } catch (\Exception $e) {
        \Log::error('Department update failed: ' . $e->getMessage());

        $this->addError(
            'edit_department_name',
            'Failed to update department. Contact Admin.'
        );
    }
}





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
        $this->addError('department_name', 'Failed to create department. Contact Admin.');
        
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
