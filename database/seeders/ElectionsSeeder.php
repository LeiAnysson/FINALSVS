<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ElectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('elections')->insert([
            [
                'org_id' => 1, // BSIT
                'created_by' => 1, // Admin user_id
                'title' => 'BSIT 1st Semester Election',
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(7),
                'status' => 'scheduled',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
