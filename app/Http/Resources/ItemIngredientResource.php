<?php

namespace App\Http\Resources;


use App\Enums\Status;
use App\Libraries\AppLibrary;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\isNull;

class ItemIngredientResource extends JsonResource
{

    public $variation = 0;
    public function toArray($request)
    {
        return [
            'id'                             => $this->id,
            'name'                           => $this->name,
            'buying_price'                   => $this->buying_price,
            'unit'                           => $this->unit,
            'quantity'                       => $this->quantity,
            'quantity_alert'                 => $this->quantity_alert,
            'status'                         => $this->status,
        ];
    }

    private function variationTotal()
    {
        $variationArray = $this->addonItem?->variations?->mapWithKeys(function ($variation) {
            return [$variation->id => $variation];
        });
        if ($this->addon_item_variation) {
            $variations = (object) json_decode($this->addon_item_variation, true);
            $price      = 0;
            $name       = [];
            foreach ($variations as $variation) {
                if (isset($variationArray[$variation])) {
                    $name[] = [
                        'id'             => $variationArray[$variation]->id,
                        'name'           => $variationArray[$variation]->name,
                        'attribute_name' => $variationArray[$variation]->itemAttribute->name
                    ];
                    $price  += $variationArray[$variation]->price;
                }
            }
            return (object)[
                'price' => $price,
                'name'  => $name
            ];
        }
    }
}
