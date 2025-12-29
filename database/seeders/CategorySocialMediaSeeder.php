<?php

namespace Database\Seeders;

use App\Models\CategorySocialMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySocialMediaSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Instagram',
                'icon' => 'social-icons/instagram.svg',
            ],
            [
                'name' => 'Facebook',
                'icon' => 'social-icons/facebook.svg',
            ],
            [
                'name' => 'LinkedIn',
                'icon' => 'social-icons/linkedin.svg',
            ],
            [
                'name' => 'GitHub',
                'icon' => 'social-icons/github.svg',
            ],
        ];

        foreach ($categories as $item) {
            CategorySocialMedia::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                ]
            );
        }
    }
}
