<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroFormacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_centro'
    ];

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'centro', 'id_centro');
    }
}
