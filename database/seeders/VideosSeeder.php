<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = Section::all();

        if ($sections->isEmpty()) {
            $this->call([SectionSeeder::class]);
            $sections = Section::all();
        }

        // روابط يوتيوب حقيقية ومتنوعة
        $youtubeVideos = [
            "dQw4w9WgXcQ", // Rick Astley - Never Gonna Give You Up
            "9bZkp7q19f0", // PSY - GANGNAM STYLE
            "kJQP7kiw5Fk", // Luis Fonsi - Despacito
            "y6120QOlsfU", // Sandstorm - Darude
            "ZZ5LpwO-An4", // Ylvis - The Fox
            "fJ9rUzIMcZQ", // Queen - Bohemian Rhapsody
            "1Bix44H1v9I", // Queen - Don't Stop Me Now
            "hFZFjoX2cGg", // Queen - Radio Ga Ga
            "vjW8wmF5VWc", // Queen - We Will Rock You
            "tgbNymZ7vqY", // Queen - We Are The Champions
        ];

        $videoTemplates = [
            "Development" => [
                [
                    "title" => [
                        "en" => "Welcome to the Course",
                        "ar" => "مرحباً بك في الدورة"
                    ],
                    "duration" => "05:30",
                    "is_preview" => true
                ],
                [
                    "title" => [
                        "en" => "Setting Up Your Development Environment",
                        "ar" => "إعداد بيئة التطوير الخاصة بك"
                    ],
                    "duration" => "12:45",
                    "is_preview" => false
                ],
                [
                    "title" => [
                        "en" => "Your First Project",
                        "ar" => "مشروعك الأول"
                    ],
                    "duration" => "25:15",
                    "is_preview" => false
                ]
            ],
            "Business" => [
                [
                    "title" => [
                        "en" => "Course Introduction",
                        "ar" => "مقدمة الدورة"
                    ],
                    "duration" => "08:15",
                    "is_preview" => true
                ],
                [
                    "title" => [
                        "en" => "Business Fundamentals",
                        "ar" => "أساسيات الأعمال"
                    ],
                    "duration" => "16:30",
                    "is_preview" => false
                ],
                [
                    "title" => [
                        "en" => "Market Analysis",
                        "ar" => "تحليل السوق"
                    ],
                    "duration" => "20:45",
                    "is_preview" => false
                ]
            ],
            "Design" => [
                [
                    "title" => [
                        "en" => "Introduction to Design",
                        "ar" => "مقدمة في التصميم"
                    ],
                    "duration" => "10:20",
                    "is_preview" => true
                ],
                [
                    "title" => [
                        "en" => "Color Theory",
                        "ar" => "نظرية الألوان"
                    ],
                    "duration" => "15:45",
                    "is_preview" => false
                ],
                [
                    "title" => [
                        "en" => "Typography Basics",
                        "ar" => "أساسيات الطباعة"
                    ],
                    "duration" => "17:30",
                    "is_preview" => false
                ]
            ]
        ];

        $videoIndex = 0;

        foreach ($sections as $section) {
            $category = $section->course->category->parent->name;
            $videos = $videoTemplates[$category] ?? $videoTemplates["Development"];
            
            $order = 1;
            foreach ($videos as $video) {
                $youtubeId = $youtubeVideos[$videoIndex % count($youtubeVideos)];
                
                Video::create([
                    "title" => $video["title"],
                    "video_url" => "https://www.youtube.com/watch?v=" . $youtubeId,
                    "duration" => $video["duration"],
                    "is_preview" => $video["is_preview"],
                    "order" => $order++,
                    "section_id" => $section->id
                ]);
                $videoIndex++;
            }
        }
    }
} 