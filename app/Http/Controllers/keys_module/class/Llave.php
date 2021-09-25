<?php

namespace App\Http\Controllers\keys_module\class;

use App\Http\Controllers\Require\Trait\MethodsKey;

class LLave {

    public function __construct(
        private $nombre_ambiente
    ){}

    //Usamos el trait 'MethodsKey', para validar las propiedades: 
    use MethodsKey;
}