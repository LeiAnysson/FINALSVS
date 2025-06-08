<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('candidates')->insert([
            [
                'user_id' => 3, 
                'election_id' => 1,
                'position_id' => 1, 
                'description' => 'Passionate student leader',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
