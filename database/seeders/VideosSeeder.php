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

        $youtubeVideos = [
            // 1
            "XlvsJLer_No?si=VJVugfV18dSr6f9k", // Rick Astley - Never Gonna Give You Up
            "iPKrpRpUOzQ?si=Cl0iqty5Yz8L2iyq", // PSY - GANGNAM STYLE
            "XlvsJLer_No?si=VJVugfV18dSr6f9k", // Rick Astley - Never Gonna Give You Up
            "iPKrpRpUOzQ?si=Cl0iqty5Yz8L2iyq", // PSY - GANGNAM STYLE
            // 2
            "QFaFIcGhPoM?si=Gd4yUfJC2UgK10SQ", // Luis Fonsi - Despacito
            "5_PdMS9CLLI?si=bIzNc8eFpl06wfUP", // Sandstorm - Darude
            "QFaFIcGhPoM?si=Gd4yUfJC2UgK10SQ", // Luis Fonsi - Despacito
            "5_PdMS9CLLI?si=bIzNc8eFpl06wfUP", // Sandstorm - Darude
            // 3
            "rIfdg_Ot-LI?si=_R1s2rJugnNndRIi", // Ylvis - The Fox
            "WgO4T65j8pc?si=qlqXKZLlftxsWdii", // Queen - Bohemian Rhapsody
            "rIfdg_Ot-LI?si=_R1s2rJugnNndRIi", // Ylvis - The Fox
            "WgO4T65j8pc?si=qlqXKZLlftxsWdii", // Queen - Bohemian Rh
            // 4
            "wR0jg0eQsZA?si=GuzWC2-rlVs-7KrI", // Queen - Don't Stop Me Now
            "5OdVJbNCSso?si=y6HB8Z5ziF8sfYDj", // Queen - Radio Ga Ga
            "wR0jg0eQsZA?si=GuzWC2-rlVs-7KrI", // Queen - Don't Stop Me Now
            "5OdVJbNCSso?si=y6HB8Z5ziF8sfYDj", // Queen - Radio Ga Ga
            // 5
            "1xipg02Wu8s?si=YHID2Ei389Myp3Qr", // Queen - We Will Rock You
            "OO_-MbnXQzY?si=By6gey7fl7T9EjcC", // Queen - We Are The Champions
            "1xipg02Wu8s?si=YHID2Ei389Myp3Qr", // Queen - We Will Rock You
            "OO_-MbnXQzY?si=By6gey7fl7T9EjcC", // Queen - We Are The Champions
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
            $parentName = $section->course->category->parent->name ?? null;
            $category = is_array($parentName) ? ($parentName['en'] ?? null) : $parentName;
            $videos = $videoTemplates[$category] ?? $videoTemplates["Development"];
            
            $order = 1;
            $desiredCount = 2;
            $templateCount = max(1, count($videos));
            for ($i = 0; $i < $desiredCount; $i++) {
                $video = $videos[$i % $templateCount];
                $youtubeId = $youtubeVideos[$videoIndex % count($youtubeVideos)];

                Video::create([
                    'title' => $video['title'],
                    'video_url' => 'https://www.youtube.com/embed/' . $youtubeId,
                    'duration' => $video['duration'],
                    'is_preview' => $video['is_preview'],
                    'order' => $order++,
                    'section_id' => $section->id
                ]);
                $videoIndex++;
            }
        }
    }
} 