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
}
