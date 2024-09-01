<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('item_variations', function (Blueprint $table) {
            $table->decimal('overall_cost', 10)->default(0);
        });
    }
    public function down()
    {
        Schema::table('item_variations', function (Blueprint $table) {
            $table->dropColumn('overall_cost');
        });
    }
};
