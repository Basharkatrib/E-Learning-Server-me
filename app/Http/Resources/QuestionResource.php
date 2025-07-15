<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "questionId" => $this->id,
            "quizId" => $this->quiz_id,
            "questionText" => $this->question_text,
            "questionType" => $this->question_type,
            "points" => $this->points,
            "options" => OptionResource::collection($this->whenLoaded("options")),
        ];
    }
}
