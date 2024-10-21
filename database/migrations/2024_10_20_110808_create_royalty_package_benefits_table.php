<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() : void
        {
            Schema::create('royalty_package_benefits' , function (Blueprint $table) {
                $table->id();
                $table->string('name');
//                $table->string('package_id');
                $table->unsignedInteger('status');
                $table->string('description')->nullable();
                $table->timestamps();
            });
        }

        public function down() : void
        {
            Schema::dropIfExists('royalty_package_benefits');
        }
    };
