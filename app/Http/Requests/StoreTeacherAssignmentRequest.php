<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherAssignmentRequest extends FormRequest
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
            "teacher_id" => "required|integer|exists:teachers,id",
            "stream_id" => "required|integer|exists:streams,id",
            "section_id" => "required|integer|exists:sections,id",
            "courses_id" => "required|integer|exists:courses,id",
        ];
    }
}
