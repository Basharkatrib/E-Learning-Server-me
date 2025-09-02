<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Development Skills
            "PHP",
            "JavaScript",
            "Python",
            "HTML/CSS",
            "React",
            "Vue.js",
            "Angular",
            "Node.js",
            "Laravel",
            "Django",
            "Flask",
            "Express.js",
            "TypeScript",
            "Swift",
            "Kotlin",
            "Flutter",
            "React Native",
            "Unity",
            "Unreal Engine",
            "C#",
            "Game Design",
            "Data Analysis",
            "Machine Learning",
            "TensorFlow",
            "PyTorch",
            "Pandas",
            "NumPy",
            "SQL",
            "NoSQL",
            "MongoDB",
            "PostgreSQL",

            // Business Skills
            "Entrepreneurship",
            "Business Planning",
            "Financial Analysis",
            "Accounting",
            "Investment Strategies",
            "Stock Market",
            "Cryptocurrency",
            "Leadership",
            "Project Management",
            "Agile Methodologies",
            "Scrum",
            "Team Management",
            "Negotiation",
            "Business Strategy",
            "Startup Funding",

            // Design Skills
            "UI Design",
            "UX Design",
            "Figma",
            "Adobe XD",
            "Sketch",
            "Photoshop",
            "Illustrator",
            "InDesign",
            "Typography",
            "Color Theory",
            "Logo Design",
            "3D Modeling",
            "Blender",
            "Maya",
            "3ds Max",
            "Character Design",
            "Architectural Visualization",
            "Motion Graphics",

            // Marketing Skills
            "Digital Marketing",
            "SEO",
            "SEM",
            "Google Ads",
            "Facebook Ads",
            "Content Marketing",
            "Email Marketing",
            "Social Media Marketing",
            "Influencer Marketing",
            "Analytics",
            "Google Analytics",
            "Copywriting",
            "Branding",
            "Market Research",
            "Growth Hacking",

            // IT & Software Skills
            "Cybersecurity",
            "Ethical Hacking",
            "Network Security",
            "Penetration Testing",
            "Linux Administration",
            "Windows Server",
            "macOS",
            "Bash Scripting",
            "Cloud Computing",
            "AWS",
            "Azure",
            "Google Cloud",
            "DevOps",
            "Docker",
            "Kubernetes",
            "CI/CD",
            "Terraform",
            "Ansible",

            // Personal Development
            "Time Management",
            "Productivity",
            "Goal Setting",
            "Mindfulness",
            "Public Speaking",
            "Communication",
            "Emotional Intelligence",
            "Critical Thinking",
            "Problem Solving",
            "Decision Making",
            "Conflict Resolution",
            "Networking",
            "Resume Writing",
            "Interview Skills",
            "Career Planning",

            // Language Skills
            "English",
            "Spanish",
            "Japanese",
        ];

        foreach ($skills as $skill) {
            $exists = Skill::where('name->en', $skill)->first();
            if (!$exists) {
                Skill::create(["name" => $skill]);
            }
        }
    }
}
