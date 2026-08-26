<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $documents = Document::factory()->create([
            'name'=> 'ID Copy',
        ]);

        $documents = Document::factory()->create([
            'name'=> 'Police Clearance Certificate',
        ]);

        $documents = Document::factory()->create([
            'name'=> 'KRA PIN',
        ]);
    }
}
