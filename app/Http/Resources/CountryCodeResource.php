<?php

    namespace App\Http\Resources;

    use Illuminate\Http\Resources\Json\JsonResource;

    class CountryCodeResource extends JsonResource
    {


        public function toArray($request) : array
        {
            /*
             *    'country_code' => 'UGA',
                    'country_name' => 'Uganda (UGA)',
                    'calling_code' => '+256'
             */
            return [
//            "calling_code"  => $this->calling_codes[0] == '+1201' ? '+1' : $this->calling_codes[0],
                "calling_code"  => $this['calling_code'] ,
//                "flag_emoji"    => $this->flag->emoji ,
//                "flag_svg"      => $this->flag->svg ,
//                "flag_svg_path" => $this->flag->svg_path ,
//                "capital"       => $this->capital_rinvex ,
//                "nationality"   => $this->demonym ,
            ];

        }


//        private function getCallingCode()
//        {
//            if ( ! isset($this['calling_codes']) || ! is_array($this['calling_codes']) || empty($this['calling_codes']) ) {
//                return '+256';
//            }
//
//            $code = $this['calling_codes'][0];
//            return $code == '+1201' ? '+1' : $code;
//        }
    }
