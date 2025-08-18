<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "quizId" => $this->id,
            "courseId" => $this->course_id,
            "title" => $this->title,
            "description" => $this->description,
            "timeLimit" => $this->time_limit,
            "passingScore" => $this->passing_score,
            "isPublished" => $this->is_published,
            "questions" => QuestionResource::collection($this->whenLoaded("questions")),
        ];
    }
}
