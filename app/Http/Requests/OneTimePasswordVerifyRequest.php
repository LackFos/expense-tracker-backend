<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OneTimePasswordVerifyRequest extends FormRequest
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
            'email' => 'required|email|exists:one_time_passwords,email',
            'otp' => 'required|string|min:6|max:6',
        ];
    }
}
