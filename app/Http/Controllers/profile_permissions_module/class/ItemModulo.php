<?php

namespace App\Http\Controllers\profile_permissions_module\class;

use App\Http\Controllers\Require\Trait\MethodsItemModule;

class ItemModulo {

    public function __construct(
        private $item_module = '',
        private $nombre_item_module = '',
        private $url_item_module = '',
        private $icono_item_module = '',
        private $orden = ''
    ){}

    // Usamos los metodos proporcionados por el trait 'MethodsItemModule': 
    use MethodsItemModule;
}