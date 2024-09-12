<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CountryCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "calling_code"  => $this->getCallingCode(),
            "flag_emoji"    => $this['flag']['emoji'] ?? null,
            "flag_svg"      => $this['flag']['svg'] ?? null,
            "flag_svg_path" => $this['flag']['svg_path'] ?? null,
            "capital"       => $this['capital_rinvex'] ?? null,
            "nationality"   => $this['demonym'] ?? null,
        ];
    }

    /**
     * Get the calling code with fallback.
     *
     * @return string|null
     */
    private function getCallingCode()
    {
        if (!isset($this['calling_codes']) || !is_array($this['calling_codes']) || empty($this['calling_codes'])) {
            return '+256';
        }

        $code = $this['calling_codes'][0];
        return $code == '+1201' ? '+1' : $code;
    }
}