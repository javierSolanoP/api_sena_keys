<?php

namespace App\Http\Controllers\Require\Trait;

use App\Http\Controllers\Require\Class\Validate;

trait MethodsItemModule {
    
    // Metodo para validar las propiedades al momento de hacer un registro: 
    public function registerData()
    {
        if(isset($_SESSION['item-modulo'])){

            // Asignamos la instancia que contiene la sesion 'item-modulo', a la variable: 
            $data = $_SESSION['item-modulo'];

            // Instanciamos la clase 'Validate', para validar los datos:
            $validate = new Validate;

            // Decraramos un arreglo para almacenar las propiedades validadas: 
            $valid = [];

            // Metodo para validar el campo 'item_modulo': 
            if(!empty($data->item_module)){

                if($validate->validateNumber($data->item_module)){
                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['item_modulo'] = true;
                }else{
                    // Retornamos el error: 
                    die('{"register": false, "error" : "Campo item_modulo: Debe ser un caracter de tipo numerico entero."}');
                }
            }

            // Metodo para validar el campo 'url_item_modulo': 
            if(!empty($data->url_item_module)){

                if($validate->validateString($data->url_item_module)){
                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['url_item_modulo'] = true;
                }else{
                    // Retornamos el error: 
                    die('{"register": false, "error" : "Campo url_item_modulo: No debe contener caracteres alfanumericos."}');
                }
            }

            // Metodo para validar el campo 'icono_item_modulo': 
            if(!empty($data->icono_item_module)){

                if($validate->validateString($data->icono_item_module)){
                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['icono_item_modulo'] = true;
                }else{
                    // Retornamos el error: 
                    die('{"register": false, "error" : "Campo icono_item_modulo: No debe contener caracteres alfanumericos."}');
                }
            }

            // Metodo para validar el campo 'orden': 
            if(!empty($data->orden)){

                if($validate->validateNumber($data->orden)){
                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['orden'] = true;
                }else{
                    // Retornamos el error: 
                    die('{"register": false, "error" : "Campo orden: Debe ser un caracter de tipo numerico entero."}');
                }
            }

            // Retornamos la respuesta: 
            return ['register' => true, 'fields' => $valid];
        }
    }
}