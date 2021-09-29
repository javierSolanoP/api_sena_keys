<?php

namespace App\Http\Controllers\Require\Trait;

use App\Http\Controllers\Require\Class\Validate;

trait MethodsItemModuleProfile {

    // Metodo para validar las propiedades de la instancia, para realizar un registro: 
    public function registerData()
    {
        if(isset($_SESSION['item-modulo-perfil'])){

            //Asignamos la instancia que contiene la sesion 'item-modulo-perfil', a la variable: 
            $data = $_SESSION['item-modulo-perfil'];

            // Instanciamos la clase 'Validate', para validar los datos que contienen las propiedades: 
            $validate = new Validate;

            // Validamos la propiedad 'item_modulo':
            if(!empty($data->item_modulo)){

                if($validate->validateNumber(data: $data->item_modulo)){

                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['item_modulo'] = true;
                }else{
                    // Retornamos el error:
                    die('{"error" : "Campo item_modulo: Debe ser un caracter de tipo numerico entero."}');
                }
            }

            // Validamos la propiedad 'perfil':
            if(!empty($data->perfil)){

                if($validate->validateString(data: $data->perfil)){

                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['perfil'] = true;
                }else{
                    // Retornamos el error:
                    die('{"error" : "Campo perfil: No debe contener caracteres alfanumericos."}');
                }
            }

            // Retornamos la respuesta:
            return ['register' => true, 'fields' => $valid];
        }
    }
}