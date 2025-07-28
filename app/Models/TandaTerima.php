<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TandaTerima extends Model
{
    protected $table = 'tanda_terima';
    
    protected $fillable = [
        'path',
        'nama_file',
        'ukuran',
        'laptop_id',
        'tipe'
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
