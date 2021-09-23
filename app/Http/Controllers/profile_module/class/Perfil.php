<?php

namespace App\Http\Controllers\profile_module\class;

use App\Http\Controllers\Require\Trait\MethodsProfile;

class Perfil {

    public function __construct(
        protected $nombre_perfil = '',
        protected $tipo_permiso = ''
    ){}

    use MethodsProfile;
}