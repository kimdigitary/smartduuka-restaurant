<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up() : void
        {
            Schema ::create('expenses', function (Blueprint $table) {
                $table -> id();
                $table -> string('name');
                $table -> integer('amount');
                $table -> dateTime('date');
                $table -> integer('category');
                $table -> integer('user_id');
                $table -> text('note')->nullable();
                $table -> integer('paymentMethod');
                $table -> integer('repetitions');
                $table ->integer('paid') -> default(0) ;
                $table ->dateTime('paid_on') -> nullable();
                $table ->dateTime('repeats_on') -> nullable();
                $table -> string('referenceNo') -> nullable();
                $table -> string('attachment') -> nullable();
                $table -> string('recurs') -> nullable();
                $table -> boolean('isRecurring') -> default(false);
                $table -> timestamps();
            });
        }

        public function down() : void
        {
            Schema ::dropIfExists('expenses');
        }
    };
