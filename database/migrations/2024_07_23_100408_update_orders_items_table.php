<?php

use App\Traits\TableSchemaTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use TableSchemaTrait;

    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('status')->default(1);
        });
    }

    public function down(): void
    {
        $this->dropColumnIfExists('order_items', 'status');
    }
};
