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
        Schema::table('settings', function (Blueprint $table) {
            // Voeg tenant_id NU eerst nullable toe
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');

            // Voeg dan de foreign key naar tenants(id) toe
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->cascadeOnDelete();
        });

        // Optioneel: als je wilt dat bestaande rijen wel een tenant krijgen vóór je non-null maakt,
        // kun je dat hier al doen via DB::table('settings')->update([...]), maar meestal
        // doe je dat in een aparte seeder of via Tinker.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
