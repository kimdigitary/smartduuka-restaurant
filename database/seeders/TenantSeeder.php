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
                'email'      => 'smartduuka@example.com',
                'status'     => 5,
                'username'   => 'foodscan',
            ],
        ]);
    }
}