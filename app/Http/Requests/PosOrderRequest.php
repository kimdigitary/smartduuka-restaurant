<?php

    namespace App\Http\Requests;

    use App\Enums\Activity;
    use App\Enums\OrderType;
    use App\Rules\ValidJsonOrder;
    use Illuminate\Foundation\Http\FormRequest;
    use Smartisan\Settings\Facades\Settings;

    class PosOrderRequest extends FormRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize() : bool
        {
            return true;
        }

        public function rules() : array
        {
            return [
                'customer_id'      => [ 'required' , 'numeric' ] ,
                'dining_table_id'  => project() === 'mophyz' ? [ 'required' , 'numeric' ] : [ 'nullable' ] ,
                'branch_id'        => [ 'required' , 'numeric' ] ,
                'subtotal'         => [ 'required' , 'numeric' ] ,
                'discount'         => [ 'nullable' , 'numeric' ] ,
                'delivery_charge'  => request('order_type') === OrderType::DELIVERY ? [
                    'required' ,
                    'numeric'
                ] : [ 'nullable' ] ,
                'total'            => [ 'required' , 'numeric' ] ,
                'order_type'       => [ 'required' , 'numeric' ] ,
                'is_advance_order' => [ 'required' , 'numeric' ] ,
                'address_id'       => request('order_type') === OrderType::DELIVERY ? [
                    'required' ,
                    'numeric'
                ] : [ 'nullable' ] ,
                'delivery_time'    => request('order_type') === OrderType::DELIVERY ? [
                    'required' ,
                    'string'
                ] : [ 'nullable' ] ,
                'source'           => [ 'required' , 'numeric' ] ,
                'items'            => [ 'required' , 'json' , new ValidJsonOrder ]
            ];
        }

        public function withValidator($validator)
        {
            $validator->after(function ($validator) {
                if ( request('order_type') == OrderType::DELIVERY && Settings::group('order_setup')->get("order_setup_delivery") == Activity::DISABLE ) {
                    $validator->errors()->add('order_type' , 'This order type is disabled now you can try another order type right now or call the management.');
                } else if ( request('order_type') == OrderType::TAKEAWAY && Settings::group('order_setup')->get("order_setup_takeaway") == Activity::DISABLE ) {
                    $validator->errors()->add('order_type' , 'This order type is disabled now you can try another order type right now or call the management.');
                } else if ( blank(request('order_type')) ) {
                    $validator->errors()->add('order_type' , 'This order type is disabled now you can try another order type right now or call the management.');
                }
            });
        }
    }
