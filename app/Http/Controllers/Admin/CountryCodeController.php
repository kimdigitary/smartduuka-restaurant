<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Services\CountryCodeService;
use App\Http\Resources\CountryCodeResource;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class CountryCodeController extends AdminController
{
    public CountryCodeService $countryCodeService;

    public function __construct(CountryCodeService $countryCodeService)
    {
        parent::__construct();
        $this->countryCodeService = $countryCodeService;
    }

    public function index() : Response | array | Application | ResponseFactory
    {
        try {
            return $this->countryCodeService->list();
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show($country) : Response | CountryCodeResource | Application | ResponseFactory
    {
        try {
            return new CountryCodeResource( $this->countryCodeService->show($country) );
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
