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
                'name'       => 'Smart Duuka',
                'phone'       => '773034311',
                'logo'   => 'logo.png',
            ],
            [
                'name'       => 'Smart Khan',
                'phone'       => '773034312',
                'logo'   => 'logo.png',
            ],
            [
                'name'       => 'Smart Mohammadpur',
                'phone'       => '773034313',
                'logo'   => 'logo.png',
            ],
            [
                'name'       => 'Smart Dhaka',
                'phone'       => '773034314',
                'logo'   => 'logo.png',
            ],
            [
                'name'       => 'Smart Chittagong',
                'phone'       => '773034315',
                'logo'   => 'logo.png',
            ]
        ]);
    }
}