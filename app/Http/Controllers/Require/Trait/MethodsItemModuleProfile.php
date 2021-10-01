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

            // Validamos la propiedad 'nombre_modulo':
            if(!empty($data->nombre_modulo)){

                if($validate->validateString(data: $data->nombre_modulo)){

                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['nombre_modulo'] = true;
                }else{
                    // Retornamos el error:
                    die('{"register" : false, "error" : "Campo nombre_modulo: No debe contener caracteres alfanumericos."}');
                }
            }

            // Validamos la propiedad 'perfil':
            if(!empty($data->perfil)){

                if($validate->validateString(data: $data->perfil)){

                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['perfil'] = true;
                }else{
                    // Retornamos el error:
                    die('{"register" : false, "error" : "Campo perfil: No debe contener caracteres alfanumericos."}');
                }
            }

            // Retornamos la respuesta:
            return ['register' => true, 'fields' => $valid];
        }
    }

    // Metodo para validar las propiedades de la instancia, para realizar una actualizacion: 
    public function updateData()
    {
        if(isset($_SESSION['item-modulo-perfil'])){

            //Asignamos la instancia que contiene la sesion 'item-modulo-perfil', a la variable: 
            $data = $_SESSION['item-modulo-perfil'];

            // Instanciamos la clase 'Validate', para validar los datos que contienen las propiedades: 
            $validate = new Validate;

            // Validamos la propiedad 'nombre_modulo':
            if(!empty($data->nombre_modulo)){

                if($validate->validateString(data: $data->nombre_modulo)){

                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['nombre_modulo'] = true;
                }else{
                    // Retornamos el error:
                    die('{"register" : false, "error" : "Campo new_nombre_modulo: No debe contener caracteres alfanumericos."}');
                }
            }

            // Validamos la propiedad 'perfil':
            if(!empty($data->perfil)){

                if($validate->validateString(data: $data->perfil)){

                    // Agregamos la propiedad validada al arreglo 'valid': 
                    $valid['perfil'] = true;
                }else{
                    // Retornamos el error:
                    die('{"register" : false, "error" : "Campo new_perfil: No debe contener caracteres alfanumericos."}');
                }
            }

            // Retornamos la respuesta:
            return ['register' => true, 'fields' => $valid];
        }
    }
}