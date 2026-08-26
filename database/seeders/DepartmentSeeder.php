<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //departments
        $department=Department::factory()->create([
            'name' => 'HR and Admin',
            'hod_id' => '1', // Assuming the HOD user ID is 1
            'status' => 'active',
    ]);

     $department=Department::factory()->create([
            'name' => 'IT',
            'hod_id' => '4', // Assuming the HOD user ID is 4
            'status' => 'active',
    ]);

    }
}
