<?php

namespace Database\Seeders;

use App\Models\CareerPosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CareerPositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [

            // ======================
            // Tech / Software
            // ======================
            'Software Engineer',
            'Software Developer',
            'Frontend Developer',
            'Backend Developer',
            'Fullstack Developer',
            'Web Developer',
            'Mobile Developer',
            'Android Developer',
            'iOS Developer',
            'Game Developer',
            'Game Designer',
            'AR/VR Developer',
            'AI Engineer',
            'Machine Learning Engineer',
            'Data Scientist',
            'Data Analyst',
            'Big Data Engineer',
            'DevOps Engineer',
            'Site Reliability Engineer',
            'Cloud Engineer',
            'Cybersecurity Specialist',
            'Blockchain Developer',
            'Smart Contract Developer',
            'QA Engineer',
            'Quality Assurance Tester',
            'System Analyst',
            'IT Support',
            'Network Engineer',

            // ======================
            // Design & Creative
            // ======================
            'UI Designer',
            'UX Designer',
            'UI/UX Designer',
            'Product Designer',
            'Graphic Designer',
            'Visual Designer',
            'Motion Designer',
            'Animator',
            'Illustrator',
            '3D Artist',
            '3D Animator',
            'Concept Artist',
            'Art Director',
            'Creative Director',
            'Photographer',
            'Videographer',
            'Video Editor',
            'Film Maker',

            // ======================
            // Digital Marketing & Content
            // ======================
            'Digital Marketer',
            'SEO Specialist',
            'Content Strategist',
            'Content Creator',
            'Content Writer',
            'Copywriter',
            'Social Media Manager',
            'Brand Strategist',
            'Growth Hacker',
            'Performance Marketer',
            'Email Marketer',

            // ======================
            // Product, Business & Startup
            // ======================
            'Product Manager',
            'Associate Product Manager',
            'Project Manager',
            'Business Analyst',
            'Business Development',
            'Operations Manager',
            'Startup Founder',
            'Co-Founder',
            'Entrepreneur',
            'CEO',
            'CTO',
            'COO',
            'CMO',
            'Investor',
            'Venture Capital Analyst',

            // ======================
            // Education & Research
            // ======================
            'Teacher',
            'Educator',
            'Lecturer',
            'Dosen',
            'Tutor',
            'Instructor',
            'Researcher',
            'Academic Researcher',
            'Curriculum Developer',
            'Education Consultant',

            // ======================
            // Engineering (Non-Software)
            // ======================
            'Civil Engineer',
            'Mechanical Engineer',
            'Electrical Engineer',
            'Industrial Engineer',
            'Architect',
            'Interior Designer',
            'Urban Planner',

            // ======================
            // Media, Entertainment & Personal Brand
            // ======================
            'YouTuber',
            'Streamer',
            'Podcaster',
            'Influencer',
            'Public Speaker',
            'Voice Actor',
            'Musician',
            'Composer',
            'Producer',
            'DJ',
            'Actor',
            'Actress',

            // ======================
            // Freelance & General
            // ======================
            'Freelancer',
            'Consultant',
            'Advisor',
            'Strategist',
            'General Professional',
            'Student',
            'College Student',
            'Job Seeker',
        ];

        foreach ($positions as $name) {
            CareerPosition::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
