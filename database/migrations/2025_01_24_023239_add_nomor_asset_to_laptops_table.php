<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            if (!Schema::hasColumn('laptops', 'nomor_asset')) {
                $table->string('nomor_asset')->nullable()->after('id');
            }
            if (!Schema::hasColumn('laptops', 'kapasitas_ssd')) {
                $table->integer('kapasitas_ssd')->nullable()->after('nomor_asset');
            }
            if (!Schema::hasColumn('laptops', 'kapasitas_ram')) {
                $table->integer('kapasitas_ram')->nullable()->after('kapasitas_ssd');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->dropColumn(['nomor_asset', 'kapasitas_ssd', 'kapasitas_ram']);
        });
    }
};
