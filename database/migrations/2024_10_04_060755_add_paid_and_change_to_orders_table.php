<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() : void
        {
            Schema::table('orders' , function (Blueprint $table) {
                $table->integer('paid')->nullable();
                $table->integer('change')->nullable();
            });
        }

        public function down() : void
        {
            Schema::table('orders' , function (Blueprint $table) {
                $table->dropColumn('paid');
                $table->dropColumn('change');
            });
        }
    };
