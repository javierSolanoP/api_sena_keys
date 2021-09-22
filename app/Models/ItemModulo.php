<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_modulo', 
        'url_item_modulo',
        'icono_item_modulo',
        'orden'
    ];
}
