<?php

namespace App\Services;

use App\Models\ThemeSetting;

class SettingService
{
    public function list(): array
    {
        $groups = [
            'company',
            'site',
            'theme',
            'otp',
            'social_media',
            'notification',
            'order_setup'
        ];

        $settings = [];

        foreach ($groups as $group) {
            $groupSettings = ThemeSetting::where('group', $group)
                ->where('tenant_id', \App\Tenancy\Tenancy::getTenantId())
                ->get();

            foreach ($groupSettings as $setting) {
                $settings[$setting->key] = $setting->value;
            }
        }

        return $settings;
    }
}