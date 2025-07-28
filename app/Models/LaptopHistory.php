<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopHistory extends Model
{
    protected $fillable = [
        'laptop_id',
        'tanggal_perpindahan',
        'nama_client',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $dates = [
        'tanggal_mulai',
        'tanggal_selesai',
        'tanggal_perpindahan'
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
