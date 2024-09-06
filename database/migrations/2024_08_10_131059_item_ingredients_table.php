<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('item_ingredients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('ingredient_id')->constrained('ingredients');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_ingredients');
    }
};
