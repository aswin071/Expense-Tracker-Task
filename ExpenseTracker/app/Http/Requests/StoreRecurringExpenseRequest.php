<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecurringExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'       => 'required|numeric|min:0.01',
            'description'  => 'required|string|max:500',
            'category'     => 'required|in:food,transportation,entertainment,health,shopping,utilities,other',
            'day_of_month' => 'required|integer|min:1|max:28',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'       => 'Amount must be at least ₹0.01.',
            'day_of_month.min' => 'Day must be between 1 and 28.',
            'day_of_month.max' => 'Day must be between 1 and 28 to work in every month.',
        ];
    }
}
