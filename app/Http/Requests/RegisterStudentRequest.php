<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterStudentRequest extends FormRequest
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
            "first_name" => "required|string|max:100",
            "middle_name" => "required|string|max:100",
            "last_name" => "required|string|max:100",
            "age" => "required|integer|max:100|min:15",
            "gender" => "required|string|max:10",
            "address" => "required|string",
            "email" => "required|email:rfc|unique:App\Models\User,email",
            "phone_number" => "required|string",
            "department_name" => "required|string",
            "stream_name" => "required|string|exists:streams,stream_name",
            "level" => "required|integer",
            "current_level" => "required|integer",
        ];
    }
}
