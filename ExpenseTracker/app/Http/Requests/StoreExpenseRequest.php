<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount'        => 'required|numeric|min:0.01',
            'description'   => 'required|string|max:500',
            'category'      => 'required|in:food,transportation,entertainment,health,shopping,utilities,other',
            'date'          => 'required|date|before_or_equal:today',
            'receipt_image' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min'           => 'Amount must be at least ₹0.01.',
            'date.before_or_equal' => 'You cannot log an expense for a future date.',
            'receipt_image.max'    => 'Receipt file must not exceed 2 MB.',
        ];
    }
}
