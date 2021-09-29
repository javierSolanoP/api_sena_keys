<?php

namespace App\Http\Controllers\profile_permissions_module\class;

use App\Http\Controllers\Require\Trait\MethodsItemModuleProfile;

class ItemModuloPerfil {

    public function __construct(
        private $item_modulo = '',
        private $perfil = ''
    ){}

    // Usamos el trait 'MethodsItemModule/Profile', para validar las propiedades:  
    use MethodsItemModuleProfile;
}