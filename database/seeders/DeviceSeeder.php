<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Device;
class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $device = Device::factory()->create([
            'user_id'=> 1, // Assuming user with ID 1 exists'',
            'name' => 'Dell XPS 13',
            'type' => 'laptop',
            'purchase_date' => '2023-01-01',
            'cost' => 1000,
            'model' => 'XPS 13 9310',
            'tag_number' => 'TAG12345',
            'serial_number' => 'SN12345',
            'branch' => 'HQ',
        ]);
        $device = Device::factory()->create([
            'user_id'=> 2, // Assuming user with ID 2 exists
            'name' => 'Apple iPhone 14',
            'type' => 'smartphone',
            'purchase_date' => '2023-02-01',
            'cost' => 1200,
            'model' => 'iPhone 14',
            'tag_number' => 'TAG67890',
            'serial_number' => 'SN67890',
            'branch' => 'HQ',
        ]);
        $device = Device::factory()->create([
            'user_id'=> 3, // Assuming user with ID 3 exists
            'name' => 'Samsung Galaxy S21',
            'type' => 'smartphone',
            'purchase_date' => '2023-03-01',
            'cost' => 800,
            'model' => 'Galaxy S21',
            'tag_number' => 'TAG54321',
            'serial_number' => 'SN54321',
            'branch' => 'Mombasa',
        ]);
        $device = Device::factory()->create([
            'user_id'=> 4, // Assuming user with ID 4 exists
            'name' => 'HP Spectre x360',
            'type' => 'laptop',
            'purchase_date' => '2023-04-01',
            'cost' => 1500,
            'model' => 'Spectre x360',
            'tag_number' => 'TAG98765',
            'serial_number' => 'SN98765',
            'branch' => 'Tatu-city',
        ]);
        $device = Device::factory()->create([
            'user_id'=> 5, // Assuming user with ID 5 exists
            'name' => 'Sony WH-1000XM4',
            'type' => 'headphones',
            'purchase_date' => '2023-05-01',
            'cost' => 350,
            'model' => 'WH-1000XM4',
            'tag_number' => 'TAG56789',
            'serial_number' => 'SN56789',
            'branch' => 'HQ',
        ]);
        $device = Device::factory()->create([
            'user_id'=> 6, // Assuming user with ID 6 exists
            'name' => 'Apple MacBook Pro',
            'type' => 'laptop',
            'purchase_date' => '2023-06-01',
            'cost' => 2000,
            'model' => 'MacBook Pro',
            'tag_number' => 'TAG11111',
            'serial_number' => 'SN11111',
            'branch' => 'HQ',
        ]);
    }
}
