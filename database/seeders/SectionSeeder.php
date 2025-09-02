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
                ["title" => [
                    "en" => "Introduction and Setup",
                    "ar" => "المقدمة والإعداد"
                ]],
                ["title" => [
                    "en" => "Core Concepts",
                    "ar" => "المفاهيم الأساسية"
                ]],
                ["title" => [
                    "en" => "Advanced Topics",
                    "ar" => "المواضيع المتقدمة"
                ]],
                ["title" => [
                    "en" => "Practical Projects",
                    "ar" => "المشاريع العملية"
                ]],
                ["title" => [
                    "en" => "Deployment and Next Steps",
                    "ar" => "النشر والخطوات التالية"
                ]]
            ],
            "Business" => [
                ["title" => [
                    "en" => "Course Overview",
                    "ar" => "نظرة عامة على الدورة"
                ]],
                ["title" => [
                    "en" => "Fundamental Principles",
                    "ar" => "المبادئ الأساسية"
                ]],
                ["title" => [
                    "en" => "Case Studies",
                    "ar" => "دراسات الحالة"
                ]],
                ["title" => [
                    "en" => "Implementation Strategies",
                    "ar" => "استراتيجيات التنفيذ"
                ]],
                ["title" => [
                    "en" => "Final Assessment",
                    "ar" => "التقييم النهائي"
                ]]
            ],
            "Design" => [
                ["title" => [
                    "en" => "Getting Started",
                    "ar" => "البدء"
                ]],
                ["title" => [
                    "en" => "Design Fundamentals",
                    "ar" => "أساسيات التصميم"
                ]],
                ["title" => [
                    "en" => "Tools and Techniques",
                    "ar" => "الأدوات والتقنيات"
                ]],
                ["title" => [
                    "en" => "Project Workshop",
                    "ar" => "ورشة المشروع"
                ]],
                ["title" => [
                    "en" => "Portfolio Presentation",
                    "ar" => "عرض المحفظة"
                ]]
            ],
            // Add templates for other categories...
        ];

        $sectionsCreated = 0;
        $maxSections = 1000; // High cap; we will control per-course counts explicitly

        foreach ($courses as $course) {
            $parentName = $course->category->parent->name ?? null;
            $parentCategory = is_array($parentName) ? ($parentName['en'] ?? null) : $parentName;
            $subName = $course->category->name ?? null;
            $subCategory = is_array($subName) ? ($subName['en'] ?? null) : $subName;

            $sections = $sectionTemplates[$parentCategory] ?? $sectionTemplates["Development"]; // Default to Development template

            // Desired count: 2 for Web Development subcategory, else 5
            $desiredCount = ($subCategory === 'Web Development') ? 2 : 5;
            $desiredCount = min($desiredCount, count($sections));

            $order = 1;
            for ($i = 0; $i < $desiredCount; $i++) {
                if ($sectionsCreated >= $maxSections) {
                    break 2; // Break both loops if global cap reached
                }
                Section::create([
                    'title' => $sections[$i]['title'],
                    'course_id' => $course->id,
                    'order' => $order++
                ]);
                $sectionsCreated++;
            }
            // No bonus sections to keep exact counts per requirement
        }
    }
}
