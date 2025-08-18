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
                "question" => [
                    "en" => "What are the prerequisites for this course?",
                    "ar" => "ما هي المتطلبات الأساسية لهذه الدورة؟"
                ],
                "answer" => [
                    "en" => "This course is designed for beginners with no prior experience required. Basic computer skills would be helpful but aren't mandatory.",
                    "ar" => "تم تصميم هذه الدورة للمبتدئين ولا تتطلب خبرة سابقة. ستكون مهارات الكمبيوتر الأساسية مفيدة ولكنها ليست إلزامية."
                ]
            ],
            [
                "question" => [
                    "en" => "How long will I have access to the course materials?",
                    "ar" => "كم من الوقت سأحصل على الوصول إلى مواد الدورة؟"
                ],
                "answer" => [
                    "en" => "You'll have lifetime access to all course materials, including any future updates.",
                    "ar" => "ستحصل على وصول مدى الحياة لجميع مواد الدورة، بما في ذلك أي تحديثات مستقبلية."
                ]
            ],
            [
                "question" => [
                    "en" => "Will I receive a certificate upon completion?",
                    "ar" => "هل سأحصل على شهادة عند الانتهاء؟"
                ],
                "answer" => [
                    "en" => "Yes, you'll receive a certificate of completion that you can add to your resume or LinkedIn profile.",
                    "ar" => "نعم، ستحصل على شهادة إتمام يمكنك إضافتها إلى سيرتك الذاتية أو ملفك الشخصي على LinkedIn."
                ]
            ],
            [
                "question" => [
                    "en" => "What if I don't like the course?",
                    "ar" => "ماذا لو لم تعجبني الدورة؟"
                ],
                "answer" => [
                    "en" => "We offer a 30-day money-back guarantee if you're not satisfied with the course.",
                    "ar" => "نقدم ضمان استرداد الأموال خلال 30 يومًا إذا لم تكن راضيًا عن الدورة."
                ]
            ],
            [
                "question" => [
                    "en" => "How much time should I dedicate to this course each week?",
                    "ar" => "كم من الوقت يجب أن أخصص لهذه الدورة كل أسبوع؟"
                ],
                "answer" => [
                    "en" => "We recommend dedicating 3-5 hours per week to complete the course in the suggested timeframe.",
                    "ar" => "نوصي بتخصيص 3-5 ساعات أسبوعيًا لإكمال الدورة في الإطار الزمني المقترح."
                ]
            ]
        ];

        $techFaqs = [
            [
                "question" => [
                    "en" => "What software/tools will I need for this course?",
                    "ar" => "ما هي البرامج/الأدوات التي سأحتاجها لهذه الدورة؟"
                ],
                "answer" => [
                    "en" => "You'll need a computer with internet access. We'll provide instructions for any free software needed during the course.",
                    "ar" => "ستحتاج إلى جهاز كمبيوتر مع اتصال بالإنترنت. سنقدم تعليمات لأي برامج مجانية مطلوبة أثناء الدورة."
                ]
            ],
            [
                "question" => [
                    "en" => "Which programming languages are used in this course?",
                    "ar" => "ما هي لغات البرمجة المستخدمة في هذه الدورة؟"
                ],
                "answer" => [
                    "en" => "The primary language used is specified in the course description, with all code examples provided.",
                    "ar" => "اللغة الأساسية المستخدمة محددة في وصف الدورة، مع توفير جميع أمثلة الكود."
                ]
            ],
            [
                "question" => [
                    "en" => "Will this course help me get a job?",
                    "ar" => "هل ستساعدني هذه الدورة في الحصول على وظيفة؟"
                ],
                "answer" => [
                    "en" => "While we can't guarantee employment, this course teaches in-demand skills that employers are looking for.",
                    "ar" => "على الرغم من أننا لا نستطيع ضمان التوظيف، إلا أن هذه الدورة تعلم مهارات مطلوبة يبحث عنها أصحاب العمل."
                ]
            ],
            [
                "question" => [
                    "en" => "Is there any coding experience required?",
                    "ar" => "هل هناك أي خبرة في البرمجة مطلوبة؟"
                ],
                "answer" => [
                    "en" => "This course is designed for all levels, with separate learning paths for beginners and experienced coders.",
                    "ar" => "تم تصميم هذه الدورة لجميع المستويات، مع مسارات تعليمية منفصلة للمبتدئين والمبرمجين ذوي الخبرة."
                ]
            ]
        ];

        $businessFaqs = [
            [
                "question" => [
                    "en" => "Are there any real-world case studies included?",
                    "ar" => "هل هناك دراسات حالة من العالم الحقيقي متضمنة؟"
                ],
                "answer" => [
                    "en" => "Yes, the course includes several real-world case studies and practical examples.",
                    "ar" => "نعم، تتضمن الدورة عدة دراسات حالة من العالم الحقيقي وأمثلة عملية."
                ]
            ],
            [
                "question" => [
                    "en" => "Will I learn how to apply these concepts in my business?",
                    "ar" => "هل سأتعلم كيفية تطبيق هذه المفاهيم في عملي؟"
                ],
                "answer" => [
                    "en" => "Absolutely! The course focuses on practical applications you can implement immediately.",
                    "ar" => "بالتأكيد! تركز الدورة على التطبيقات العملية التي يمكنك تنفيذها فورًا."
                ]
            ],
            [
                "question" => [
                    "en" => "Do you provide business templates or tools?",
                    "ar" => "هل تقدمون قوالب أو أدوات للأعمال؟"
                ],
                "answer" => [
                    "en" => "Yes, you'll get access to downloadable templates, worksheets, and planning tools.",
                    "ar" => "نعم، ستحصل على وصول إلى قوالب قابلة للتحميل وأوراق عمل وأدوات تخطيط."
                ]
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
