<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_perfil',
        'tipo_permiso'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'perfil', 'id_perfil');
    }

    //  Relacion con el modelo 'ItemModuloPerfil': 
    public function itemModuleProfiles()
    {
        return $this->hasMany(ItemModuloPerfil::class, 'perfil', 'id_perfil');
    }
}
