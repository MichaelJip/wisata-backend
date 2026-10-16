<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'transaction_time' => $this->transaction_time,
            'total_price' => $this->total_price,
            'total_item' => $this->total_item,
            'payment_amount' => $this->payment_amount,
            'cashier_id' => $this->cashier_id,
            'cashier_name' => $this->cashier_name,
            'payment_method' => $this->payment_method,
        ];
    }
}
