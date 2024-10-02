<?php

    namespace App\Http\Resources;

    use Illuminate\Http\Request;
    use Illuminate\Http\Resources\Json\JsonResource;

    class PosPaymentResource extends JsonResource
    {
        public function toArray(Request $request) : array
        {
            return [
                'due_payment' => ( floatval($this->total) - floatval($this?->purchasePayment?->sum('amount')) ) ,
            ];
        }
    }
