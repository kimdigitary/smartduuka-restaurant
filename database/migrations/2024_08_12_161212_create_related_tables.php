<?php

use App\Models\Tenant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->getTenantRelatedTables() as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->foreignIdFor(Tenant::class)
                    ->nullable()
                    ->constrained()
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach ($this->getTenantRelatedTables() as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }

    public function getTenantRelatedTables(): array
    {
        return [
            // Your tenant related tables list.
            'branches',
            'item_categories',
            'offers',
            'taxes',
            'items',
            'orders',
            'transactions',
            'suppliers',
            'purchases',
            'stocks',
            'dining_tables',
            'expenses',
            'ingredients'
        ];
    }
};