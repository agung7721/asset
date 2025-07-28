<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laptop extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_asset',
        'merk',
        'model',
        'serial_number',
        'kapasitas_ssd',
        'kapasitas_ram',
        'tanggal_pembelian',
        'nama_client',
        'divisi',
        'tanggal_penyerahan',
        'kondisi_awal',
        'kondisi_akhir',
        'keterangan_rusak',
        'posisi_terakhir',
    ];

    protected $dates = [
        'tanggal_pembelian',
        'tanggal_penyerahan'
    ];

    public function images()
    {
        return $this->hasMany(LaptopImage::class);
    }

    public function histories()
    {
        return $this->hasMany(LaptopHistory::class);
    }

    public function repairs()
    {
        return $this->hasMany(Repair::class);
    }

    public function perbaikan()
    {
        return $this->hasMany(Repair::class, 'laptop_id', 'id');
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatLaptop::class);
    }

    public function tandaTerima()
    {
        return $this->hasMany(TandaTerima::class, 'laptop_id', 'id');
    }
}
