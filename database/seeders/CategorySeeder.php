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
                "name" => [
                    "en" => "Development",
                    "ar" => "التطوير"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Web Development",
                            "ar" => "تطوير الويب"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Mobile Development",
                            "ar" => "تطوير الموبايل"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Game Development",
                            "ar" => "تطوير الألعاب"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Data Science",
                            "ar" => "علوم البيانات"
                        ]
                    ]
                ]
            ],
            "Business" => [
                "name" => [
                    "en" => "Business",
                    "ar" => "الأعمال"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Entrepreneurship",
                            "ar" => "ريادة الأعمال"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Finance",
                            "ar" => "المالية"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Management",
                            "ar" => "الإدارة"
                        ]
                    ]
                ]
            ],
            "Design" => [
                "name" => [
                    "en" => "Design",
                    "ar" => "التصميم"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Graphic Design",
                            "ar" => "التصميم الجرافيكي"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "UX/UI",
                            "ar" => "تجربة المستخدم وواجهة المستخدم"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "3D Modeling",
                            "ar" => "النمذجة ثلاثية الأبعاد"
                        ]
                    ]
                ]
            ],
            "Marketing" => [
                "name" => [
                    "en" => "Marketing",
                    "ar" => "التسويق"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Digital Marketing",
                            "ar" => "التسويق الرقمي"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "SEO",
                            "ar" => "تحسين محركات البحث"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Social Media Marketing",
                            "ar" => "التسويق عبر وسائل التواصل الاجتماعي"
                        ]
                    ]
                ]
            ],
            "IT & Software" => [
                "name" => [
                    "en" => "IT & Software",
                    "ar" => "تقنية المعلومات والبرمجيات"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Network Security",
                            "ar" => "أمن الشبكات"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Operating Systems",
                            "ar" => "أنظمة التشغيل"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Cloud Computing",
                            "ar" => "الحوسبة السحابية"
                        ]
                    ]
                ]
            ],
            "Personal Development" => [
                "name" => [
                    "en" => "Personal Development",
                    "ar" => "التنمية الشخصية"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Productivity",
                            "ar" => "الإنتاجية"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Career Development",
                            "ar" => "التطور المهني"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Communication Skills",
                            "ar" => "مهارات الاتصال"
                        ]
                    ]
                ]
            ],
            "Math" => [
                "name" => [
                    "en" => "Math",
                    "ar" => "الرياضيات"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Algebra",
                            "ar" => "الجبر"
                        ]
                    ]
                ]
            ],
            "Science" => [
                "name" => [
                    "en" => "Science",
                    "ar" => "العلوم"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "Physics",
                            "ar" => "الفيزياء"
                        ]
                    ]
                ]
            ],
            "Language Learning" => [
                "name" => [
                    "en" => "Language Learning",
                    "ar" => "تعلم اللغات"
                ],
                "children" => [
                    [
                        "name" => [
                            "en" => "English",
                            "ar" => "اللغة الإنجليزية"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Spanish",
                            "ar" => "اللغة الإسبانية"
                        ]
                    ],
                    [
                        "name" => [
                            "en" => "Japanese",
                            "ar" => "اللغة اليابانية"
                        ]
                    ]
                ]
            ]
        ];

        foreach ($categories as $category) {
            $parent = Category::whereNull('parent_id')
                ->where('name->en', $category['name']['en'])
                ->first();

            if (!$parent) {
                $parent = Category::create([
                    'name' => $category['name'],
                    'description' => [
                        'en' => $category['name']['en'] . ' related courses',
                        'ar' => 'دورات متعلقة بـ ' . $category['name']['ar']
                    ],
                    'parent_id' => null,
                ]);
            }

            foreach (($category['children'] ?? []) as $child) {
                $existingChild = Category::where('parent_id', $parent->id)
                    ->where('name->en', $child['name']['en'])
                    ->first();

                if (!$existingChild) {
                    Category::create([
                        'name' => $child['name'],
                        'description' => [
                            'en' => $child['name']['en'] . ' courses',
                            'ar' => 'دورات ' . $child['name']['ar']
                        ],
                        'parent_id' => $parent->id,
                    ]);
                }
            }
        }
    }
}
