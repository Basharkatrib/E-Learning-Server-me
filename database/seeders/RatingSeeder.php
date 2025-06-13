<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use App\Models\Rating;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::all();
        $students = User::where("role", "student")->get();

        // Create some student users if none exist
        if ($students->isEmpty()) {
            $students = User::factory(20)->create();
            foreach ($students as $student) {
                $student->assignRole("student");
            }
        }

        /* foreach ($courses as $course) {
            // Get 5-15 random students to rate this course
            $reviewers = $students->random(rand(5, min(15, $students->count())));

            foreach ($reviewers as $reviewer) {
                $rating = rand(3, 5); // Most ratings between 3-5 (realistic distribution)

                // 20% chance of lower rating (1-2)
                if (rand(1, 5) === 1) {
                    $rating = rand(1, 2);
                }

                Rating::create([
                    "course_id" => $course->id,
                    "user_id" => $reviewer->id,
                    "rating" => $rating,
                    "review" => $this->generateReview($rating, $course->title)
                ]);
            }
        }
    }

    private function generateReview(int $rating, string $courseTitle): ?string
    {
        // 30% chance of no review (just a rating)
        if (rand(1, 10) <= 3) {
            return null;
        }

        $positiveReviews = [
            "This course exceeded my expectations!",
            "The instructor explains concepts clearly and concisely.",
            "Highly recommend to anyone looking to learn " . $courseTitle,
            "Practical examples made the concepts easy to understand.",
            "Worth every penny - I learned so much!",
            "The course materials are top-notch and well organized."
        ];

        $negativeReviews = [
            "The content felt outdated in some sections.",
            "Could use more practical exercises.",
            "Some concepts weren't explained clearly enough.",
            "The course didn't meet my expectations.",
            "Too basic for what I was looking for."
        ];

        $neutralReviews = [
            "Good course overall, but room for improvement.",
            "Decent introduction to " . $courseTitle,
            "Met my basic expectations for this topic.",
            "Some good information, but could be better organized."
        ];

        if ($rating >= 4) {
            return $positiveReviews[array_rand($positiveReviews)];
        } elseif ($rating <= 2) {
            return $negativeReviews[array_rand($negativeReviews)];
        } else {
            return $neutralReviews[array_rand($neutralReviews)];
        }
    } */
   foreach ($courses as $course) {
            // Determine how many reviewers we can have (minimum 1, maximum 15)
            $availableStudents = $students->count();
            $maxReviewers = min(15, $availableStudents);
            $minReviewers = min(5, $maxReviewers);
            
            // Get random students to rate this course
            $reviewerCount = rand($minReviewers, $maxReviewers);
            $reviewers = $students->random($reviewerCount);

            foreach ($reviewers as $reviewer) {
                $rating = rand(3, 5); // Most ratings between 3-5
                
                // 20% chance of lower rating (1-2)
                if (rand(1, 5) === 1) {
                    $rating = rand(1, 2);
                }

                Rating::create([
                    "course_id" => $course->id,
                    "user_id" => $reviewer->id,
                    "rating" => $rating,
                    "review" => $this->generateReview($rating, $course->title)
                ]);
            }
        }
    }

    private function generateReview(int $rating, string $courseTitle): ?string
    {
        // 30% chance of no review
        if (rand(1, 10) <= 3) {
            return null;
        }

        $positiveReviews = [
            "This course exceeded my expectations!",
            "The instructor explains concepts clearly and concisely.",
            "Highly recommend to anyone looking to learn ".$courseTitle,
            "Practical examples made the concepts easy to understand.",
            "Worth every penny - I learned so much!",
            "The course materials are top-notch and well organized.",
            "The best course I've taken on this subject!",
            "Perfect balance of theory and practice."
        ];

        $negativeReviews = [
            "The content felt outdated in some sections.",
            "Could use more practical exercises.",
            "Some concepts weren't explained clearly enough.",
            "The course didn't meet my expectations.",
            "Too basic for what I was looking for.",
            "The video quality could be improved.",
            "Not enough interactive elements."
        ];

        $neutralReviews = [
            "Good course overall, but room for improvement.",
            "Decent introduction to ".$courseTitle,
            "Met my basic expectations for this topic.",
            "Some good information, but could be better organized.",
            "Average course - nothing special but not bad either."
        ];

        if ($rating >= 4) {
            return $positiveReviews[array_rand($positiveReviews)];
        } elseif ($rating <= 2) {
            return $negativeReviews[array_rand($negativeReviews)];
        } else {
            return $neutralReviews[array_rand($neutralReviews)];
        }
    }
}
