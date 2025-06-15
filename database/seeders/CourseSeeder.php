<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $teachers = User::where("role", "teacher")->get();

        //Get all subcategories
        $subCategories = Category::whereNotNull("parent_id")->get();

        $courseTemplates = [
            //Development
            "Web Development" => [
                [
                    "title" => [
                        "en" => "Complete Web Developer Bootcamp",
                        "ar" => "دورة تطوير الويب الشاملة"
                    ],
                    "duration" => "40 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1585247226801-bc613c441316?q=80&w=1480&auto=format&fit=crop"
                ],
                [
                    "title" => [
                        "en" => "React Masterclass",
                        "ar" => "دورة متقدمة في React"
                    ],
                    "duration" => "25 hours",
                    "level" => "intermediate",
                    "thumbnail" => "https://images.unsplash.com/photo-1633356122544-f134324a6cee?q=80&w=1470&auto=format&fit=crop"
                ],
            ],
            "Mobile Development" => [
                [
                    "title" => [
                        "en" => "Flutter Complete Guide",
                        "ar" => "دليل Flutter الشامل"
                    ],
                    "duration" => "30 hours",
                    "level" => "intermediate",
                    "thumbnail" => "https://images.unsplash.com/photo-1555774698-0b77e0d5fac6?q=80&w=1470&auto=format&fit=crop"
                ],
            ],
            "Data Science" => [
                [
                    "title" => [
                        "en" => "Python for Data Science",
                        "ar" => "بايثون لعلوم البيانات"
                    ],
                    "duration" => "25 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1470&auto=format&fit=crop"
                ],
            ],
            // Business
            "Entrepreneurship" => [
                [
                    "title" => [
                        "en" => "Startup Fundamentals",
                        "ar" => "أساسيات الشركات الناشئة"
                    ],
                    "duration" => "10 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1470&auto=format&fit=crop"
                ],
            ],
            // Design
            "Graphic Design" => [
                [
                    "title" => [
                        "en" => "Adobe Photoshop Masterclass",
                        "ar" => "دورة متقدمة في فوتوشوب"
                    ],
                    "duration" => "20 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1516116216624-53e697fedbea?q=80&w=1470&auto=format&fit=crop"
                ],
            ],
            // Marketing
            "Digital Marketing" => [
                [
                    "title" => [
                        "en" => "Digital Marketing Fundamentals",
                        "ar" => "أساسيات التسويق الرقمي"
                    ],
                    "duration" => "12 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1557838923-2985c318be48?q=80&w=1631&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                ],
            ],
            // IT & Software
            "Network Security" => [
                [
                    "title" => [
                        "en" => "Cybersecurity Fundamentals",
                        "ar" => "أساسيات الأمن السيبراني"
                    ],
                    "duration" => "15 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1470&auto=format&fit=crop"
                ],
            ],
            // Personal Development
            "Productivity" => [
                [
                    "title" => [
                        "en" => "Time Management Mastery",
                        "ar" => "إتقان إدارة الوقت"
                    ],
                    "duration" => "6 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1694905472184-dcfab2382ced?q=80&w=1470&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                ],
            ],
            // Language Learning
            "English" => [
                [
                    "title" => [
                        "en" => "English for Beginners",
                        "ar" => "الإنجليزية للمبتدئين"
                    ],
                    "duration" => "30 hours",
                    "level" => "beginner",
                    "thumbnail" => "https://images.unsplash.com/photo-1546410531-bb4caa6b424d?q=80&w=1471&auto=format&fit=crop"
                ],
            ],
        ];

        $coursesCreated = 0;
        $maxCourses = 10;

        foreach ($subCategories as $subcategory) {

            if (!isset($courseTemplates[$subcategory->name])) continue;

            foreach ($courseTemplates[$subcategory->name] as $template) {
                if ($coursesCreated >= $maxCourses) {
                    break 2; // Break both loops once 10 courses are created
                }
                Course::create([
                    "title" => $template["title"],
                    "description" => $this->generateCourseDescription($template["title"], $subcategory->name),
                    "category_id" => $subcategory->id,
                    "user_id" => $teachers->random()->id,
                    "duration" => $template["duration"],
                    "difficulty_level" => $template["level"],
                    "thumbnail_url" => $template["thumbnail"],
                    "default_language" => $this->getDefaultLanguage($subcategory->parent->name),
                ]);
                $coursesCreated++;
            }
        }
    }

    private function generateCourseDescription($title, $category)
    {
        $phrases = [
            "en" => [
                "This comprehensive course will teach you everything you need to know about {$title['en']}.",
                "Master the concepts with hands-on projects and real-world examples.",
                "Perfect for beginners looking to break into $category.",
                "Take your $category skills to the next level with this in-depth course.",
                "Includes downloadable resources, exercises, and practical assignments.",
                "Get certified upon completion and add this valuable skill to your resume.",
                "Join thousands of satisfied students who have transformed their careers.",
                "Taught by industry experts with years of practical experience.",
                "Lifetime access to course materials and future updates.",
                "30-day money-back guarantee if you're not completely satisfied."
            ],
            "ar" => [
                "ستعلمك هذه الدورة الشاملة كل ما تحتاج معرفته عن {$title['ar']}.",
                "أتقن المفاهيم من خلال المشاريع العملية والأمثلة الواقعية.",
                "مثالية للمبتدئين الذين يتطلعون إلى الدخول في مجال $category.",
                "ارتقِ بمهاراتك في $category إلى المستوى التالي مع هذه الدورة المتعمقة.",
                "تشمل موارد قابلة للتحميل وتمارين ومهام عملية.",
                "احصل على شهادة عند الانتهاء وأضف هذه المهارة القيمة إلى سيرتك الذاتية.",
                "انضم إلى آلاف الطلاب الراضين الذين غيروا مسار حياتهم المهنية.",
                "يتم تدريسها من قبل خبراء الصناعة ذوي الخبرة العملية.",
                "وصول مدى الحياة إلى مواد الدورة والتحديثات المستقبلية.",
                "ضمان استرداد الأموال خلال 30 يومًا إذا لم تكن راضيًا تمامًا."
            ]
        ];

        $description = [];
        foreach (['en', 'ar'] as $lang) {
            shuffle($phrases[$lang]);
            $description[$lang] = implode(' ', array_slice($phrases[$lang], 0, 4));
        }

        return $description;
    }

    private function getDefaultLanguage($parentCategory)
    {
        if ($parentCategory === "Language Learning") {
            return "English";
        }

        $languages = ["English", "Arabic"];
        return $languages[array_rand($languages)];
    }
}
