<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


enum LedgerType: string
{
    case EXPENSE = 'expense';
    case INCOME = 'income';
}

class LedgerUpdateRequest extends FormRequest
{
    public User $user;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        if($user) {
            $this->user = $user;
            return true;
        } else {
            return false;
        }
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
            'type' => ['required', Rule::enum(LedgerType::class)],
            'date' => 'required|date'
        ];
    }
}
