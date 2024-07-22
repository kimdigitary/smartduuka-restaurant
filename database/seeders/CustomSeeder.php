<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomSeeder extends Seeder
{
    public function run()
    {
//        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
//        DB::table('menus')->truncate();
//        DB::table('permissions')->truncate();
        $this->call(MenuTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
//        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
