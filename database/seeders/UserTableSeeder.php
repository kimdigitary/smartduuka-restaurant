<?php

namespace Database\Seeders;

use App\Enums\Ask;
use App\Enums\Role as EnumRole;
use App\Enums\Status;
use App\Models\User;
use Illuminate\Database\Seeder;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name'              => 'John Doe',
            'email'             => 'admin@gmail.com',
            'phone'             => '1254875855',
            'username'          => 'admin',
            'email_verified_at' => now(),
            'password'          => bcrypt('123456'),
            'branch_id'         => 0,
            'status'            => Status::ACTIVE,
            'country_code'      => '+256',
            'is_guest'          => Ask::NO
        ]);
        $admin->assignRole(EnumRole::ADMIN);
    }
}
