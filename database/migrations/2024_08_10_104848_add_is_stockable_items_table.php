<?php

use App\Enums\Ask;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->integer('is_stockable')->default(Ask::NO);
            $table->unsignedDecimal('buying_price')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('is_stockable');
            $table->dropColumn('buying_price');
        });
    }
};
