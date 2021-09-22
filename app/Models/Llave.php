<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Llave extends Model
{
    use HasFactory;

    protected $fillable = [
        'ambiente_id',
        'codigo_qr',
        'codigo_llave'
    ];
}
