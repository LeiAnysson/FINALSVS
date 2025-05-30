<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'role_id' => 1, // Teacher
                'name' => 'Teacher User',
                'email' => 'teacher@gmail.com',
                'password' => Hash::make('password'),
                'student_ID' => null,
                'course' => null,
            ],
            [
                'role_id' => 2, // Student
                'name' => 'Student User',
                'email' => 'student@gmail.com',
                'password' => Hash::make('password'),
                'student_ID' => null,
                'course' => null,
            ],
        ]);
    }
}
