<?php

namespace App\Http\Requests\API\V1\Fee;

use Illuminate\Foundation\Http\FormRequest;

class CalculateUserFeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'is_maker' => 'sometimes|boolean'
        ];
    }
} 