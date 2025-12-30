<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgressStatus;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ProgressStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        ProgressStatus::truncate();
        Schema::enableForeignKeyConstraints();

        $statuses = [
            ['id' => 1, 'name' => 'In Progress'],
            ['id' => 2, 'name' => 'Started'],
            ['id' => 3, 'name' => 'Delayed'],
            ['id' => 4, 'name' => 'Completed'],
        ];

        foreach ($statuses as $status) {
                ProgressStatus::create([
                    'id'   => $status['id'],
                    'name' => $status['name'],
                    'slug' => Str::slug($status['name']),
                ]);
        }
    }
}
