<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => ["required", "string", "max:255"],
            "description" => ["nullable", "string"],
            "category_id" => ["required", "exists:cayegories,id"],
            "duration" => ["nullable", "string"],
            "difficulty_level" => [
                "required",
                Rule::in(["beginner", "intermediate", "advanced"])
            ],
            "thumbnail" => ["nullable", "image", "max:2048"], //max size 2MB
            "default_language" => ["required", "string", "max:50"],
            "skills" => ["nullable", "array"],
            "skills.*" => ["string", "max:250"]
        ];
    }
}
