<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::with("category")->get();
        $skills = Skill::all();

        // Map categories to relevant skills with more comprehensive lists
        $categorySkills = [
            // Development
            "Web Development" => [
                ["name" => ["en" => "PHP", "ar" => "PHP"]],
                ["name" => ["en" => "JavaScript", "ar" => "جافاسكريبت"]],
                ["name" => ["en" => "HTML/CSS", "ar" => "HTML/CSS"]],
                ["name" => ["en" => "React", "ar" => "React"]],
                ["name" => ["en" => "Vue.js", "ar" => "Vue.js"]],
                ["name" => ["en" => "Angular", "ar" => "Angular"]],
                ["name" => ["en" => "Node.js", "ar" => "Node.js"]],
                ["name" => ["en" => "Laravel", "ar" => "Laravel"]],
                ["name" => ["en" => "TypeScript", "ar" => "TypeScript"]],
                ["name" => ["en" => "Express.js", "ar" => "Express.js"]],
                ["name" => ["en" => "Django", "ar" => "Django"]],
                ["name" => ["en" => "Flask", "ar" => "Flask"]]
            ],
            "Mobile Development" => [
                ["name" => ["en" => "Swift", "ar" => "Swift"]],
                ["name" => ["en" => "Kotlin", "ar" => "Kotlin"]],
                ["name" => ["en" => "Flutter", "ar" => "Flutter"]],
                ["name" => ["en" => "React Native", "ar" => "React Native"]],
                ["name" => ["en" => "Java", "ar" => "Java"]],
                ["name" => ["en" => "Dart", "ar" => "Dart"]],
                ["name" => ["en" => "Mobile UI", "ar" => "واجهة المستخدم للموبايل"]],
                ["name" => ["en" => "Android SDK", "ar" => "Android SDK"]],
                ["name" => ["en" => "iOS Development", "ar" => "تطوير iOS"]]
            ],
            "Game Development" => [
                ["name" => ["en" => "Unity", "ar" => "Unity"]],
                ["name" => ["en" => "Unreal Engine", "ar" => "Unreal Engine"]],
                ["name" => ["en" => "C#", "ar" => "C#"]],
                ["name" => ["en" => "Game Design", "ar" => "تصميم الألعاب"]],
                ["name" => ["en" => "3D Modeling", "ar" => "النمذجة ثلاثية الأبعاد"]],
                ["name" => ["en" => "Character Animation", "ar" => "تحريك الشخصيات"]],
                ["name" => ["en" => "Game Physics", "ar" => "فيزياء الألعاب"]],
                ["name" => ["en" => "AR/VR Development", "ar" => "تطوير الواقع المعزز/الافتراضي"]]
            ],
            "Data Science" => [
                ["name" => ["en" => "Python", "ar" => "بايثون"]],
                ["name" => ["en" => "Data Analysis", "ar" => "تحليل البيانات"]],
                ["name" => ["en" => "Machine Learning", "ar" => "التعلم الآلي"]],
                ["name" => ["en" => "TensorFlow", "ar" => "TensorFlow"]],
                ["name" => ["en" => "PyTorch", "ar" => "PyTorch"]],
                ["name" => ["en" => "Pandas", "ar" => "Pandas"]],
                ["name" => ["en" => "SQL", "ar" => "SQL"]],
                ["name" => ["en" => "NoSQL", "ar" => "NoSQL"]],
                ["name" => ["en" => "Data Visualization", "ar" => "تصور البيانات"]],
                ["name" => ["en" => "Statistics", "ar" => "الإحصاء"]]
            ],

            // Business
            "Entrepreneurship" => [
                ["name" => ["en" => "Entrepreneurship", "ar" => "ريادة الأعمال"]],
                ["name" => ["en" => "Business Planning", "ar" => "تخطيط الأعمال"]],
                ["name" => ["en" => "Startup Funding", "ar" => "تمويل الشركات الناشئة"]],
                ["name" => ["en" => "Lean Startup", "ar" => "الشركة الناشئة الرشيقة"]],
                ["name" => ["en" => "Business Model Canvas", "ar" => "لوحة نموذج العمل"]],
                ["name" => ["en" => "Pitching", "ar" => "العرض التقديمي"]]
            ],
            "Finance" => [
                ["name" => ["en" => "Financial Analysis", "ar" => "التحليل المالي"]],
                ["name" => ["en" => "Accounting", "ar" => "المحاسبة"]],
                ["name" => ["en" => "Investment Strategies", "ar" => "استراتيجيات الاستثمار"]],
                ["name" => ["en" => "Stock Market", "ar" => "سوق الأوراق المالية"]],
                ["name" => ["en" => "Personal Finance", "ar" => "المالية الشخصية"]],
                ["name" => ["en" => "Corporate Finance", "ar" => "المالية المؤسسية"]],
                ["name" => ["en" => "Financial Modeling", "ar" => "النمذجة المالية"]]
            ],
            "Management" => [
                ["name" => ["en" => "Leadership", "ar" => "القيادة"]],
                ["name" => ["en" => "Project Management", "ar" => "إدارة المشاريع"]],
                ["name" => ["en" => "Agile Methodologies", "ar" => "المنهجيات الرشيقة"]],
                ["name" => ["en" => "Team Management", "ar" => "إدارة الفرق"]],
                ["name" => ["en" => "Conflict Resolution", "ar" => "حل النزاعات"]],
                ["name" => ["en" => "Strategic Planning", "ar" => "التخطيط الاستراتيجي"]],
                ["name" => ["en" => "Change Management", "ar" => "إدارة التغيير"]]
            ],

            // Design
            "Graphic Design" => [
                ["name" => ["en" => "Photoshop", "ar" => "فوتوشوب"]],
                ["name" => ["en" => "Illustrator", "ar" => "Illustrator"]],
                ["name" => ["en" => "Typography", "ar" => "الطباعة"]],
                ["name" => ["en" => "Color Theory", "ar" => "نظرية الألوان"]],
                ["name" => ["en" => "Logo Design", "ar" => "تصميم الشعارات"]],
                ["name" => ["en" => "InDesign", "ar" => "InDesign"]],
                ["name" => ["en" => "Brand Identity", "ar" => "الهوية البصرية"]],
                ["name" => ["en" => "Print Design", "ar" => "التصميم للطباعة"]]
            ],
            "UX/UI" => [
                ["name" => ["en" => "UI Design", "ar" => "تصميم واجهة المستخدم"]],
                ["name" => ["en" => "UX Design", "ar" => "تصميم تجربة المستخدم"]],
                ["name" => ["en" => "Figma", "ar" => "Figma"]],
                ["name" => ["en" => "Adobe XD", "ar" => "Adobe XD"]],
                ["name" => ["en" => "Sketch", "ar" => "Sketch"]],
                ["name" => ["en" => "User Research", "ar" => "بحث المستخدم"]],
                ["name" => ["en" => "Wireframing", "ar" => "تصميم الهيكل"]],
                ["name" => ["en" => "Prototyping", "ar" => "النماذج الأولية"]]
            ],
            "3D Modeling" => [
                ["name" => ["en" => "3D Modeling", "ar" => "النمذجة ثلاثية الأبعاد"]],
                ["name" => ["en" => "Blender", "ar" => "Blender"]],
                ["name" => ["en" => "Maya", "ar" => "Maya"]],
                ["name" => ["en" => "Character Design", "ar" => "تصميم الشخصيات"]],
                ["name" => ["en" => "3ds Max", "ar" => "3ds Max"]],
                ["name" => ["en" => "ZBrush", "ar" => "ZBrush"]],
                ["name" => ["en" => "Texturing", "ar" => "التكسير"]],
                ["name" => ["en" => "Rendering", "ar" => "التحويل"]]
            ],

            // Marketing
            "Digital Marketing" => [
                ["name" => ["en" => "Digital Marketing", "ar" => "التسويق الرقمي"]],
                ["name" => ["en" => "SEO", "ar" => "تحسين محركات البحث"]],
                ["name" => ["en" => "Content Marketing", "ar" => "التسويق بالمحتوى"]],
                ["name" => ["en" => "Social Media Marketing", "ar" => "التسويق عبر وسائل التواصل الاجتماعي"]],
                ["name" => ["en" => "Email Marketing", "ar" => "التسويق عبر البريد الإلكتروني"]],
                ["name" => ["en" => "Inbound Marketing", "ar" => "التسويق الجاذب"]],
                ["name" => ["en" => "Marketing Automation", "ar" => "أتمتة التسويق"]]
            ],
            "SEO" => [
                ["name" => ["en" => "SEO", "ar" => "تحسين محركات البحث"]],
                ["name" => ["en" => "SEM", "ar" => "التسويق عبر محركات البحث"]],
                ["name" => ["en" => "Google Analytics", "ar" => "Google Analytics"]],
                ["name" => ["en" => "Keyword Research", "ar" => "بحث الكلمات المفتاحية"]],
                ["name" => ["en" => "Technical SEO", "ar" => "تحسين محركات البحث التقني"]],
                ["name" => ["en" => "On-Page SEO", "ar" => "تحسين محركات البحث داخل الصفحة"]],
                ["name" => ["en" => "Link Building", "ar" => "بناء الروابط"]]
            ],
            "Social Media Marketing" => [
                ["name" => ["en" => "Social Media Marketing", "ar" => "التسويق عبر وسائل التواصل الاجتماعي"]],
                ["name" => ["en" => "Facebook Ads", "ar" => "إعلانات فيسبوك"]],
                ["name" => ["en" => "Influencer Marketing", "ar" => "التسويق عبر المؤثرين"]],
                ["name" => ["en" => "Instagram Marketing", "ar" => "التسويق عبر انستغرام"]],
                ["name" => ["en" => "TikTok Marketing", "ar" => "التسويق عبر تيك توك"]],
                ["name" => ["en" => "Community Management", "ar" => "إدارة المجتمع"]]
            ],

            // IT & Software
            "Network Security" => [
                ["name" => ["en" => "Cybersecurity", "ar" => "الأمن السيبراني"]],
                ["name" => ["en" => "Network Security", "ar" => "أمن الشبكات"]],
                ["name" => ["en" => "Penetration Testing", "ar" => "اختبار الاختراق"]],
                ["name" => ["en" => "Ethical Hacking", "ar" => "القرصنة الأخلاقية"]],
                ["name" => ["en" => "Information Security", "ar" => "أمن المعلومات"]],
                ["name" => ["en" => "Risk Management", "ar" => "إدارة المخاطر"]]
            ],
            "Operating Systems" => [
                ["name" => ["en" => "Linux Administration", "ar" => "إدارة لينكس"]],
                ["name" => ["en" => "Windows Server", "ar" => "خادم ويندوز"]],
                ["name" => ["en" => "Bash Scripting", "ar" => "برمجة Bash"]],
                ["name" => ["en" => "PowerShell", "ar" => "PowerShell"]],
                ["name" => ["en" => "System Administration", "ar" => "إدارة الأنظمة"]],
                ["name" => ["en" => "Virtualization", "ar" => "المحاكاة الافتراضية"]]
            ],
            "Cloud Computing" => [
                ["name" => ["en" => "Cloud Computing", "ar" => "الحوسبة السحابية"]],
                ["name" => ["en" => "AWS", "ar" => "AWS"]],
                ["name" => ["en" => "Azure", "ar" => "Azure"]],
                ["name" => ["en" => "Google Cloud", "ar" => "Google Cloud"]],
                ["name" => ["en" => "Serverless", "ar" => "بدون خادم"]],
                ["name" => ["en" => "Cloud Security", "ar" => "أمن السحابة"]],
                ["name" => ["en" => "DevOps", "ar" => "DevOps"]]
            ],

            // Personal Development
            "Productivity" => [
                ["name" => ["en" => "Time Management", "ar" => "إدارة الوقت"]],
                ["name" => ["en" => "Productivity", "ar" => "الإنتاجية"]],
                ["name" => ["en" => "Goal Setting", "ar" => "تحديد الأهداف"]],
                ["name" => ["en" => "Task Management", "ar" => "إدارة المهام"]],
                ["name" => ["en" => "Focus Techniques", "ar" => "تقنيات التركيز"]],
                ["name" => ["en" => "Habit Building", "ar" => "بناء العادات"]]
            ],
            "Career Development" => [
                ["name" => ["en" => "Resume Writing", "ar" => "كتابة السيرة الذاتية"]],
                ["name" => ["en" => "Interview Skills", "ar" => "مهارات المقابلة"]],
                ["name" => ["en" => "Career Planning", "ar" => "تخطيط المسيرة المهنية"]],
                ["name" => ["en" => "Personal Branding", "ar" => "العلامة الشخصية"]],
                ["name" => ["en" => "Networking", "ar" => "التواصل المهني"]],
                ["name" => ["en" => "Salary Negotiation", "ar" => "التفاوض على الراتب"]]
            ],
            "Communication Skills" => [
                ["name" => ["en" => "Public Speaking", "ar" => "التحدث أمام الجمهور"]],
                ["name" => ["en" => "Communication", "ar" => "التواصل"]],
                ["name" => ["en" => "Networking", "ar" => "التواصل المهني"]],
                ["name" => ["en" => "Active Listening", "ar" => "الاستماع النشط"]],
                ["name" => ["en" => "Nonverbal Communication", "ar" => "التواصل غير اللفظي"]],
                ["name" => ["en" => "Persuasion", "ar" => "الإقناع"]]
            ],

            // Language Learning
            "English" => [
                ["name" => ["en" => "English", "ar" => "اللغة الإنجليزية"]],
                ["name" => ["en" => "Grammar", "ar" => "القواعد"]],
                ["name" => ["en" => "Vocabulary", "ar" => "المفردات"]],
                ["name" => ["en" => "Business English", "ar" => "الإنجليزية للأعمال"]],
                ["name" => ["en" => "IELTS", "ar" => "IELTS"]],
                ["name" => ["en" => "TOEFL", "ar" => "TOEFL"]]
            ],
            "Spanish" => [
                ["name" => ["en" => "Spanish", "ar" => "اللغة الإسبانية"]],
                ["name" => ["en" => "Spanish Grammar", "ar" => "قواعد اللغة الإسبانية"]],
                ["name" => ["en" => "Spanish Vocabulary", "ar" => "مفردات اللغة الإسبانية"]],
                ["name" => ["en" => "DELE", "ar" => "DELE"]]
            ],
            "Japanese" => [
                ["name" => ["en" => "Japanese", "ar" => "اللغة اليابانية"]],
                ["name" => ["en" => "Kanji", "ar" => "الكانجي"]],
                ["name" => ["en" => "JLPT", "ar" => "JLPT"]],
                ["name" => ["en" => "Japanese Conversation", "ar" => "المحادثة باللغة اليابانية"]]
            ]
        ];

        foreach ($courses as $course) {
            $categoryName = $course->category->name;

            if (isset($categorySkills[$categoryName])) {
                $relevantSkills = $skills->whereIn("name", collect($categorySkills[$categoryName])->pluck("name.en")->toArray());

                // Get the minimum between available skills and requested count (3-5)
                $count = min($relevantSkills->count(), rand(3, 5));

                if ($count > 0) {
                    $randomSkills = $relevantSkills->random($count);
                    $course->skills()->attach($randomSkills);
                }
            }
        }
    }
}
