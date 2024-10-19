<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() : void
        {
            Schema::table('variation_ingredients' , function (Blueprint $table) {
                $table->decimal('quantity')->change();
            });
            Schema::table('item_ingredients' , function (Blueprint $table) {
                $table->decimal('quantity')->change();
            });
        }

        public function down() : void
        {
            Schema::table('variation_ingredients' , function (Blueprint $table) {
                $table->integer('quantity')->change();
            });
            Schema::table('item_ingredients' , function (Blueprint $table) {
                $table->integer('quantity')->change();
            });
        }
    };
