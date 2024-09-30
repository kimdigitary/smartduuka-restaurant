<?php

namespace App\Services;


use App\Http\Requests\TenantFileTextGetRequest;
use App\Libraries\AppLibrary;
use Exception;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TenantRequest;
use App\Http\Requests\PaginateRequest;
use Smartisan\Settings\Facades\Settings;


class TenantService
{
    protected $tenantFilter = [
        'name',
        'phone',
        'email',
        'logo',
        'tagline',
        'status',
        'website',
        'address',
        'username'
    ];
    /**
     * @throws Exception
     */
    public function list(PaginateRequest $request)
    {
        try {
            $requests    = $request->all();
            $method      = $request->get('paginate', 0) == 1 ? 'paginate' : 'get';
            $methodValue = $request->get('paginate', 0) == 1 ? $request->get('per_page', 10) : '*';
            $orderColumn = $request->get('order_column') ?? 'id';
            $orderType   = $request->get('order_type') ?? 'desc';

            return Tenant::where(function ($query) use ($requests) {
                foreach ($requests as $key => $request) {
                    if (in_array($key, $this->tenantFilter)) {
                        $query->where($key, 'like', '%' . $request . '%');
                    }
                }
            })->orderBy($orderColumn, $orderType)->$method(
                $methodValue
            );
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function store(TenantRequest $request)
    {
        try {
            $tenant = Tenant::create($request->validated());
            if ($request->logo) {
                $tenant->addMediaFromRequest('logo')->toMediaCollection('tenant');
            }

            return $tenant;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function update(Request $request, $tenant): Tenant
    {
        try {
            $tenant->update($request->validated());
            if ($request->image) {
                $tenant->clearMediaCollection('tenant');
                $tenant->addMediaFromRequest('image')->toMediaCollection('tenant');
            }
            return $tenant;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function destroy($tenant): void
    {
        try {
            Tenant::find($tenant)->delete();
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

    /**
     * @throws Exception
     */
    public function show($tenant): Tenant
    {
        try {
            $tenant = Tenant::findOrFail($tenant);
            \Log::info('Tenant from service: ' . $tenant);
            return $tenant;
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }

}