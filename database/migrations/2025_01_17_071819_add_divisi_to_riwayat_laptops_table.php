<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('riwayat_laptops', function (Blueprint $table) {
            $table->string('divisi_lama')->nullable()->after('client_lama');
            $table->string('divisi_baru')->nullable()->after('client_baru');
        });
    }

    public function down()
    {
        Schema::table('riwayat_laptops', function (Blueprint $table) {
            $table->dropColumn(['divisi_lama', 'divisi_baru']);
        });
    }
};