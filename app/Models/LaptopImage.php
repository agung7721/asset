<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaptopImage extends Model
{
    protected $fillable = [
        'laptop_id',
        'image_path',
        'type'
    ];

    public function laptop()
    {
        return $this->belongsTo(Laptop::class);
    }
}
