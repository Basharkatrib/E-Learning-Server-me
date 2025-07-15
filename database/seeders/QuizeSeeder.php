<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Option;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = Course::with(["sections.videos"])->get();

        foreach ($courses as $course) {
            $this->createCourseQuiz($course);
        }
    }

    protected function createCourseQuiz($course)
    {
        $quiz = Quiz::create([
            "course_id" => $course->id,
            "title" => "Knowledge Check: " . $course->title,
            "description" => "Test your understanding of the course material",
            "time_limit" => 15, // Fixed 15 minutes for all quizzes
            "passing_score" => 70,
            "is_published" => true,
        ]);

        // Create 2 multiple choice questions
        $this->createMultipleChoiceQuestion($quiz, "What is the main focus of this course?");
        $this->createMultipleChoiceQuestion($quiz, "Which skill will you develop in this course?");

        // Create 2 true/false questions
        $this->createTrueFalseQuestion($quiz, "This course covers advanced topics in the field.", false);
        $this->createTrueFalseQuestion($quiz, "The course includes practical exercises.", true);
    }

    protected function createMultipleChoiceQuestion($quiz, $questionText)
    {
        $question = Question::create([
            "quiz_id" => $quiz->id,
            "question_text" => $questionText,
            "question_type" => "multiple_choice",
            "points" => 1
        ]);

        // Create 4 options with one correct answer
        $correctOption = rand(0, 3);
        for ($i = 0; $i < 4; $i++) {
            Option::create([
                "question_id" => $question->id,
                "option_text" => $this->generateOptionText($i, $i === $correctOption),
                "is_correct" => $i === $correctOption
            ]);
        }
    }

    protected function createTrueFalseQuestion($quiz, $questionText, $correctAnswer)
    {
        $question = Question::create([
            "quiz_id" => $quiz->id,
            "question_text" => $questionText,
            "question_type" => "true_false",
            "points" => 1
        ]);

        Option::create([
            "question_id" => $question->id,
            "option_text" => "True",
            "is_correct" => $correctAnswer
        ]);

        Option::create([
            "question_id" => $question->id,
            "option_text" => "False",
            "is_correct" => !$correctAnswer
        ]);
    }

    protected function generateOptionText($index, $isCorrect)
    {
        if ($isCorrect) {
            $correctOptions = [
                "The primary subject matter",
                "The core concept being taught",
                "The fundamental principle",
                "The main learning objective"
            ];
            return $correctOptions[$index % count($correctOptions)];
        }

        $incorrectOptions = [
            "An unrelated topic",
            "A secondary consideration",
            "A prerequisite requirement",
            "An advanced specialization"
        ];
        return $incorrectOptions[$index % count($incorrectOptions)];
    }
}
