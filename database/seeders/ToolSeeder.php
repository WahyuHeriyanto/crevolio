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
            'Figma',
            'Adobe Photoshop',
            'Adobe Illustrator',
            'Adobe After Effects',
            'Adobe Premiere Pro',
            'Blender',
            'Sketch',
            'Framer',
            'Canva',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'Firebase',
            'Supabase',
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
            'REST API',
            'GraphQL',
            'Postman',
            'Notion',
            'Jira',
            'Slack',
            'Microsoft Word',
            'Microsoft Excel',
            'Microsoft Power Point',
            'Microsoft Azure',
            'Google Collaboratory',
            'Google Cloud',
            'Google Document',
            'Google Spreadsheet',
            'TensorFlow',
            'PyTorch',
            'Scikit-Learn',
            'Vertex AI'
            
        ];

        foreach ($tools as $name) {
            Tool::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
