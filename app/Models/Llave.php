<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llave extends Model
{
    use HasFactory;

    protected $fillable = [
        'ambiente_id',
        'url_codigo_qr',
        'codigo_llave'
    ];

    //Relacion uno a muchos : 
    public function environments()
    {
        return $this->belongsTo(Ambiente::class, 'ambiente', 'id_ambiente');
    }
}
