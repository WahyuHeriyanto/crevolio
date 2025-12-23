<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tool::insert([
            ['slug' => 'student', 'name' => 'Student'],
            ['slug' => 'frontend-dev', 'name' => 'Frontend Developer'],
            ['slug' => 'backend-dev', 'name' => 'Backend Developer'],
            ['slug' => 'designer', 'name' => 'UI/UX Designer'],
        ]);

    }
}
