<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatLaptop extends Model
{
    protected $fillable = [
        'laptop_id',
        'client_lama',
        'divisi_lama',
        'client_baru',
        'divisi_baru',
        'tanggal_perpindahan',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_perpindahan' => 'datetime'
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
