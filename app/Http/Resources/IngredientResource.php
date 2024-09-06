<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id"             => $this->id,
            "name"           => $this->name,
            "buying_price"   => $this->buying_price,
            "unit"           => $this->unit,
            "quantity"       => $this->quantity,
            "quantity_alert" => $this->quantity_alert,
            "pivot"          => $this->pivot,
        ];
    }
}
