<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Programming', 'image' => 'category_covers/programming.png'],
            ['name' => 'Marketing', 'image' => 'category_covers/marketing.png'],
            ['name' => 'AI', 'image' => 'category_covers/ai.png'],
            ['name' => 'Networks', 'image' => 'category_covers/net.png'],
            ['name' => 'Conversation Skills', 'image' => 'category_covers/com skills.png'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['cover_image' => $category['image']]
            );
        }
    }
}
