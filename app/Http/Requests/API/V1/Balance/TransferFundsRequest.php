<?php

namespace App\Http\Requests\API\V1\Balance;

use Illuminate\Foundation\Http\FormRequest;

class TransferFundsRequest extends FormRequest
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
            'to_user_id' => 'required|integer|exists:users,id|different:from_user_id',
            'coin_id' => 'required|integer|exists:coins,id',
            'amount' => 'required|numeric|min:0.000001'
        ];
    }
} 