<?php

namespace Database\Seeders;


use App\Enums\Activity;
use App\Enums\CurrencyPosition;
use App\Models\Currency;
use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Smartisan\Settings\Facades\Settings;

class SiteTableSeeder extends Seeder
{
    public function run()
    {
        $envService = new EnvEditor();
        Settings::group('site')->set([
            'site_date_format'               => 'd-m-Y',
            'site_time_format'               => 'h:i A',
            'site_default_timezone'          => 'Africa/Kampala',
            'site_default_branch'            => 1,
            'site_default_currency'          => 1,
            'site_default_currency_symbol'   => 'UGX',
            'site_currency_position'         => CurrencyPosition::LEFT,
            'site_digit_after_decimal_point' => '0',
            'site_email_verification'        => Activity::ENABLE,
            'site_phone_verification'        => Activity::DISABLE,
            'site_default_language'          => 1,
            'site_google_map_key'            => $envService->getValue(
                'DEMO'
            ) ? 'Fake-map-key' : '',
            'site_copyright'                 => $envService->getValue(
                'DEMO'
            ) ? '© FoodScan by iNiLabs 2024, All Rights Reserved' : '',
            'site_language_switch'        => Activity::ENABLE,
            'site_app_debug'              => Activity::DISABLE,
            'site_auto_update'            => Activity::DISABLE,
            'site_online_payment_gateway' => $envService->getValue('DEMO') ? Activity::ENABLE : Activity::DISABLE,
            'site_default_sms_gateway'    => 0,
            'site_food_preparation_time'  => "30",
        ]);

        $envService->addData([
            'APP_DEBUG'              => 'false',
            'TIMEZONE'               => 'Africa/Kampala',
            'CURRENCY'               => 'UGX',
            'CURRENCY_SYMBOL'        => 'UGX',
            'CURRENCY_POSITION'      => '5',
            'CURRENCY_DECIMAL_POINT' => '0',
            'DATE_FORMAT'            => 'd-m-Y',
            'TIME_FORMAT'            => 'h:i A'
        ]);
        Artisan::call('optimize:clear');
    }
}
