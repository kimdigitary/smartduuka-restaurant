<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            // DB::statement('ALTER TABLE branches MODIFY city VARCHAR(255) NULL');
            // DB::statement('ALTER TABLE branches MODIFY state VARCHAR(255) NULL');
            // DB::statement('ALTER TABLE branches MODIFY zip_code VARCHAR(255) NULL');
        });
    }


    public function down()
    {
        //
    }
};