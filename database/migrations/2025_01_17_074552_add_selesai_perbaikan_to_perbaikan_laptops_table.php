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
        Schema::table('perbaikan_laptops', function (Blueprint $table) {
            $table->dateTime('selesai_perbaikan')->nullable()->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('perbaikan_laptops', function (Blueprint $table) {
            $table->dropColumn('selesai_perbaikan');
        });
    }
};
