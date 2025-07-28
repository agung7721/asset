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
        Schema::create('laptops', function (Blueprint $table) {
            $table->id();
            $table->string('merk');
            $table->string('model');
            $table->string('serial_number');
            $table->date('tanggal_pembelian')->nullable();
            $table->string('nama_client')->nullable();
            $table->date('tanggal_penyerahan')->nullable();
            $table->enum('kondisi_awal', ['Baru', 'Baik', 'Rusak Ringan', 'Tidak bisa diperbaiki']);
            $table->date('tanggal_pengembalian')->nullable();
            $table->enum('kondisi_akhir', ['Baik', 'Rusak Ringan', 'Tidak bisa diperbaiki'])->nullable();
            $table->enum('posisi_terakhir', ['Dipinjam User', 'Perbaikan', 'Gudang IT']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laptops');
    }
};
