<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
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
            'amount' => 'required|integer',
            'description' => 'nullable|string',
            'date' => 'required|date'
        ];
    }

    /**
     * Get the validated data after the validation process.
     *
     * @return array
     */
    public function validatedWithUser(): array
    {
        $validatedData = $this->validated();
        $validatedData['user_id'] = auth()->id();
        return $validatedData;
    }
}
