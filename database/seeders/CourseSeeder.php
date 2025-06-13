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
                    "title" => "Complete Web Developer Bootcamp",
                    "duration" => "40 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Advanced JavaScript Patterns",
                    "duration" => "15 hours",
                    "level" => "advanced"
                ],
                [
                    "title" => "React Masterclass",
                    "duration" => "25 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Node.js: From Zero to Hero",
                    "duration" => "20 hours",
                    "level" => "intermediate"
                ],
            ],
            "Mobile Development" => [
                [
                    "title" => "Flutter Complete Guide",
                    "duration" => "30 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Swift for Beginners",
                    "duration" => "15 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Android Kotlin Development",
                    "duration" => "25 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "React Native Advanced Concepts",
                    "duration" => '18 hours',
                    "level" => "advanced"
                ],
            ],
            "Game Development" => [
                [
                    "title" => "Unity Game Development",
                    "duration" => "35 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Unreal Engine 5 Fundamentals",
                    "duration" => "20 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "C# for Game Programming",
                    "duration" => "15 hours",
                    "level" => "intermediate"
                ],
            ],
            "Data Science" => [
                [
                    "title" => "Python for Data Science",
                    "duration" => "25 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Machine Learning A-Z",
                    "duration" => "40 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Deep Learning with TensorFlow",
                    "duration" => "30 hours",
                    "level" => "advanced"
                ],
            ],

            // Business
            "Entrepreneurship" => [
                [
                    "title" => "Startup Fundamentals",
                    "duration" => "10 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Business Model Innovation",
                    "duration" => "12 hours",
                    "level" => "intermediate"
                ],
            ],
            "Finance" => [
                [
                    "title" => "Personal Finance Mastery",
                    "duration" => "8 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Investment Strategies",
                    "duration" => "15 hours",
                    "level" => "intermediate"
                ],
            ],
            "Management" => [
                [
                    "title" => "Leadership Skills",
                    "duration" => "10 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Project Management Professional",
                    "duration" => "35 hours",
                    "level" => "advanced"
                ],
            ],

            // Design
            "Graphic Design" => [
                [
                    "title" => "Adobe Photoshop Masterclass",
                    "duration" => "20 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Logo Design Principles",
                    "duration" => "8 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Illustrator for Beginners",
                    "duration" => "15 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Advanced Typography",
                    "duration" => "10 hours",
                    "level" => "advanced"
                ],
            ],
            "UX/UI" => [
                [
                    "title" => "User Experience Fundamentals",
                    "duration" => "12 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Figma for UI Designers",
                    "duration" => "15 hours",
                    "level" => "intermediate"
                ],
            ],
            "3D Modeling" => [
                [
                    "title" => "Blender 3D for Beginners",
                    "duration" => "18 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Advanced Maya Techniques",
                    "duration" => "25 hours",
                    "level" => "advanced"
                ],
            ],
            // Marketing
            "Digital Marketing" => [
                [
                    "title" => "Digital Marketing Fundamentals",
                    "duration" => "12 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Google Ads Complete Course",
                    "duration" => "15 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Content Marketing Strategy",
                    "duration" => "10 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Marketing Analytics",
                    "duration" => "12 hours",
                    "level" => "advanced"
                ],
            ],
            "SEO" => [
                [
                    "title" => "SEO for Beginners",
                    "duration" => "8 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Advanced SEO Techniques",
                    "duration" => "12 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Technical SEO Mastery",
                    "duration" => "10 hours",
                    "level" => "advanced"
                ],
                [
                    "title" => "Local SEO Strategies",
                    "duration" => "6 hours",
                    "level" => "intermediate"
                ],
            ],
            "Social Media Marketing" => [
                [
                    "title" => "Instagram Marketing",
                    "duration" => "8 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Facebook Ads Masterclass",
                    "duration" => "12 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Social Media Strategy",
                    "duration" => "10 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "TikTok Marketing",
                    "duration" => "6 hours",
                    "level" => "beginner"
                ],
            ],

            // IT & Software
            "Network Security" => [
                [
                    "title" => "Cybersecurity Fundamentals",
                    "duration" => "15 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Ethical Hacking",
                    "duration" => "25 hours",
                    "level" => "advanced"
                ],
                [
                    "title" => "Network Defense Strategies",
                    "duration" => "18 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "CompTIA Security+ Prep",
                    "duration" => "30 hours",
                    "level" => "intermediate"
                ],
            ],
            "Operating Systems" => [
                [
                    "title" => "Linux Command Line Basics",
                    "duration" => "10 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Windows Server Administration",
                    "duration" => "20 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "macOS Power User",
                    "duration" => "8 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Advanced Linux Administration",
                    "duration" => "25 hours",
                    "level" => "advanced"
                ],
            ],
            "Cloud Computing" => [
                [
                    "title" => "AWS Certified Cloud Practitioner",
                    "duration" => "20 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Azure Fundamentals",
                    "duration" => "15 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Google Cloud Platform Essentials",
                    "duration" => "12 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "DevOps with Kubernetes",
                    "duration" => "25 hours",
                    "level" => "advanced"
                ],
            ],

            // Personal Development
            "Productivity" => [
                [
                    "title" => "Time Management Mastery",
                    "duration" => "6 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Getting Things Done (GTD)",
                    "duration" => "8 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Deep Work Strategies",
                    "duration" => "5 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Notion for Productivity",
                    "duration" => "4 hours",
                    "level" => "beginner"
                ],
            ],
            "Career Development" => [
                [
                    "title" => "Resume Writing Workshop",
                    "duration" => "4 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Interview Success Strategies",
                    "duration" => "6 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Networking for Professionals",
                    "duration" => "5 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Executive Presence",
                    "duration" => "8 hours",
                    "level" => "advanced"
                ],
            ],
            "Communication Skills" => [
                [
                    "title" => "Public Speaking Mastery",
                    "duration" => "10 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Business Writing Skills",
                    "duration" => "6 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Negotiation Techniques",
                    "duration" => "8 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Active Listening Skills",
                    "duration" => "4 hours",
                    "level" => "beginner"
                ],
            ],

            // Language Learning
            "English" => [
                [
                    "title" => "English for Beginners",
                    "duration" => "30 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Business English",
                    "duration" => "20 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "IELTS Preparation",
                    "duration" => "25 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Advanced English Grammar",
                    "duration" => "15 hours",
                    "level" => "advanced"
                ],
            ],
            "Spanish" => [
                [
                    "title" => "Spanish for Beginners",
                    "duration" => "25 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Conversational Spanish",
                    "duration" => "20 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Spanish for Travelers",
                    "duration" => "10 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Advanced Spanish Grammar",
                    "duration" => "15 hours",
                    "level" => "advanced"
                ],
            ],
            "Japanese" => [
                [
                    "title" => "Japanese for Beginners",
                    "duration" => "30 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "JLPT N5 Preparation",
                    "duration" => "25 hours",
                    "level" => "beginner"
                ],
                [
                    "title" => "Japanese Kanji Mastery",
                    "duration" => "20 hours",
                    "level" => "intermediate"
                ],
                [
                    "title" => "Business Japanese",
                    "duration" => "15 hours",
                    "level" => "advanced"
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
                    "thumbnail_url" => $this->getThumbnailForCategory($subcategory->parent->name),
                    "default_language" => $this->getDefaultLanguage($subcategory->parent->name),
                ]);
                $coursesCreated++;
            }
        }
    }

    private function generateCourseDescription($title, $category)
    {
        $phrases = [
            "This comprehensive course will teach you everything you need to know about $title.",
            "Master the concepts with hands-on projects and real-world examples.",
            "Perfect for beginners looking to break into $category.",
            "Take your $category skills to the next level with this in-depth course.",
            "Includes downloadable resources, exercises, and practical assignments.",
            "Get certified upon completion and add this valuable skill to your resume.",
            "Join thousands of satisfied students who have transformed their careers.",
            "Taught by industry experts with years of practical experience.",
            "Lifetime access to course materials and future updates.",
            "30-day money-back guarantee if you're not completely satisfied."
        ];

        shuffle($phrases);
        return implode(' ', array_slice($phrases, 0, 4));
    }

    private function getThumbnailForCategory($parentCategory)
    {
        $thumbnails = [
            "Development" => 'https://source.unsplash.com/random/800x600/?programming',
            "Business" => 'https://source.unsplash.com/random/800x600/?business',
            "Design" => 'https://source.unsplash.com/random/800x600/?design',
            "Marketing" => 'https://source.unsplash.com/random/800x600/?marketing',
            "IT & Software" => 'https://source.unsplash.com/random/800x600/?technology',
            "Personal Development" => 'https://source.unsplash.com/random/800x600/?success',
            "Language Learning" => 'https://source.unsplash.com/random/800x600/?language',
        ];

        return $thumbnails[$parentCategory] ?? 'https://source.unsplash.com/random/800x600/?education';
    }

    private function getDefaultLanguage($parentCategory)
    {
        if ($parentCategory === "Language Learning") {
            return "English"; // Base language for teaching other languages
        }

        $languages = ["English", "Arabic"];
        return $languages[array_rand($languages)];
    }
}
