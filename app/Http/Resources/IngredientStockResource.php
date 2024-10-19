<?php

    namespace App\Http\Resources;

    use App\Libraries\AppLibrary;
    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class IngredientStockResource extends JsonResource
    {
        public function toArray(Request $request) : array
        {
            return [
                'id'             => $this->id ,
                'item_id'        => $this->item_id ,
                'price'          => $this->price ,
                'status'         => $this->status ,
                'price_currency' => AppLibrary::currencyAmountFormat($this->price) ,
                'quantity'       => number_format($this->quantity) ,
                'item'           => $this->item ,
            ];
        }
    }
