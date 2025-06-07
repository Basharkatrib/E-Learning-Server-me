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
                "PHP",
                "JavaScript",
                "HTML/CSS",
                "React",
                "Vue.js",
                "Angular",
                "Node.js",
                "Laravel",
                "TypeScript",
                "Express.js",
                "Django",
                "Flask"
            ],
            "Mobile Development" => [
                "Swift",
                "Kotlin",
                "Flutter",
                "React Native",
                "Java",
                "Dart",
                "Mobile UI",
                "Android SDK",
                "iOS Development"
            ],
            "Game Development" => [
                "Unity",
                "Unreal Engine",
                "C#",
                "Game Design",
                "3D Modeling",
                "Character Animation",
                "Game Physics",
                "AR/VR Development"
            ],
            "Data Science" => [
                "Python",
                "Data Analysis",
                "Machine Learning",
                "TensorFlow",
                "PyTorch",
                "Pandas",
                "SQL",
                "NoSQL",
                "Data Visualization",
                "Statistics"
            ],

            // Business
            "Entrepreneurship" => [
                "Entrepreneurship",
                "Business Planning",
                "Startup Funding",
                "Lean Startup",
                "Business Model Canvas",
                "Pitching"
            ],
            "Finance" => [
                "Financial Analysis",
                "Accounting",
                "Investment Strategies",
                "Stock Market",
                "Personal Finance",
                "Corporate Finance",
                "Financial Modeling"
            ],
            "Management" => [
                "Leadership",
                "Project Management",
                "Agile Methodologies",
                "Team Management",
                "Conflict Resolution",
                "Strategic Planning",
                "Change Management"
            ],

            // Design
            "Graphic Design" => [
                "Photoshop",
                "Illustrator",
                "Typography",
                "Color Theory",
                "Logo Design",
                "InDesign",
                "Brand Identity",
                "Print Design"
            ],
            "UX/UI" => [
                "UI Design",
                "UX Design",
                "Figma",
                "Adobe XD",
                "Sketch",
                "User Research",
                "Wireframing",
                "Prototyping"
            ],
            "3D Modeling" => [
                "3D Modeling",
                "Blender",
                "Maya",
                "Character Design",
                "3ds Max",
                "ZBrush",
                "Texturing",
                "Rendering"
            ],

            // Marketing
            "Digital Marketing" => [
                "Digital Marketing",
                "SEO",
                "Content Marketing",
                "Social Media Marketing",
                "Email Marketing",
                "Inbound Marketing",
                "Marketing Automation"
            ],
            "SEO" => [
                "SEO",
                "SEM",
                "Google Analytics",
                "Keyword Research",
                "Technical SEO",
                "On-Page SEO",
                "Link Building"
            ],
            "Social Media Marketing" => [
                "Social Media Marketing",
                "Facebook Ads",
                "Influencer Marketing",
                "Instagram Marketing",
                "TikTok Marketing",
                "Community Management"
            ],

            // IT & Software
            "Network Security" => [
                "Cybersecurity",
                "Network Security",
                "Penetration Testing",
                "Ethical Hacking",
                "Information Security",
                "Risk Management"
            ],
            "Operating Systems" => [
                "Linux Administration",
                "Windows Server",
                "Bash Scripting",
                "PowerShell",
                "System Administration",
                "Virtualization"
            ],
            "Cloud Computing" => [
                "Cloud Computing",
                "AWS",
                "Azure",
                "Google Cloud",
                "Serverless",
                "Cloud Security",
                "DevOps"
            ],

            // Personal Development
            "Productivity" => [
                "Time Management",
                "Productivity",
                "Goal Setting",
                "Task Management",
                "Focus Techniques",
                "Habit Building"
            ],
            "Career Development" => [
                "Resume Writing",
                "Interview Skills",
                "Career Planning",
                "Personal Branding",
                "Networking",
                "Salary Negotiation"
            ],
            "Communication Skills" => [
                "Public Speaking",
                "Communication",
                "Networking",
                "Active Listening",
                "Nonverbal Communication",
                "Persuasion"
            ],

            // Language Learning
            "English" => [
                "English",
                "Grammar",
                "Vocabulary",
                "Business English",
                "IELTS",
                "TOEFL"
            ],
            "Spanish" => [
                "Spanish",
                "Spanish Grammar",
                "Spanish Vocabulary",
                "DELE"
            ],
            "Japanese" => [
                "Japanese",
                "Kanji",
                "JLPT",
                "Japanese Conversation"
            ]
        ];

        foreach ($courses as $course) {
            $categoryName = $course->category->name;

            if (isset($categorySkills[$categoryName])) {
                $relevantSkills = $skills->whereIn("name", $categorySkills[$categoryName]);

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
