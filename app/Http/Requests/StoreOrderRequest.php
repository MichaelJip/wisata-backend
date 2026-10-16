<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_time' => ['required', 'string'],
            'total_price' => ['required', 'integer'],
            'total_item' => ['required', 'integer'],
            'payment_amount' => ['required', 'integer'],
            'cashier_id' => ['required', 'integer'],
            'cashier_name' => ['required', 'string'],
            'payment_method' => ['required', 'string'],
            'order_items' => ['required', 'array', 'min:1'],
            'order_items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'order_items.*.quantity' => ['required', 'integer', 'min:1'],
            'order_items.*.total_price' => ['required', 'integer'],
        ];
    }
}
