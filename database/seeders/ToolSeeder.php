<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        $tools = [

            // ======================
            // Programming Languages
            // ======================
            'HTML',
            'CSS',
            'JavaScript',
            'TypeScript',
            'PHP',
            'Python',
            'Java',
            'Kotlin',
            'Swift',
            'Dart',
            'C',
            'C++',
            'C#',
            'Go',
            'Rust',

            // ======================
            // Frameworks & Libraries
            // ======================
            'Laravel',
            'Symfony',
            'CodeIgniter',
            'Express.js',
            'NestJS',
            'Next.js',
            'Nuxt.js',
            'React',
            'Vue.js',
            'Svelte',
            'Angular',
            'Flutter',
            'React Native',
            'Unity',
            'Unreal Engine',

            // ======================
            // Design Tools
            // ======================
            'Figma',
            'Adobe Photoshop',
            'Adobe Illustrator',
            'Adobe After Effects',
            'Adobe Premiere Pro',
            'Blender',
            'Sketch',
            'Framer',
            'Canva',

            // ======================
            // Database & Backend
            // ======================
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'Firebase',
            'Supabase',

            // ======================
            // DevOps & Cloud
            // ======================
            'Docker',
            'Kubernetes',
            'Linux',
            'Git',
            'GitHub',
            'GitLab',
            'Bitbucket',
            'AWS',
            'Google Cloud',
            'Azure',
            'Vercel',
            'Netlify',

            // ======================
            // Others
            // ======================
            'REST API',
            'GraphQL',
            'Postman',
            'Notion',
            'Jira',
            'Slack',
        ];

        foreach ($tools as $name) {
            Tool::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
