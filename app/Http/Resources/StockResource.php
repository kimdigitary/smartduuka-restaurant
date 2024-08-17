<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */

    public function toArray($request)
    {

        return [
            'item_id'         => $this['item_id'],
            'product_name'    => $this['item']['name'],
            'status'          => $this['status'],
            'stock'           => $this['quantity']
        ];
    }
}
