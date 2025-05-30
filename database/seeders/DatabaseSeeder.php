<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
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
        // DB::table("roles")->insert([
        //     ["role_name" => "admin"],
        //     ["role_name" => "student"]
        // ]);
        $this->call([
            RolesSeeder::class,
            OrganizationsSeeder::class,
            UsersSeeder::class,
            ElectionsSeeder::class,
            PositionsSeeder::class,
            CandidatesSeeder::class,
            VotesSeeder::class,
            NotificationsSeeder::class,
            OtpSeeder::class,
            StudentSeeder::class,
            PositionsSeeder::class,
        ]);
    }
}
