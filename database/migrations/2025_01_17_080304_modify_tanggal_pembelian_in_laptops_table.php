<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->date('tanggal_pembelian')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('laptops', function (Blueprint $table) {
            $table->date('tanggal_pembelian')->nullable(false)->change();
        });
    }
};