<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            "Development" => [
                "Web Development",
                "Mobile Developent",
                "Game Development",
                "Data Science"
            ],
            "Business" => [
                "Entrepreneurship",
                "Finance",
                "Management"
            ],
            "Design" => [
                "Graphic Design",
                "UX/UI",
                "3D Modeling"
            ],
            "Marketing" => [
                "Digital Marketing",
                "SEO",
                "Social Media Marketing"
            ],
            "IT & Software" => [
                "Network Security",
                "Operating Systems",
                "Cloud Computing"
            ],
            "Personal Development" => [
                "Productivity",
                "Career Development",
                "Communication Skills"
            ],
            "Language Learning" => [
                "English",
                "Spanish",
                "Japanese"
            ],
        ];

        foreach ($categories as $parentName => $children) {
            $parent = Category::create([
                "name" => $parentName,
                "description" => $parentName . " related courses ",
                "parent_id" => null,
            ]);

            foreach ($children as $childName) {
                Category::create([
                    "name" => $childName,
                    "description" => $childName . " courses ",
                    "parent_id" => $parent->id,
                ]);
            }
        }
    }
}
