<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;
class ManagePermissions extends Component
{
   use WithPagination;

    public $search = '';
    public $permissionName = '';
    public $selectedPermissions = [];
    public $editingPermissionId = null;
    public $isEditing = false;

    protected $rules = [
        'permissionName' => 'required|min:3|unique:permissions,name',
    ];
#[layout('layouts.dashboard')]
    public function render()
    {
        

        return view('livewire.manage-permissions', [
            'permissions' => Permission::when($this->search, function ($query) {
            return $query->where('name', 'like', '%' . $this->search . '%');
        })->paginate(3) ]);
    

      
    }

    public function createPermission()
    {
        $this->validate();

        if ($this->isEditing) {
            $permission = Permission::findById($this->editingPermissionId);
            $permission->name = $this->permissionName;
            $permission->save();

            $this->resetEditState();
            
            $this->dispatch('notify', 
                type: 'success',
                title: 'Success',
                message: 'Permission updated successfully'
            );
        } else {
            Permission::create(['name' => $this->permissionName, 'guard_name' => 'web']);

            $this->permissionName = '';
            
            $this->dispatch('notify', 
                type: 'success',
                title: 'Success',
                message: 'Permission created successfully'
            );
        }
    }

    public function editPermission($id)
    {
        $permission = Permission::findById($id);
        $this->permissionName = $permission->name;
        $this->editingPermissionId = $id;
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->resetEditState();
    }

    protected function resetEditState()
    {
        $this->permissionName = '';
        $this->editingPermissionId = null;
        $this->isEditing = false;
    }

   
    public function deletePermission($id)
    {
        Permission::findById($id)->delete();
        
        $this->dispatch('notify', 
            type: 'success',
            title: 'Success',
            message: 'Permission deleted successfully'
        );
    }
}