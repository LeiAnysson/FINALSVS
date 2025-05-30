<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('otps')->insert([
            [
                'user_id' => 3,
                'election_id' => 1,
                'code' => '123456',
                'isUsed' => false,
                'expired_at' => now()->addMinutes(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
