<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Lei Anysson Marquez',
            'email' => 'leianysson@example.com',
            'password' => bcrypt('password'),
            'role_id' => 2,
            'student_ID' => '02000293024',
            'course' => 'BSIT',
        ]);

        User::insert([
            [
                'name' => 'Grace Judith Anne Bayonito',
                'email' => 'gracebayonito@example.com',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'student_ID' => '02000290570',
                'course' => 'BSIT',
            ],
            [
                'name' => 'Kurt Russel Papruz',
                'email' => 'kurtpapruz@example.com',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'student_ID' => '02000289857',
                'course' => 'BSIT',
            ],
            [
                'name' => 'Jayson Visnar',
                'email' => 'jaysonvisnar@example.com',
                'password' => bcrypt('password'),
                'role_id' => 2,
                'student_ID' => '02000302857',
                'course' => 'BSIT',
            ],
        ]);

        User::factory()->count(10)->create();
    }
}
