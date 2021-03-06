<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    
    protected $fillable = [
        'perfil_id',
        'identificacion',
        'nombre',
        'apellido',
        'codigo_barras',
        'email',
        'password'
    ];

    public function profile()
    {
        return $this->belongsTo(Perfil::class, 'perfil', 'id_perfil');
    }
}