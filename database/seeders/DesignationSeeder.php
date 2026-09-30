<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $designation = Designation::factory()->create([
            'name' => 'Chief Executive Officer',
            'division_id' => 1, // Assuming the division ID is 1
            'reports_to' => null, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'Chief Operating Officer',
            'division_id' => 1, // Assuming the division ID is 1
            'reports_to' => 1, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'Human Resources Manager',
            'division_id' => 1, // Assuming the division ID is 1
            'reports_to' => 2, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'Chief Financial Officer',
            'division_id' => 3, // Assuming the division ID is 1
            'reports_to' => 2, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'IT Manager',
            'division_id' => 2, // Assuming the division ID is 2
            'reports_to' => 4, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'Performance Assistant',
            'division_id' => 1, // Assuming the division ID is 2
            'reports_to' => 3, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'HR Assistant',
            'division_id' => 1, // Assuming the division ID is 2
            'reports_to' => 3, // This designation does not report to anyone
        ]);

        $designation = Designation::factory()->create([
            'name' => 'IT Infrastracture and Application Support Coordinator',
            'division_id' => 2, // Assuming the division ID is 2
            'reports_to' => 5, // This designation does not report to anyone
        ]);
    
    }
}
