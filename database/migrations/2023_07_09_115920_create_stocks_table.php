<?php

    use App\Enums\Status;
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() : void
        {
            Schema::create('stocks' , function (Blueprint $table) {
                $table->id();
                $table->foreignId('item_id')->constrained();
                $table->string('model_type');
                $table->unsignedBigInteger('model_id');
                $table->string('item_type');
                $table->unsignedDecimal('price' , 13 , 6)->default(0);
                $table->bigInteger('quantity')->default(1);
                $table->unsignedDecimal('discount' , 13 , 6)->default(0);
                $table->unsignedDecimal('subtotal' , 13 , 6)->default(0);
                $table->unsignedDecimal('tax' , 13 , 6)->default(0);
                $table->unsignedDecimal('total' , 13 , 6)->default(0);
                $table->unsignedTinyInteger('status')->default(Status::INACTIVE);
                $table->timestamps();
            });
        }

        public function down() : void
        {
            Schema::dropIfExists('stocks');
        }
    };
