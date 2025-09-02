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

        //Get all categories (parents and subcategories)
        $subCategories = Category::all();

        $courseTemplates = [
            //Development
            "Web Development" => [
                [
                    "title" => [
                        "en" => "Complete Web Developer Bootcamp",
                        "ar" => "دورة تطوير الويب الشاملة"
                    ],
                    "duration" => [
                        "en" => "40 hours",
                        "ar" => "40 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://wallpapercave.com/wp/wp6350578.jpg",
                    "price" => 0,
                    "is_sequential" => true,
                    "documents" => [
                        [
                            "title" => "Introduction to Web Development",
                            "type" => "Resource",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                        [
                            "title" => "HTML, CSS, and JavaScript",
                            "type" => "Assignment",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                    ],
                ],
                [
                    "title" => [
                        "en" => "React Masterclass",
                        "ar" => "دورة متقدمة في React"
                    ],
                    "duration" => [
                        "en" => "25 hours",
                        "ar" => "25 ساعة"
                    ],
                    "level" => "intermediate",
                    "thumbnail" => "https://images.unsplash.com/photo-1633356122544-f134324a6cee?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
                    "price" => rand(5, 10) - 0.01,
                    "is_sequential" => true,
                    "documents" => [
                        [
                            "title" => "Introduction to React",
                            "type" => "Resource",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                        [
                            "title" => "React Hooks",
                            "type" => "Assignment",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                    ],
                ],
                [
                    "title" => [
                        "en" => "Laravel Complete Guide",
                        "ar" => "دليل Laravel الشامل"
                    ],
                    "duration" => [
                        "en" => "25 hours",
                        "ar" => "25 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://embed-ssl.wistia.com/deliveries/fece433e54f817872309273fb46fe6e9.jpg",
                    "price" => rand(5, 10) - 0.01,
                    "is_sequential" => true,
                    "documents" => [
                        [
                            "title" => "Introduction to Laravel",
                            "type" => "Resource",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                        [
                            "title" => "Laravel Routing",
                            "type" => "Assignment",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                    ],
                ],
                [
                    "title" => [
                        "en" => "Database Management Masterclass",
                        "ar" => "دورة متقدمة في إدارة القواعد البيانية"
                    ],
                    "duration" => [
                        "en" => "25 hours",
                        "ar" => "25 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse3.mm.bing.net/th/id/OIP.rS1YhxdLLnoxpQllcgrEnAHaDt?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                    "is_sequential" => true,
                    "documents" => [
                        [
                            "title" => "Introduction to Database Management",
                            "type" => "Resource",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                        [
                            "title" => "Database Management",
                            "type" => "Assignment",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                    ],
                ],
            ],
            "Mobile Development" => [
                [
                    "title" => [
                        "en" => "Flutter Complete Guide",
                        "ar" => "دليل Flutter الشامل"
                    ],
                    "duration" => [
                        "en" => "30 hours",
                        "ar" => "30 ساعة"
                    ],
                    "level" => "intermediate",
                    "thumbnail" => "https://blog.jetdevelopers.com/wp-content/uploads/2023/06/Flutter.png",
                    "price" => rand(5, 10) - 0.01,
                    "documents" => [
                        [
                            "title" => "Introduction to Flutter",
                            "type" => "Resource",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                        [
                            "title" => "Flutter Widgets",
                            "type" => "Assignment",
                            "url" => "https://res.cloudinary.com/dna6zcg07/image/upload/v1755528743/LearNova_Documentation-1_cpb7z2.pdf"
                        ],
                    ],
                ],
            ],
            "Data Science" => [
                [
                    "title" => [
                        "en" => "Python for Data Science",
                        "ar" => "بايثون لعلوم البيانات"
                    ],
                    "duration" => [
                        "en" => "25 hours",
                        "ar" => "25 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse3.mm.bing.net/th/id/OIP.fXHgwo8Q58iC_mD_exQWPwHaF7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Business
            "Entrepreneurship" => [
                [
                    "title" => [
                        "en" => "Startup Fundamentals",
                        "ar" => "أساسيات الشركات الناشئة"
                    ],
                    "duration" => [
                        "en" => "10 hours",
                        "ar" => "10 ساعات"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse4.mm.bing.net/th/id/OIP.6LPCDb-V4xXPb8PX3n42AAHaE8?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Design
            "Graphic Design" => [
                [
                    "title" => [
                        "en" => "Adobe Photoshop Masterclass",
                        "ar" => "دورة متقدمة في فوتوشوب"
                    ],
                    "duration" => [
                        "en" => "20 hours",
                        "ar" => "20 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse1.mm.bing.net/th/id/OIP.2WvYG4snFdAr4QDmT2X9EwHaE6?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Marketing
            "Digital Marketing" => [
                [
                    "title" => [
                        "en" => "Digital Marketing Fundamentals",
                        "ar" => "أساسيات التسويق الرقمي"
                    ],
                    "duration" => [
                        "en" => "12 hours",
                        "ar" => "12 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://th.bing.com/th/id/R.b0873c3818211a89adc71fc499984ad8?rik=dgz9fVDw6UpnTw&pid=ImgRaw&r=0",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // IT & Software
            "Network Security" => [
                [
                    "title" => [
                        "en" => "Cybersecurity Fundamentals",
                        "ar" => "أساسيات الأمن السيبراني"
                    ],
                    "duration" => [
                        "en" => "15 hours",
                        "ar" => "15 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://th.bing.com/th/id/R.5dfb35f3ec4e71d458529e0f9eb51de3?rik=NEjWXuYcA8H7tw&pid=ImgRaw&r=0",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Personal Development
            "Productivity" => [
                [
                    "title" => [
                        "en" => "Time Management Mastery",
                        "ar" => "إتقان إدارة الوقت"
                    ],
                    "duration" => [
                        "en" => "6 hours",
                        "ar" => "6 ساعات"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://media.istockphoto.com/photos/time-management-concept-picture-id1173522094?k=20&m=1173522094&s=612x612&w=0&h=pUQ9tY01sOwDMueGs0ouFaR66qqfLLblhdWpVlc0ZHA=",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Language Learning
            "English" => [
                [
                    "title" => [
                        "en" => "English for Beginners",
                        "ar" => "الإنجليزية للمبتدئين"
                    ],
                    "duration" => [
                        "en" => "30 hours",
                        "ar" => "30 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse1.mm.bing.net/th/id/OIP.p4fX-hIDZkY2M78ivPTFogHaEK?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Math
            "Math" => [
                [
                    "title" => [
                        "en" => "Math for Beginners",
                        "ar" => "الرياضيات للمبتدئين"
                    ],
                    "duration" => [
                        "en" => "10 hours",
                        "ar" => "10 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse1.mm.bing.net/th/id/OIP.OEGUfbdCEYgoWNKZd45DVAHaGW?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
            // Science
            "Science" => [
                [
                    "title" => [
                        "en" => "Science for Beginners",
                        "ar" => "العلوم للمبتدئين"
                    ],
                    "duration" => [
                        "en" => "10 hours",
                        "ar" => "10 ساعة"
                    ],
                    "level" => "beginner",
                    "thumbnail" => "https://tse1.mm.bing.net/th/id/OIP.e2edtySWWI1LxgQ9jncMyAHaE7?r=0&rs=1&pid=ImgDetMain&o=7&rm=3",
                    "price" => rand(5, 10) - 0.01,
                ],
            ],
        ];

        $coursesCreated = 0;
        $maxCourses = 100;

        foreach ($subCategories as $subcategory) {

            $categoryKey = is_array($subcategory->name) ? ($subcategory->name['en'] ?? null) : $subcategory->name;
            if (!$categoryKey || !isset($courseTemplates[$categoryKey])) continue;

            foreach ($courseTemplates[$categoryKey] as $template) {
                if ($coursesCreated >= $maxCourses) {
                    break 2; // Break both loops once 10 courses are created
                }
                Course::create([
                    "title" => $template["title"],
                    "description" => $this->generateCourseDescription($template["title"], $categoryKey),
                    "category_id" => $subcategory->id,
                    "user_id" => $teachers->random()->id,
                    "duration" => $template["duration"],
                    "difficulty_level" => $template["level"],
                    "thumbnail_url" => $template["thumbnail"],
                    "default_language" => $this->getDefaultLanguage(
                        isset($subcategory->parent)
                            ? (is_array($subcategory->parent->name) ? ($subcategory->parent->name['en'] ?? null) : $subcategory->parent->name)
                            : null
                    ),
                    "price" => $template["price"],
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
                "30-day money-back guarantee if you're not completely satisfied.",
                "Learn {$title['en']} with step‑by‑step guidance designed for real outcomes.",
                "Build portfolio‑ready projects that demonstrate your $category capabilities.",
                "Clear explanations, practical tips, and proven best practices throughout.",
                "Stay ahead with up‑to‑date lessons aligned with market needs.",
                "Understand core theory, then apply it immediately in guided exercises.",
                "Practice with quizzes and challenges to reinforce every concept.",
                "Discover common pitfalls in $category and how to avoid them like a pro.",
                "Get instructor insights, shortcuts, and time‑saving techniques.",
                "From fundamentals to advanced topics, everything is covered in one place.",
                "By the end, you’ll confidently use {$title['en']} in real projects."
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
                "ضمان استرداد الأموال خلال 30 يومًا إذا لم تكن راضيًا تمامًا.",
                "تعلّم {$title['ar']} بخطوات واضحة تقودك لنتائج عملية ملموسة.",
                "أنشئ مشاريع احترافية تُظهر قدراتك في مجال $category.",
                "شروحات مبسّطة ونصائح عملية وأفضل الممارسات على طول الطريق.",
                "محتوى محدّث يواكب متطلبات سوق العمل الحالية.",
                "افهم الأساسيات ثم طبّقها مباشرة في تمارين موجهة.",
                "اختبر معلوماتك عبر أسئلة وتحديات تعزّز استيعابك.",
                "تعرّف على الأخطاء الشائعة في $category وكيفية تجنّبها باحترافية.",
                "استفد من خبرات المدرب وأساليبه لتوفير الوقت والجهد.",
                "من الأساسيات إلى المواضيع المتقدمة – كل ذلك في مكان واحد.",
                "بنهاية الدورة ستستخدم {$title['ar']} بثقة في مشاريع حقيقية."
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
