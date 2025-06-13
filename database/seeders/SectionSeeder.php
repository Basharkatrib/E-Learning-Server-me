<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        if ($courses->isEmpty()) {
            $this->call([CourseSeeder::class]);
            $courses = Course::all();
        }

        $sectionTemplates = [
            "Development" => [
                ["title" => "Introduction and Setup"],
                ["title" => "Core Concepts"],
                ["title" => "Advanced Topics"],
                ["title" => "Practical Projects"],
                ["title" => "Deployment and Next Steps"]
            ],
            "Business" => [
                ["title" => "Course Overview"],
                ["title" => "Fundamental Principles"],
                ["title" => "Case Studies"],
                ["title" => "Implementation Strategies"],
                ["title" => "Final Assessment"]
            ],
            "Design" => [
                ["title" => "Getting Started"],
                ["title" => "Design Fundamentals"],
                ["title" => "Tools and Techniques"],
                ["title" => "Project Workshop"],
                ["title" => "Portfolio Presentation"]
            ],
            // Add templates for other categories...
        ];

        $sectionsCreated = 0;
        $maxSections = 30; // Limit total sections to 50

        foreach ($courses as $course) {
            $category = $course->category->parent->name;
            $sections = $sectionTemplates[$category] ?? $sectionTemplates["Development"]; // Default to Development template
            
            $order = 1;
            foreach ($sections as $section) {
                if ($sectionsCreated >= $maxSections) {
                    break 2; // Break both loops if max sections reached
                }
                Section::create([
                    "title" => $section["title"],
                    "course_id" => $course->id,
                    "order" => $order++
                ]);
                $sectionsCreated++;
            }

            // Add 1-3 bonus sections randomly
            $bonusSections = [
                ["title" => "Bonus Content"],
                ["title" => "Additional Resources"],
                ["title" => "Q&A Sessions"],
                ["title" => "Interview Preparation"]
            ];

            $randomBonus = collect($bonusSections)->random(rand(1, 3));
            foreach ($randomBonus as $bonus) {
                if ($sectionsCreated >= $maxSections) {
                    break 2; // Break both loops if max sections reached
                }
                Section::create([
                    "title" => $bonus["title"],
                    "course_id" => $course->id,
                    "order" => $order++
                ]);
                $sectionsCreated++;
            }
        }
    }
}
