<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'item_id'      => $this['item_id'],
            'product_name' => $this['product_name'],
            'status'       => $this['status'],
            'stock'        => $this['itemStock']
        ];
    }
}
