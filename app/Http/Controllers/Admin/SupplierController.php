<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PaginateRequest;
use App\Http\Requests\SupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use App\Services\SupplierService;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class SupplierController extends AdminController
{
    private SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        parent::__construct();
        $this->supplierService = $supplierService;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy', 'show');
    }

    public function index(PaginateRequest $request): Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | Application | ResponseFactory
    {
        try {
            return SupplierResource::collection($this->supplierService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(SupplierRequest $request): Response | SupplierResource | Application | ResponseFactory
    {
        try {
            return new SupplierResource($this->supplierService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(SupplierRequest $request, Supplier $supplier): Response | SupplierResource | Application | ResponseFactory
    {
        try {
            return new SupplierResource($this->supplierService->update($request, $supplier));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Supplier $supplier): Response | Application | ResponseFactory
    {
        try {
            $this->supplierService->destroy($supplier);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Supplier $supplier): Response | SupplierResource | Application | ResponseFactory
    {
        try {
            return new SupplierResource($this->supplierService->show($supplier));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}
