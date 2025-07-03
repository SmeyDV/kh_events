<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Music',
            'Food & Drink',
            'Charity & Causes',
            'Hobbies',
            'Tech',
            'Fashion',
            'Sports',
            'Health & Wellness',
            'Education',
            'Arts & Culture',
            'Business & Networking',
            'Travel & Adventure',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'description' => null,
                ]
            );
        }
    }
}
