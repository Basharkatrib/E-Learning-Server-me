<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\BenefitsCourse;
use Illuminate\Database\Seeder;

class BenefitsCourseSeeder extends Seeder
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

        $commonBenefits = [
            [
                'title' => [
                    'en' => 'Lifetime Access',
                    'ar' => 'وصول مدى الحياة'
                ]
            ],
            [
                'title' => [
                    'en' => 'Certificate of Completion',
                    'ar' => 'شهادة إتمام'
                ]
            ],
            [
                'title' => [
                    'en' => 'Expert Support',
                    'ar' => 'دعم الخبراء'
                ]
            ],
            [
                'title' => [
                    'en' => 'Mobile Access',
                    'ar' => 'الوصول عبر الهاتف المحمول'
                ]
            ],
            [
                'title' => [
                    'en' => 'Downloadable Resources',
                    'ar' => 'موارد قابلة للتحميل'
                ]
            ]
        ];

        $developmentBenefits = [
            [
                'title' => [
                    'en' => 'Real-World Projects',
                    'ar' => 'مشاريع واقعية'
                ]
            ],
            [
                'title' => [
                    'en' => 'Code Reviews',
                    'ar' => 'مراجعة الكود'
                ]
            ],
            [
                'title' => [
                    'en' => 'GitHub Integration',
                    'ar' => 'تكامل مع GitHub'
                ]
            ],
            [
                'title' => [
                    'en' => 'Debugging Skills',
                    'ar' => 'مهارات التصحيح'
                ]
            ]
        ];

        $businessBenefits = [
            [
                'title' => [
                    'en' => 'Business Templates',
                    'ar' => 'قوالب الأعمال'
                ]
            ],
            [
                'title' => [
                    'en' => 'Case Studies',
                    'ar' => 'دراسات الحالة'
                ]
            ],
            [
                'title' => [
                    'en' => 'Market Analysis Tools',
                    'ar' => 'أدوات تحليل السوق'
                ]
            ],
            [
                'title' => [
                    'en' => 'Business Plan Templates',
                    'ar' => 'قوالب خطة العمل'
                ]
            ]
        ];

        $designBenefits = [
            [
                'title' => [
                    'en' => 'Design Assets',
                    'ar' => 'أصول التصميم'
                ]
            ],
            [
                'title' => [
                    'en' => 'Portfolio Building',
                    'ar' => 'بناء المحفظة'
                ]
            ],
            [
                'title' => [
                    'en' => 'Design Tools Access',
                    'ar' => 'الوصول لأدوات التصميم'
                ]
            ],
            [
                'title' => [
                    'en' => 'Creative Exercises',
                    'ar' => 'تمارين إبداعية'
                ]
            ]
        ];

        foreach ($courses as $course) {
            $order = 1;
            
            // Add common benefits for all courses
            foreach ($commonBenefits as $benefit) {
                BenefitsCourse::create([
                    'course_id' => $course->id,
                    'title' => $benefit['title'],
                    'order' => $order++
                ]);
            }

            // Add category-specific benefits
            $category = $course->category->parent->name;
            $specificBenefits = [];

            if (in_array($category, ['Development', 'IT & Software'])) {
                $specificBenefits = $developmentBenefits;
            } elseif (in_array($category, ['Business', 'Marketing'])) {
                $specificBenefits = $businessBenefits;
            } elseif (in_array($category, ['Design'])) {
                $specificBenefits = $designBenefits;
            }

            foreach ($specificBenefits as $benefit) {
                BenefitsCourse::create([
                    'course_id' => $course->id,
                    'title' => $benefit['title'],
                    'order' => $order++
                ]);
            }
        }
    }
} 