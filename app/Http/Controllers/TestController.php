<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function seedDatabase()
    {
        try {
            DB::table('menus')->truncate();
            Artisan::call('db:seed', [
                '--class' => 'MenuTableSeeder'
            ]);
            return response()->json(['message' => 'Database seeded successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
