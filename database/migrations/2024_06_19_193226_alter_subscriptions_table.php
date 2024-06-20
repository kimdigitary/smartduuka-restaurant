<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('invoice')->after('user_id');
            $table->dateTime('starts_at')->after('expires_at');
            $table->integer('status')->after('expires_at');
        });
    }

    public function down(): void
    {
        //
    }
};
