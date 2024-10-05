<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TenantFileTextGetRequest;
use App\Http\Requests\TenantRequest;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\TenantFileListResource;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TenantController extends AdminController
{
    private TenantService $tenantService;

    public function __construct(TenantService $tenantService)
    {
        parent::__construct();
        $this->tenantService = $tenantService;
        $this->middleware(['permission:settings'])->only('store', 'update', 'destroy');
    }

    public function index(PaginateRequest $request): \Illuminate\Http\Response|\Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return TenantResource::collection($this->tenantService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(TenantRequest $request): \Illuminate\Http\Response|TenantResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new TenantResource($this->tenantService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show($tenant): \Illuminate\Http\Response|TenantResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            return new TenantResource($this->tenantService->show($tenant));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(Request $request, Tenant $tenant): \Illuminate\Http\Response|TenantResource|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            \Log::info($tenant);
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'logo' => 'image|mimes:jpg,jpeg,png|max:2048',
                'tagline' => 'nullable|string|max:255',
                'status' => 'required|in:5,10',
                'website' => 'nullable|string|max:255',
                'address' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('tenants')->ignore($tenant), // Ignore current tenant during update
                ],
            ]);

            // Update the tenant
            $tenant->update($validatedData);

            // Handle the logo image if it's present
            if ($request->hasFile('logo')) {
                $tenant->clearMediaCollection('tenant');
                $tenant->addMediaFromRequest('logo')->toMediaCollection('tenant');
            }

            return new TenantResource($tenant);

        } catch (ValidationException $validationException) {
            // Return validation errors
            return response([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (Exception $exception) {
            // Handle other exceptions
            return response([
                'status' => false,
                'message' => $exception->getMessage()
            ], 422);
        }
    }

    public function destroy($tenant): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $this->tenantService->destroy($tenant);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

}
