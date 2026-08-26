<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
$this->call(RolesAndPermissionsSeeder::class);
$this->call(DepartmentSeeder::class);
$this->call(DivisionSeeder::class);
$this->call(DesignationSeeder::class);
$this->call(DocumentSeeder::class);
$this->call(ChecklistTemplateSeeder::class);
$this->call(VideoSeeder::class);
$this->call(QuestionSeeder::class);

        $User=User::factory()->create([
            'name' => 'it officer',
            'email' => 'it@example.com',
            'designation_id' => '1', // Assuming the designation ID is 1
            'password' => '12345',
            'line_manager_id' => 6, // Assuming line manager ID is 4
        ])->assignRole('It'); 
       
        $User=User::factory()->create([
            'name' => 'hr officer',
            'email' => 'denniskem6@gmail.com',
            'designation_id' => '1',
            'password' => '12345',
            'line_manager_id' => 4, // Assuming line manager ID is 4
        ])->assignRole('Hr');

        $User=User::factory()->create([
            'name' => 'super admin',
            'email' => 'kememwadennis@gmail.com',
            'designation_id' => '2', 
            'password' => '12345',
            'line_manager_id' => 4, // Assuming line manager ID is 4
        ])->assignRole('SuperAdmin');

        $User=User::factory()->create([
            'name' => 'Line Manager',
            'email' => 'linemanager@example.com',
            'designation_id' => '1', 
            'password' => '12345',
            'line_manager_id' => 1, // Assuming line manager ID is 4
        ])->assignRole('LineManager');

        $User=User::factory()->create([
            'name' => 'Admin officer',
            'email' => 'adminofficer@example.com',
            'designation_id' => '2',
            'password' => '12345',
            'line_manager_id' => 2, // Assuming line manager ID is 4
        ])->assignRole('AdminOfficer');

        $User=User::factory()->create([
            'name' => 'finance officer',
            'email' => 'finance@example.com',
            'designation_id' => '1', 
            'password' => '12345',
            'line_manager_id' => 2, // Assuming line manager ID is 4
        ])->assignRole('Finance');

        $User=User::factory()->create([
            'name' => 'User Officer',
            'email' => 'user@example.com',
            'designation_id' => '1', 
            'password' => '12345',
            'line_manager_id' => 3, // Assuming line manager ID is 4
        ])->assignRole('User');
    $this->call(DeviceSeeder::class);

    }


}
