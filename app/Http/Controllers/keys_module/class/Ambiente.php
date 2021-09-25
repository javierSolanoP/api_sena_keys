<?php

namespace App\Http\Controllers\keys_module\class;

use App\Http\Controllers\Require\Trait\MethodsEnvironment;

class Ambiente {

    public function __construct(
        private $nombre_ambiente,
        private $description
    ){}

    //Usamos el trait 'MethodsEnvironment', para validar las propidades: 
    use MethodsEnvironment;
}