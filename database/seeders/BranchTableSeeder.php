<?php

namespace Database\Seeders;

use Dipokhalder\EnvEditor\EnvEditor;
use App\Enums\Status;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name'      => 'Main Branch',
            'email'     => 'branch@smartduuka.com',
            'phone'     => '0788948653',
            'latitude'  => 23.8042375,
            'longitude' => 90.3525979,
            'city'      => 'Kampala',
            'state'     => 'Kampala',
            'zip_code'  => '00256',
            'address'   => 'Uganda, Kampala, Plot 1, Kampala Road',
            'status'    => Status::ACTIVE,
        ]);

//        $envService = new EnvEditor();
//        if ($envService->getValue('DEMO')) {
//            Branch::create([
//                'name'      => 'Gulshan-1',
//                'email'     => 'gulshan@inilabs.xyz',
//                'phone'     => '+1243535366',
//                'latitude'  => 23.7948597,
//                'longitude' => 90.4083123,
//                'city'      => 'Gulshan-1',
//                'state'     => 'Dhaka',
//                'zip_code'  => '1212',
//                'address'   => '1st floor, Adam Building, House: 41 Road: 52, Dhaka 1212',
//                'status'    => Status::ACTIVE,
//            ]);
//        }
    }
}
