<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up()
        {
            Schema::table('ingredients' , function (Blueprint $table) {
                $table->string('buying_price')->nullable()->change();
                $table->string('quantity')->nullable()->change();
                $table->string('quantity_alert')->nullable()->change();
            });
        }

        public function down()
        {
            //
        }
    };
