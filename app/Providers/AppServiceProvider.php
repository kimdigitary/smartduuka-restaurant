<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class

AppServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
//        info(env('DB_DATABASE'));
//        Menu::truncate();

//        Artisan::call('db:seed --class=MenuTableSeeder');
    }
}
