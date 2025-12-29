<?php

namespace Database\Seeders;

use App\Models\Expertise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ExpertiseSeeder extends Seeder
{
    public function run(): void
    {
        $expertises = [

            // ======================
            // Software & Tech Expertise
            // ======================
            'Web Programming',
            'Mobile App Development',
            'Backend Development',
            'Frontend Development',
            'Fullstack Development',
            'API Development',
            'System Architecture',
            'Software Engineering',
            'Game Development',
            'AR / VR Development',

            // ======================
            // UI / UX & Design
            // ======================
            'UI Design',
            'UX Design',
            'UX Research',
            'Interaction Design',
            'Visual Design',
            'Product Design',
            'Design System',
            'User Research',
            'Usability Testing',
            'Brand Identity Design',

            // ======================
            // Data, AI & Automation
            // ======================
            'Data Analysis',
            'Data Visualization',
            'Machine Learning',
            'Artificial Intelligence',
            'Natural Language Processing',
            'Computer Vision',
            'Automation',
            'Prompt Engineering',

            // ======================
            // Infrastructure & Security
            // ======================
            'Cloud Computing',
            'DevOps Engineering',
            'System Administration',
            'Cybersecurity',
            'Network Engineering',
            'Product Management',
            'Project Management',
            'Business Analysis',
            'Startup Development',
            'Market Research',
            'Growth Strategy',
            'User Research',
            'Process Optimization',
            'Digital Marketing',
            'Content Strategy',
            'Copywriting',
            'SEO Strategy',
            'Social Media Strategy',
            'Brand Strategy',
            'Email Marketing',
            'Graphic Design',
            'Motion Design',
            '3D Design',
            'Illustration',
            'Photography',
            'Videography',
            'Video Editing',
            'Animation',
            'Sound Design',
            'Music Production',
        ];

        foreach ($expertises as $name) {
            Expertise::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
