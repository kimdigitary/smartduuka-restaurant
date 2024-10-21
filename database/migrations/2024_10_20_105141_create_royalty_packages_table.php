<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() : void
        {
            Schema::create('royalty_packages' , function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('description')->nullable();
                $table->unsignedInteger('status');
                $table->timestamps();
            });
        }

        public function down() : void
        {
            Schema::dropIfExists('royalty_packages');
        }
    };
