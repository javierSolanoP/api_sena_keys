<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_zona'
    ];

    public function environment()
    {
        return $this->hasMany(Ambiente::class, 'id_ambiente');
    }
    public function center()
    {
        return $this->belongsTo(CentroFormacion::class, 'centro', 'id_centro');
    }
}
