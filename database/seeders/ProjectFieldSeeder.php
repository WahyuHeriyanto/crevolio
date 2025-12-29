<?php

namespace Database\Seeders;

use App\Models\ProjectField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectFieldSeeder extends Seeder
{
    public function run(): void
    {
        $fields = [
            'Web Development',
            'Mobile Development',
            'Software Development',
            'Game Development',
            'AR / VR Development',

            'UI / UX Design',
            'Graphic Design',
            'Product Design',
            'Illustration',
            'Motion Design',
            '3D Design',

            'Data Science',
            'Data Analysis',
            'Artificial Intelligence',

            'Photography',
            'Videography',
            'Video Editing',
            'Animation',
            'Music Production',
            'Sound Design',

            'Digital Marketing',
            'Branding',
            'Content Creation',
            'Product Management',

            'Education',
            'Research',
            'Open Source',
            'Startup Project',
        ];

        foreach ($fields as $name) {
            ProjectField::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
