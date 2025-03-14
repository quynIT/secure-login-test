<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'IT', 'description' => 'Phòng Công nghệ thông tin'],
            ['name' => 'HR', 'description' => 'Phòng Nhân sự'],
            ['name' => 'Finance', 'description' => 'Phòng Tài chính'],
        ]);
    }
}
