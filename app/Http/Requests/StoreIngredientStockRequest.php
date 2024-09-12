<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class StoreIngredientStockRequest extends FormRequest
    {

        public function authorize() : bool
        {
            return true;
        }

        public function rules() : array
        {
            return [
                'name'           => [ 'required' , 'string' ] ,
                'quantity'       => [ 'required' , 'numeric' ] ,
                'buying_price'   => [ 'required' , 'numeric' ] ,
                'quantity_alert' => [ 'required' , 'numeric' ] ,
            ];
        }
    }
