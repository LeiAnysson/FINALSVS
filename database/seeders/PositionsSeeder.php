<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Position;

class PositionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $positions = [
            // Student Government positions
            ['position_name' => 'President', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Vice President', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Secretary', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Assist. Secretary', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Treasurer', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Assist. Treasurer', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Auditor', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Assist. Auditor', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Business Manager', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Assit. Business Manager', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Multimedia 1', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Multimedia 2', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '1st year rep.', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '2nd year rep.', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '3rd year rep.', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '4th year rep.', 'category' => 'student_gov', 'created_at' => now(), 'updated_at' => now()],

            // Organization positions
            ['position_name' => 'President', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Vice President', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Secretary', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Treasurer', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Auditor', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Business Manager', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Multimedia 1', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => 'Multimedia 2', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '1st year rep.', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '2nd year rep.', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '3rd year rep.', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
            ['position_name' => '4th year rep.', 'category' => 'organization', 'created_at' => now(), 'updated_at' => now()],
        ];
        DB::table('positions')->insert($positions);
    }
}

