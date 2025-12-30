<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Schema::disableForeignKeyConstraints();
        ProjectStatus::truncate();
        Schema::enableForeignKeyConstraints();

        $statuses = [
            ['id' => 1, 'name' => 'Open'],
            ['id' => 2, 'name' => 'Public'],
            ['id' => 3, 'name' => 'Private'],
            ['id' => 4, 'name' => 'Draft'],
        ];

        foreach ($statuses as $status) {
                ProjectStatus::create([
                    'id'   => $status['id'],
                    'name' => $status['name'],
                    'slug' => Str::slug($status['name']),
                ]);
        }
    }
}
