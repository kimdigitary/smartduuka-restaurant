<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tenant::insert([
            [
                'name'       => 'Food Scan Default',
                'phone'      => '773034311',
                'email'      => 'smartduuka@example.com',
                'logo'       => 'logo.png',
                'tagline'    => 'Your smart choice',
                'status'     => 5,
                'website'    => 'http://smartduuka.com',
                'address'    => '123 Smart Street, Duuka',
                'username'   => 'foodscan',
            ],
        ]);
    }
}
