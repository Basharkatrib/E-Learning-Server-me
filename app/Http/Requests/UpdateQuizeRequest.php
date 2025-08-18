<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuizeRequest extends FormRequest
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
            "title" => ["sometimes", "string", "max:255"],
            "description" => ["nullable", "string"],
            "time_limit" => ["nullable", "integer", "min:1"],
            "passing_score" => ["nullable", "integer", "min:0"],
            "is_published" => ["sometimes", "boolean"],
        ];
    }
}
