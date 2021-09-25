<?php

namespace App\Http\Controllers\keys_module\class;

use App\Http\Controllers\Require\Trait\MethodsZone;

class Zona {

    public function __construct(
        private $nombre_zona
    ){}

    //Usamos los metodos del trait 'MethodsZone': 
    use MethodsZone;
}