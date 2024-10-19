<?php

    namespace App\Http\Resources;


    use App\Enums\PaymentStatus;
    use App\Libraries\AppLibrary;
    use Illuminate\Http\Resources\Json\JsonResource;

    class OrderResource extends JsonResource
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
                'id'                             => $this->id ,
                'order_serial_no'                => $this->order_serial_no ,
                'user_id'                        => $this->user_id ,
                'dining_table'                   => $this->diningTable ,
                'created_by'                     => $this->creator ,
                'branch_id'                      => $this->branch_id ,
                'change'                         => $this->change ,
                'paid'                           => $this->paid ,
                'change_currency'                => AppLibrary::currencyAmountFormat($this->change) ,
                'paid_currency'                  => AppLibrary::currencyAmountFormat($this->paid) ,
                'branch_name'                    => optional($this->branch)->name ,
                'order_items'                    => optional($this->orderItems)->count() ,
                "total_currency_price"           => AppLibrary::currencyAmountFormat($this->total) ,
                "total_tax_currency_price"       => AppLibrary::currencyAmountFormat($this->total_tax) ,
                "total_amount_price"             => AppLibrary::flatAmountFormat($this->total) ,
                "total_amount_price_currency"    => AppLibrary::currencyAmountFormat(AppLibrary::flatAmountFormat($this->total)) ,
                "discount_currency_price"        => AppLibrary::currencyAmountFormat($this->discount) ,
                "delivery_charge_currency_price" => AppLibrary::currencyAmountFormat($this->delivery_charge) ,
//                'payment_method'                 => $this->payment_method ,
                'payment_method'                 => $this->paymentMethod ,
                'payment_methods'                => $this->paymentMethods
                    ->map(function ($paymentMethod) {
                        return $paymentMethod->paymenMethod ? ucfirst($paymentMethod->paymenMethod->name) : null;
                    })
                    ->filter()
                    ->unique()
                    ->implode(' and ') ,
//                'payment_status'                 => $this->total == $this->paid ? Paymentstatus::PAID : Paymentstatus::PARTIALLY_PAID ,
                'payment_status'                 => $this->paymentStatus() ,
                'preparation_time'               => $this->preparation_time ,
                'order_type'                     => $this->order_type ,
                'order_datetime'                 => AppLibrary::datetime($this->order_datetime) ,
                'status'                         => $this->status ,
                'is_advance_order'               => $this->is_advance_order ,
                'status_name'                    => trans('orderStatus.' . $this->status) ,
                'customer'                       => new OrderUserResource($this->user) ,
                'transaction'                    => new TransactionResource($this->transaction) ,
                'orderItems'                     => $this->orderItems ,
                'order_notification_audio'       => asset('/audio/order_notification.mp3') ,
            ];
        }

        public function paymentStatus()
        {
            if ( $this->total == $this->paid ) {
                return Paymentstatus::PAID;
            } else if ( $this->paid == null ) {
                return Paymentstatus::UNPAID;
            } else {
                return Paymentstatus::PARTIALLY_PAID;
            }
        }
    }
