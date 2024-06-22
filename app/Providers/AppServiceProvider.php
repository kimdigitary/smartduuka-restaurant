<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\ServiceProvider;

class

AppServiceProvider extends ServiceProvider
{

    public function register()
    {

    }

    public function boot()
    {
        Menu::truncate();
        Artisan::call('db:seed --class=MenuTableSeeder');
    }
}
