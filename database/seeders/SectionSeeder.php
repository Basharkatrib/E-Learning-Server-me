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
                ["title" => [
                    "en" => "Bonus Content",
                    "ar" => "محتوى إضافي"
                ]],
                ["title" => [
                    "en" => "Additional Resources",
                    "ar" => "موارد إضافية"
                ]],
                ["title" => [
                    "en" => "Q&A Sessions",
                    "ar" => "جلسات الأسئلة والأجوبة"
                ]],
                ["title" => [
                    "en" => "Interview Preparation",
                    "ar" => "التحضير للمقابلات"
                ]]
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
