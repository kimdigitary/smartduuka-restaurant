<?php

    namespace App\Http\Resources;

    use App\Libraries\AppLibrary;
    use App\Models\PaymentMethod;
    use Illuminate\Http\Resources\Json\JsonResource;

    class PurchasePaymentResource extends JsonResource
    {
        public function toArray($request) : array
        {
            return [
                'id'             => $this->id ,
                'purchase_id'    => $this->purchase_id ,
                'date'           => $this->date ,
                'converted_date' => AppLibrary::datetime($this->date) ,
                'reference_no'   => $this->reference_no ,
                'payment_method' => PaymentMethod::find($this->payment_method)->name ,
                'amount'         => AppLibrary::flatAmountFormat($this->amount) ,
//            'file'           => $this->file,
            ];
        }
    }
