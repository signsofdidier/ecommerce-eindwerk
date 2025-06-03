<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Voeg tenant_id toe direct na de primaire sleutel 'id'.
            // Omdat je migrate:fresh doet, bestaan er nog geen producten,
            // dus tenant_id mag NOT NULL zijn.
            $table->unsignedBigInteger('tenant_id')->after('id');

            // Foreign key naar tenants(id), met cascade-on-delete.
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Verwijder eerst de foreign key constraint...
            $table->dropForeign(['tenant_id']);
            // ...en vervolgens de kolom zelf.
            $table->dropColumn('tenant_id');
        });
    }
};
