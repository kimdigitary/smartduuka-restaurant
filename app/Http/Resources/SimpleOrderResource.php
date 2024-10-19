<?php

    namespace App\Http\Resources;


    use App\Libraries\AppLibrary;
    use App\Models\Order;
    use Illuminate\Http\Resources\Json\JsonResource;

    class SimpleOrderResource extends JsonResource
    {
        /**
         * Transform the resource into an array.
         *
         * @param \Illuminate\Http\Request $request
         * @return array
         */
        public function toArray($request) : array
        {
            return [
                'id'                           => $this->id ,
                'order_serial_no'              => $this->order_serial_no ,
                'order_datetime'               => AppLibrary::datetime($this->order_datetime) ,
                "total_amount_price"           => AppLibrary::flatAmountFormat($this->total) ,
                "discount_amount_price"        => AppLibrary::flatAmountFormat($this->discount) ,
                "delivery_charge_amount_price" => AppLibrary::flatAmountFormat($this->delivery_charge) ,
                'payment_status'               => $this->payment_status ,
                'payment_method'               => Order::find($this->id)->paymentMethod ,
                'transaction'                  => new TransactionResource($this->transaction) ,
                'payment_methods'              => $this->paymentMethods
                    ->map(function ($paymentMethod) {
                        return $paymentMethod->paymenMethod ? ucfirst($paymentMethod->paymenMethod->name) : null;
                    })
                    ->filter()
                    ->unique()
                    ->implode(' and ') ,
            ];
        }
    }
