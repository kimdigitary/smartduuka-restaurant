<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMeatPriceRequest;
use App\Http\Requests\UpdateMeatPriceRequest;
use App\Models\MeatPrice;

class MeatPriceController extends Controller
{
    public function index()
    {

    }

    public function store(StoreMeatPriceRequest $request)
    {
        //
    }

    public function show(MeatPrice $meatPrice)
    {
        //
    }

    public function update(UpdateMeatPriceRequest $request, MeatPrice $meatPrice)
    {
        //
    }

    public function destroy(MeatPrice $meatPrice)
    {
        //
    }
}
