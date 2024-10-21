<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {

        public function up() : void
        {
            Schema::table('users' , function (Blueprint $table) {
                $table->unsignedBigInteger('points')->default(0);
                $table->unsignedBigInteger('visits')->default(0);
                $table->uuid('customer_id')->default(0);
                $table->date('dob')->nullable();
                $table->string('city')->nullable();
                $table->string('country')->nullable();
                $table->string('reward_location')->nullable();
                $table->string('contact_method')->nullable();
                $table->string('info_source')->nullable();
            });
        }

        public function down() : void
        {
            Schema::table('users' , function (Blueprint $table) {
                $table->dropColumn('points');
                $table->dropColumn('visits');
                $table->dropColumn('customer_id');
                $table->dropColumn('dob');
                $table->dropColumn('city');
                $table->dropColumn('country');
                $table->dropColumn('reward_location');
                $table->dropColumn('contact_method');
                $table->dropColumn('info_source');
            });
        }
    };
