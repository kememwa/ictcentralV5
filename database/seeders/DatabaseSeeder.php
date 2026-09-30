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
            'name' => 'Manan Shah',
            'email' => 'finance@kimfay.com',
            'designation_id' => '4', // Assuming the designation ID is 1
            'password' => '12345',
        
        ])->assignRole('Finance');

        $User=User::factory()->create([
            'name' => 'Alice Mworia',
            'email' => 'hr@kimfay.com',
            'designation_id' => '3', // Assuming the designation ID is 1
            'password' => '12345',
        
        ])->assignRole('HR');

        $User=User::factory()->create([
            'name' => 'Antony Kiema',
            'email' => 'it@kimfay.com',
            'designation_id' => '5', // Assuming the designation ID is 1
            'password' => '12345',
        
        ])->assignRole('It');

        $User=User::factory()->create([
            'name' => 'Dennis Kememwa',
            'email' => 'application.support@kimfay.com',
            'designation_id' => '8', // Assuming the designation ID is 1
            'password' => '12345',
        
        ])->assignRole('It'); 

        $User=User::factory()->create([
            'name' => 'Althea Marie',
            'email' => 'performance.assistant@kimfay.com',
            'designation_id' => '6', // Assuming the designation ID is 1
            'password' => '12345',
        
        ])->assignRole('It');

        $User=User::factory()->create([
            'name' => 'Jackline Kasinga',
            'email' => 'hr.assistant@kimfay.com',
            'designation_id' => '7', // Assuming the designation ID is 1
            'password' => '12345',
        
        ])->assignRole('It');
       

    $this->call(DeviceSeeder::class);

    }


}
