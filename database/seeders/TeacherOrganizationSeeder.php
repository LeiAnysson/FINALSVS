<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TeacherOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $teachers = [
            [
                'name' => 'Student Affairs Teacher',
                'email' => 'studentaffairs@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 1, 
                'course' => null, 
            ],
            [
                'name' => 'BITS Teacher',
                'email' => 'bits_teacher@example.com',
                'password' => Hash::make('password123'),
                'role_id' => 1, 
                'course' => 'BSIT', 
            ],
        ];

        foreach ($teachers as $teacherData) {
            $teacher = User::updateOrCreate(
                ['email' => $teacherData['email']],
                $teacherData
            );

            if ($teacher->email == 'studentaffairs@example.com') {
                $teacher->organizations()->sync([9, 2]); 
            } elseif ($teacher->email == 'bits_teacher@example.com') {
                $teacher->organizations()->sync([2]); 
            }
        }
    }
}
