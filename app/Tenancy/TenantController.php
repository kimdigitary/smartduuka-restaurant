<?php

namespace App\Tenancy;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TenantController extends Controller
{
    public function index()
    {
        try {
            $tenants = Tenant::where('status', 'active')->get();
            return response()->json($tenants);
        } catch (Exception $exception) {
            Log::error('Error fetching tenants: ' . $exception->getMessage());
            return response()->json(['error' => 'Failed to fetch tenants'], 500);
        }
    }

    public function show($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            return response()->json($tenant);
        } catch (Exception $exception) {
            Log::error("Error fetching tenant with ID {$id}: " . $exception->getMessage());
            return response()->json(['error' => 'Tenant not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'tagline' => 'nullable|string|max:255',
            'status' => 'required|in:5,10',
            'website' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:tenants,username',
        ]);

        try {
            DB::transaction(function () use ($validatedData, $request, &$tenant) {
                $tenant = Tenant::create($validatedData);
                if ($request->logo) {
                    $tenant->addMedia($request->logo)->toMediaCollection('logo');
                }
            });
            return response()->json($tenant, 201);
        } catch (Exception $exception) {
            Log::error('Error creating tenant: ' . $exception->getMessage());
            return response()->json(['error' => 'Failed to create tenant'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'tagline' => 'nullable|string|max:255',
            'status' => 'required|in:5,10',
            'website' => 'nullable|string|max:255',
            'address' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:tenants,username,' . $id,
        ]);

        try {
            $tenant = Tenant::findOrFail($id);

            DB::transaction(function () use ($tenant, $validatedData) {
                $tenant->update($validatedData);
            });

            return response()->json($tenant);
        } catch (Exception $exception) {
            Log::error("Error updating tenant with ID {$id}: " . $exception->getMessage());
            return response()->json(['error' => 'Failed to update tenant'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);

            DB::transaction(function () use ($tenant) {
                $tenant->delete();
            });

            return response()->json(null, 204);
        } catch (Exception $exception) {
            Log::error("Error deleting tenant with ID {$id}: " . $exception->getMessage());
            return response()->json(['error' => 'Failed to delete tenant'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $tenant = Tenant::findOrFail($id);
            return response()->json($tenant);
        } catch (Exception $exception) {
            Log::error("Error fetching tenant with ID {$id}: " . $exception->getMessage());
            return response()->json(['error' => 'Tenant not found'], 404);
        }
    }
}