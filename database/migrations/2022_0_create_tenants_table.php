<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->tinyInteger('status')->default(\App\Enums\Status::ACTIVE)->comment(\App\Enums\Status::ACTIVE . '=' . \App\Enums\Status::ACTIVE . ', ' . \App\Enums\Status::INACTIVE . '=' . \App\Enums\Status::INACTIVE);
            $table->string('username')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tenants');
    }
};