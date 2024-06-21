<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('expense_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('status')->nullable()->default(0);
        });
    }

    public function down(): void
    {
        //
    }
};
