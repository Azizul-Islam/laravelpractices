<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
            'order_number' => $this->order_number,
            'total_amount' => $this->total_amount,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'order_status' => $this->order_status,
            'created_at' => $this->created_at,
            'customer' => $this->customer,
            'orderProducts' => $this->orderProducts->map(function($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product?->name,
                    'price' => $item->price,
                    'qty' => $item->qty
                ];
            })
        ];
    }
}
