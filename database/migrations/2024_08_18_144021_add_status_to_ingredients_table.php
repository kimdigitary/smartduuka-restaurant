<?php

use App\Enums\Status;
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
        Schema::table('ingredients', function (Blueprint $table) {
            $table->tinyInteger('status')->default(Status::ACTIVE)->comment(Status::ACTIVE . '=' . trans('statuse.' . Status::ACTIVE) . ', ' . Status::INACTIVE . '=' . trans('statuse.' . Status::INACTIVE));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
