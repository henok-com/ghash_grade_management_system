<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStreamRequest extends FormRequest
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
            "department_name" => "required|string|exists:departments,department_name",
            "stream_name" => "required|string",
            "maximum_students_in_stream" => "required|integer",
            "maximum_students_in_section" => "required|integer",
            "current_students_amount" => "required|integer",
        ];
    }
}
