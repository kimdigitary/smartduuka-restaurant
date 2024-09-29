<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meat_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('adults');
            $table->unsignedInteger('five_to_nine');
            $table->unsignedInteger('less_than_five');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('meat_prices');
    }
};
