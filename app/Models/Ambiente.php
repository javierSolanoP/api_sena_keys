<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ambiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'zona_id',
        'nombre_ambiente',
        'description',
        'estado'
    ];

    //Relacion uno a muchos : 
    public function keys()
    {
        return $this->hasMany(Llave::class, 'id_llave');
    }

    //Relacion uno a muchos (Inverse) : 
    public function zone()
    {
        return $this->belongsTo(Zona::class, 'zona', 'id_zona');
    }

}
