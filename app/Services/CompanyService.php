<?php

namespace App\Services;


use App\Http\Requests\CompanyRequest;
use App\Models\ThemeSetting;
use App\Tenancy\Tenancy;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class CompanyService
{

    public $envService;

    public function __construct()
    {
        $this->envService = new EnvEditor();
    }

    /**
     * @throws Exception
     */
    public function list(): array
    {
        try {
            \Log::info(Tenancy::getTenantId());
            $settings = ThemeSetting::where('group', 'company')->where('tenant_id', Tenancy::getTenantId())->get();

            $mappedSettings = [
                'company_name' => null,
                'company_email' => null,
                'company_phone' => null,
                'company_website' => null,
                'company_city' => null,
                'company_state' => null,
                'company_country_code' => null,
                'company_zip_code' => null,
                'company_address' => null,
            ];

            foreach ($settings as $setting) {
                $payload = json_decode($setting->payload, true);

                if (isset($payload['$value'])) {
                    $value = $payload['$value'];

                    if (is_array($value)) {
                        Log::warning('Array encountered for key: ' . $setting->key);
                        $value = json_encode($value); // Or handle arrays differently
                    }

                    if (array_key_exists($setting->key, $mappedSettings)) {
                        $mappedSettings[$setting->key] = $value;
                    }
                }
            }

            return $mappedSettings;
        } catch (Exception $exception) {
            Log::error('Error listing company settings: ' . $exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }



    public function updateOrInsert(CompanyRequest $request)
    {
        try {
            // Get tenant ID from the Tenancy system
            $tenantId = Tenancy::getTenantId();

            // Validate incoming request data
            $validatedData = $request->validated();

            // Loop through each setting to update or add
            foreach ($validatedData as $key => $value) {
                // Check if the setting exists for the current tenant
                $setting = ThemeSetting::where('group', 'company')
                    ->where('key', $key)
                    ->where('tenant_id', $tenantId)
                    ->first();

                $payload = json_encode(['$value' => $value, '$cast' => null]);

                if ($setting) {
                    // If the setting exists, update it
                    $setting->update(['payload' => $payload]);
                } else {
                    // If the setting does not exist, create a new one
                    ThemeSetting::create([
                        'group' => 'company',
                        'key' => $key,
                        'payload' => $payload,
                        'tenant_id' => $tenantId,
                        'settingable_type' => null, // Set based on your requirement
                        'settingable_id' => null, // Set based on your requirement
                    ]);
                }
            }

            // Optionally update environment settings
            $this->envService->addData(['APP_NAME' => $request->company_name]);

            // Clear any cached configuration
            Artisan::call('optimize:clear');

            // Return updated settings list
            return $this->list();
        } catch (Exception $exception) {
            Log::error('Error updating company settings: ' . $exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }
}