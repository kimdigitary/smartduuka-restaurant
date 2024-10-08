<?php

namespace Database\Seeders;


use Dipokhalder\EnvEditor\EnvEditor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Smartisan\Settings\Facades\Settings;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::group('company')->set([
            'company_name'         => 'Smart Restaurant',
            'company_email'        => 'info@smartduuka.com',
            'company_phone'        => '704316255',
            'company_website'      => 'smartduuka.com',
            'company_city'         => 'Kampala',
            'company_state'        => 'Kampala',
            'company_country_code' => 'UGA',
            'company_zip_code'     => '00256',
            'company_address'      => 'Uganda, Kampala',
        ]);

        $envService = new EnvEditor();
        $envService->addData([
            'APP_NAME' => "Smart Restaurant"
        ]);
        Artisan::call('optimize:clear');
    }
}
