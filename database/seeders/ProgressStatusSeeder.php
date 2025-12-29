<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgressStatus;
use Illuminate\Support\Str;

class ProgressStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $status = [
            'Open',
            'Public',
            'Private',
            'Draft',
        ];

        foreach ($status as $name) {
            ProgressStatus::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
