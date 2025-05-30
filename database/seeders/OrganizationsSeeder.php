<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organizations')->insert([
            ['org_name' => 'Student Government'],
            ['org_name' => 'BALARAW'],
            ['org_name' => 'BITS'],
            ['org_name' => 'BALANI'],
            ['org_name' => 'BIGHANI'],
            ['org_name' => 'BALANSA - JPIA'],
            ['org_name' => 'JPMAP'],
            ['org_name' => 'BUNTALA'],
            ['org_name' => 'BALANTOK']
        ]);
    }
}
