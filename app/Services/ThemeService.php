<?php

namespace App\Services;


use App\Http\Requests\ThemeRequest;
use App\Models\ThemeSetting;
use App\Tenancy\Tenancy;
use Dipokhalder\EnvEditor\EnvEditor;
use Exception;
use Illuminate\Support\Facades\Log;
use Smartisan\Settings\Facades\Settings;

class ThemeService
{
    public $envService;

    public function __construct(EnvEditor $envEditor)
    {
        $this->envService = $envEditor;
    }

    /**
     * @throws Exception
     */
    public function list()
    {
        try {
            // Retrieve all settings for the tenant
            $settings = ThemeSetting::where('group', 'theme')->where('tenant_id', Tenancy::getTenantId())->get();
            \Log::info('Settings before: ' . json_encode($settings->toArray()));  // Log settings as JSON

            // Initialize the mapped settings array
            $mappedSettings = [
                'theme_logo' => null,
                'theme_favicon_logo' => null,
                'theme_footer_logo' => null,
            ];

            // Loop through each setting
            foreach ($settings as $setting) {
                $payload = json_decode($setting->payload, true); // Decode the payload

                if (isset($payload['$value'])) {
                    $value = $payload['$value'];

                    // Handle array values by logging and converting to JSON string
                    if (is_array($value)) {
                        \Log::warning('Array encountered for key: ' . $setting->key);
                        $value = json_encode($value);  // Convert the array to JSON
                    }

                    // Map the value to the corresponding key in the mappedSettings array
                    if (array_key_exists($setting->key, $mappedSettings)) {
                        $mappedSettings[$setting->key] = $value;
                    }
                }
            }

            // Log the final mapped settings
            \Log::info('Mapped Settings: ' . json_encode($mappedSettings));

            return $mappedSettings;  // Return the mapped settings
        } catch (Exception $exception) {
            // Log the error message
            \Log::error('Error processing theme settings: ' . $exception->getMessage());
            throw new Exception($exception->getMessage(), 422);
        }
    }


    /**
     * @throws Exception
     */
    public function updateOrInsert(ThemeRequest $request)
{
    try {
        // Retrieve the tenant ID from the request or context
        $tenantId = Tenancy::getTenantId(); // Adjust this based on your application context

        // Define the keys and media fields you want to handle
        $mediaFields = [
            'theme_logo' => 'theme-logo',
            'theme_favicon_logo' => 'theme-favicon-logo',
            'theme_footer_logo' => 'theme-footer-logo',
        ];

        foreach ($mediaFields as $field => $mediaCollection) {
            if ($request->$field) {
                // First, check if the setting exists
                $setting = ThemeSetting::where('key', $field)
                    ->where('tenant_id', $tenantId)
                    ->where('group', 'theme')
                    ->first();

                // Upload the media and clear existing media collection
                if ($setting) {
                    // Clear the existing media collection before adding new one
                    $setting->clearMediaCollection($mediaCollection);
                    // Add new media
                    $media = $setting->addMediaFromRequest($field)->toMediaCollection($mediaCollection);

                    // Construct the file path in the desired format
                    $filePath = "storage/{$media->id}/" . $media->file_name; // Adjust the format

                    // Update the existing setting's payload
                    $setting->update([
                        'payload' => json_encode(['$value' => $filePath]),
                    ]);
                } else {
                    // If the setting does not exist, create a new one
                    $setting = ThemeSetting::create([
                        'key' => $field,
                        'payload' => json_encode(['$value' => '']), // Placeholder, will be updated after upload
                        'tenant_id' => $tenantId,
                        'group' => 'theme',
                    ]);

                    // Upload the media to the new setting
                    $media = $setting->addMediaFromRequest($field)->toMediaCollection($mediaCollection);

                    // Construct the file path in the desired format
                    $filePath = "storage/{$media->id}/" . $media->file_name; // Adjust the format

                    // Update the new setting's payload with the correct file path
                    $setting->update([
                        'payload' => json_encode(['$value' => $filePath]),
                    ]);
                }
            }
        }

        return $this->list();
    } catch (Exception $exception) {
        Log::info($exception->getMessage());
        throw new Exception($exception->getMessage(), 422);
    }
}


}
