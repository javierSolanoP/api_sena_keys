<?php

namespace App\Http\Controllers\Require\Trait;

use App\Http\Controllers\Require\Class\Validate;
use Exception;

//Los metodos pde la clase 'Perfil': 
trait MethodsProfile {

    //Metodo para valdar los datos del perfil: 
    public function registerData()
    {
        //Instanciamos la clase 'Validate', para poder validar los datos: 
        $validate = new Validate;

        //Declaramos el array 'valid', para almacenar los datos validados: 
        $valid = [];

        if(isset($_SESSION['profile'])){

            $profile = $_SESSION['profile'];

            //Validamos el campo 'nombre_perfil': 
            if(!empty($profile->nombre_perfil)){

                if($validate->validateString($profile->nombre_perfil)){

                    //Agregamos el campo validado: 
                    $valid['nombre_perfil'] = true;

                }else{

                    //Retornamos el error: 
                    die('{"register": false, "error": "nombre_perfil: no puede contener caracteres alfanumericos"}');

                }
            }

            //Validamos el campo 'tipo_permiso': 
            if(!empty($profile->tipo_permiso)){

                if($validate->validateString($profile->tipo_permiso)){

                    //Agregamos el campo validado: 
                    $valid['tipo_permiso'] = true;

                }else{
    
                    //Retornamos el error: 
                    die('{"register": false, "error": "tipo_permiso: no puede contener caracteres alfanumericos"}');
    
                }
            }

            try{
            
                //Retornamos la respuesta: 
                return ['register' => true, 'fields' => $valid];
            }catch(Exception $e){
    
                //Retornamos el error: 
                return ['register' => false, 'error' => $e->getMessage()];
            }
        }
    }
}