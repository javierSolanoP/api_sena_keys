<?php

namespace App\Http\Controllers\users_module\class;

use App\Http\Controllers\Require\Trait\MethodsUser;

class User {

    public function __construct(
        protected $identificacion = '',
        protected $nombre = '',
        protected $apellidos = '',
        protected $codigo_barras = '',
        protected $email = '',
        protected $password = '',
        protected $confirmPassword = ''
    ){}

    //Utilizamos los metodos proporcionados por el trait 'MethodsUser': 
    use MethodsUser;
}