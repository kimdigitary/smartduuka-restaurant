<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('item_ingredients', function (Blueprint $table) {
            $table->integer('quantity')->default(0);
            $table->bigInteger('buying_price')->default(0);
            $table->bigInteger('total')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('item_ingredients', function (Blueprint $table) {
            $table->dropColumn(['quantity', 'buying_price', 'total']);
        });
    }
};
