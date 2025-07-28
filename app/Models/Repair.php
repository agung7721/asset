<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    protected $table = 'repairs';
    
    protected $fillable = [
        'laptop_id',
        'tanggal_perbaikan',
        'nama_sparepart',
        'harga',
        'deskripsi'
    ];
    
    protected $casts = [
        'tanggal_perbaikan' => 'date',
        'harga' => 'decimal:2'
    ];
    
    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
