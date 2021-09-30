<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModuloPerfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_modulo_id',
        'perfil_id'
    ];

    // Relacion con el modelo 'ItemModulo': 
    public function itemModule()
    {
        return $this->belongsTo(ItemModulo::class, 'item_modulo', 'id_item_modulo');
    }

    // Relacion con el modelo 'Perfil': 
    public function profile()
    {
        return $this->belongsTo(Perfil::class, 'perfil', 'id_perfil');
    }
}
