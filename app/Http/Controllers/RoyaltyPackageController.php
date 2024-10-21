<?php

    namespace App\Http\Controllers;

    use App\Http\Requests\StoreRoyaltyPackageRequest;
    use App\Http\Requests\UpdateRoyaltyPackageRequest;
    use App\Http\Resources\RoyaltyPackageResource;
    use App\Models\RoyaltyPackage;
    use Illuminate\Http\Request;

    class RoyaltyPackageController extends Controller
    {

        public function index()
        {
            return RoyaltyPackageResource::collection(RoyaltyPackage::all());
        }


        public function store(StoreRoyaltyPackageRequest $request)
        {
            return new RoyaltyPackageResource(RoyaltyPackage::create($request->validated()));
        }

        public function show(RoyaltyPackage $royaltyPackage)
        {
            return new RoyaltyPackageResource($royaltyPackage);
        }

        public function update(UpdateRoyaltyPackageRequest $request , RoyaltyPackage $royaltyPackage)
        {
            $royaltyPackage->update($request->validated());
            return new RoyaltyPackageResource($royaltyPackage);
        }

        public function destroy(RoyaltyPackage $royaltyPackage)
        {
            $royaltyPackage->delete();
            return response()->noContent();
        }
    }
