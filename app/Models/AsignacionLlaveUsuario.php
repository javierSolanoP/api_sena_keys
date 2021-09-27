<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionLlaveUsuario extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'llave_id',
        'entregada_el',
        'en_uso',
        'regresada_el'
    ];

    //Relacion con tabla 'llaves': 
    public function keys()
    {
        return $this->belongsTo(Llave::class, 'llave', 'id_llave'); 
    }
}
