<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Create roles
        $SuperAdminRole = Role::create(['name' => 'SuperAdmin']);
        $ItRole = Role::create(['name' => 'It']);
        $HrRole = Role::create(['name' => 'Hr']);
        $LineManagerRole = Role::create(['name' => 'LineManager']);
        $AdminOfficerRole = Role::create(['name' => 'AdminOfficer']);
        $FinanceRole = Role::create(['name' => 'Finance']);
        $UserRole = Role::create(['name' => 'User']);


        //create permissions
        $ItOnboardUserPermission = Permission::create(['name' => 'It Onboard User']);
        $HrOnboardUserPermission = Permission::create(['name' => 'Hr Onboard User']);
        $HrViewAllOffboardingPermission = Permission::create(['name' => 'view all offboarding']);
        $HrViewOffboardingPermission = Permission::create(['name' => 'view hr offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'view it offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'view finance offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'view admin offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'initiate offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete admin checklist']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete it checklist']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete finance checklist']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete employee confirmation']);
        $HrOnboardUserPermission = Permission::create(['name' => 'audit offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete supervisor checklist']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete offboarding']);
        $HrOnboardUserPermission = Permission::create(['name' => 'complete hr checklist']);
        
        
        
        


        //assign permissions to roles
        $ItRole->givePermissionTo($ItOnboardUserPermission);
        $HrRole->givePermissionTo($HrOnboardUserPermission);
    }
}
