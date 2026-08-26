<?php

namespace App\Livewire;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Livewire\Component;

class ManageRoles extends Component
{


    use WithPagination;

    public $roleName = '';
    public $selectedPermissions = [];
    public $search = '';
    public $editingRoleId = null;

    protected $rules = [
        'roleName' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'array'
    ];

public function createRole()
{
    $this->validate();

    $role = Role::create(['name' => $this->roleName]);
    $permissionNames = Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();
    $role->syncPermissions($permissionNames);

    $this->reset(['roleName', 'selectedPermissions']);
    $this->dispatch('notify', 
        type: 'success',
        title: 'Role Created',
        message: 'Role created successfully'
    );
    $this->dispatch('$refresh');
}
public function resetForm(){
    $this->reset();
}


    public function editRole($roleId)
    {
        $role = Role::with('permissions')->find($roleId);
        $this->editingRoleId = $roleId;
        $this->roleName = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
    }

    public function updateRole()
{
    $this->validate([
        'roleName' => 'required|string|max:255|unique:roles,name,'.$this->editingRoleId,
        'selectedPermissions' => 'array'
    ]);

    $role = Role::find($this->editingRoleId);
    $role->update(['name' => $this->roleName]);
    $permissionNames = Permission::whereIn('id', $this->selectedPermissions)->pluck('name')->toArray();
    $role->syncPermissions($permissionNames);

    $this->reset(['roleName', 'selectedPermissions', 'editingRoleId']);
    $this->dispatch('notify', 
        type: 'success',
        title: 'Role Updated',
        message: 'Role updated successfully'
    );
    $this->dispatch('$refresh');
}
    public function deleteRole($roleId)
    {
        Role::find($roleId)->delete();
        $this->dispatch('notify', 
            type: 'success',
            title: 'Role Deleted',
            message: 'Role deleted successfully'
        );
    }



     #[layout('layouts.dashboard')]
    public function render()
    {
       $roles = Role::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->withCount('permissions')
            ->paginate(5);

        $permissions = Permission::all()->groupBy(function ($item) {
            return explode('\n', $item->name);
        });

        return view('livewire.manage-roles', [
            'roles' => $roles,
            'permissions' => $permissions,
            'permissionGroups' => $permissions->keys()
        ]);
    }
    }