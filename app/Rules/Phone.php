<?php

    namespace App\Rules;

    use Illuminate\Contracts\Validation\Rule;

    class Phone implements Rule
    {
        public function __construct()
        {
            //
        }

        public function passes($attribute, $value) : bool
        {
            return preg_match('/^(25678\d{7}|25677\d{7}|25676\d{7}|25675\d{7}|25674\d{7}|25670\d{7}|25673\d{7}|2563\d{8})$/', $value);
        }

        public function message() : string
        {
            return 'Invalid phone number.';
        }
    }
