<?php

namespace App\Http\Requests;

use App\Enums\Ask;
use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $paymentMethod = $this->input('payment_method');
        $addPayment = $this->input('add_payment');
        return [
            'supplier_id'    => ['required', 'not_in:0', 'not_in:null'],
            'date'           => ['required', 'string'],
            'status'         => ['required', 'not_in:0', 'not_in:null'],
            'total'          => ['required', 'numeric'],
            'file'           => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'note'           => ['nullable', 'string', 'max:1000'],
            'products'       => ['required', 'json'],
            'amount'         => 'required_if:add_payment,' . Ask::YES,
            'payment_date'   => 'required_if:add_payment,' . Ask::YES,
            'payment_method' => 'required_if:add_payment,' . Ask::YES,
            'reference_no'   => [
                'sometimes',
                function ($attribute, $value, $fail) use ($paymentMethod, $addPayment) {
                    if ($addPayment == Ask::YES && $paymentMethod != 1) {
                        if (empty($value)) {
                            $fail('The ' . $attribute . 'required');
                        }
                    }
                }
            ],
        ];
    }

    public function messages()
    {
        return [
            'amount.required_if'         => 'Amount is required when add payment is yes.',
            'payment_date.required_if'   => 'Date is required when add payment is yes.',
            'payment_method.required_if' => 'Payment method is required when add payment is yes.',
            'reference_no.required_if'   => 'Reference number  is required when payment method is not cash  add payment is yes.',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $status = false;
            $message = '';
            $products = json_decode($this->products, true);
            if (is_array($products) && count($products)) {
                foreach ($products as $product) {
                    if ($product['quantity'] < 1 || !is_numeric($product['quantity']) || !is_int((int)$product['quantity'])) {
                        $status = true;
                        $message = trans('all.message.product_quantity_invalid');
                    } else if (!is_numeric($product['price']) || !is_double((float)$product['price']) || $product['price'] == 0 || $product['price'] < 0) {
                        $status = true;
                        $message = trans('all.message.product_price_invalid');
                    } else if (!is_numeric($product['total']) || !is_double((float)$product['total'])) {
                        $status = true;
                        $message = trans('all.message.product_price_total_invalid');
                    }
                }
            } else {
                $validator->errors()->add('products', trans('all.message.product_invalid'));
            }

            if ($status) {
                $validator->errors()->add('global', $message);
            }
        });
    }
}
