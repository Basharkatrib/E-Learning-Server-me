<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseFAQ;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FAQsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();

        $commonFaqs = [
            [
                "question" => "What are the prerequisites for this course?",
                "answer" => "This course is designed for beginners with no prior experience required. Basic computer skills would be helpful but aren't mandatory."
            ],
            [
                "question" => "How long will I have access to the course materials?",
                "answer" => "You'll have lifetime access to all course materials, including any future updates."
            ],
            [
                "question" => "Will I receive a certificate upon completion?",
                "answer" => "Yes, you'll receive a certificate of completion that you can add to your resume or LinkedIn profile."
            ],
            [
                "question" => "What if I don't like the course?",
                "answer" => "We offer a 30-day money-back guarantee if you're not satisfied with the course."
            ],
            [
                "question" => "How much time should I dedicate to this course each week?",
                "answer" => "We recommend dedicating 3-5 hours per week to complete the course in the suggested timeframe."
            ]
        ];

        $techFaqs = [
            [
                "question" => "What software/tools will I need for this course?",
                "answer" => "You'll need a computer with internet access. We'll provide instructions for any free software needed during the course."
            ],
            [
                "question" => "Which programming languages are used in this course?",
                "answer" => "The primary language used is specified in the course description, with all code examples provided."
            ],
            [
                "question" => "Will this course help me get a job?",
                "answer" => "While we can't guarantee employment, this course teaches in-demand skills that employers are looking for."
            ],
            [
                "question" => "Is there any coding experience required?",
                "answer" => "This course is designed for all levels, with separate learning paths for beginners and experienced coders."
            ]
        ];

        $businessFaqs = [
            [
                "question" => "Are there any real-world case studies included?",
                "answer" => "Yes, the course includes several real-world case studies and practical examples."
            ],
            [
                "question" => "Will I learn how to apply these concepts in my business?",
                "answer" => "Absolutely! The course focuses on practical applications you can implement immediately."
            ],
            [
                "question" => "Do you provide business templates or tools?",
                "answer" => "Yes, you'll get access to downloadable templates, worksheets, and planning tools."
            ]
        ];

        foreach ($courses as $course) {
            // Add common FAQs (3-5, but no more than available)
            $commonCount = min(5, count($commonFaqs));
            $randomCommonFaqs = collect($commonFaqs)->random(rand(3, $commonCount));

            foreach ($randomCommonFaqs as $faq) {
                CourseFAQ::create([
                    "course_id" => $course->id,
                    "question" => $faq["question"],
                    "answer" => $faq["answer"]
                ]);
            }

            // Add category-specific FAQs
            $parentCategory = $course->category->parent->name;

            if (in_array($parentCategory, ["Development", "IT & Software"])) {
                $techCount = min(3, count($techFaqs));
                $randomTechFaqs = collect($techFaqs)->random(rand(2, $techCount));

                foreach ($randomTechFaqs as $faq) {
                    CourseFAQ::create([
                        "course_id" => $course->id,
                        "question" => $faq["question"],
                        "answer" => $faq["answer"]
                    ]);
                }
            } elseif (in_array($parentCategory, ["Business", "Marketing"])) {
                $businessCount = min(3, count($businessFaqs));
                $randomBusinessFaqs = collect($businessFaqs)->random(rand(2, $businessCount));

                foreach ($randomBusinessFaqs as $faq) {
                    CourseFAQ::create([
                        "course_id" => $course->id,
                        "question" => $faq["question"],
                        "answer" => $faq["answer"]
                    ]);
                }
            }
        }
    }
}
