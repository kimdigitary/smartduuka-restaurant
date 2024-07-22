<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class CountryCodeService
{
    private $countries;

    public function __construct()
    {
        $this->countries = $this->getCountriesList();
    }

    /**
     * @throws Exception
     */
    public function list(): array
    {
        try {
            return ['data' => $this->countries];
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show($country)
    {
        try {
            $countryData = collect($this->countries)->firstWhere('country_code', $country);
            if (!$countryData) {
                throw new Exception("Country not found", 404);
            }
            return $countryData;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), $exception->getCode() ?: 422);
        }
    }

    private function getCountriesList(): array
    {
        // The provided list of countries goes here
        return [
                [
                    "country_code" => "UGA",
                    "country_name" => "Uganda (UGA)",
                    "calling_code" => "+256"
                ],
                [
                    "country_code" => "KEN",
                    "country_name" => "Kenya (KEN)",
                    "calling_code" => "+254"
                ],
                [
                    "country_code" => "TZA",
                    "country_name" => "Tanzania (TZA)",
                    "calling_code" => "+255"
                ],
                [
                    "country_code" => "RWA",
                    "country_name" => "Rwanda (RWA)",
                    "calling_code" => "+250"
                ],
                [
                    "country_code" => "ZAF",
                    "country_name" => "South Africa (ZAF)",
                    "calling_code" => "+27"
                ],
                [
                    "country_code" => "NGA",
                    "country_name" => "Nigeria (NGA)",
                    "calling_code" => "+234"
                ],
                [
                    "country_code" => "GHA",
                    "country_name" => "Ghana (GHA)",
                    "calling_code" => "+233"
                ],
                [
                    "country_code" => "EGY",
                    "country_name" => "Egypt (EGY)",
                    "calling_code" => "+20"
                ],
                [
                    "country_code" => "DZA",
                    "country_name" => "Algeria (DZA)",
                    "calling_code" => "+213"
                ],
                [
                    "country_code" => "ETH",
                    "country_name" => "Ethiopia (ETH)",
                    "calling_code" => "+251"
                ]
            ];

    }
}