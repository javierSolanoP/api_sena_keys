<?php

namespace App\Http\Controllers\Require\Trait;

use App\Http\Controllers\Require\Class\Validate;

trait MethodsEnvironment {

    //Metodo para validar los datos: 
    public function registerData()
    {
        //Instanciamos la clase 'Validate', para validar los datos recibidos: 
        $validate = new Validate;
        //Declaramos el array 'valid', para anexar los campos validados: 
        $valid = [];

        if(isset($_SESSION['register'])){
            //Asignamos la instancia que contiene la sesion a la variable 'data': 
            $data = $_SESSION['register'];

            //Validamos la propiedad 'nombre_ambiente':
            if(!empty($data->nombre_ambiente)){
                
                if($validate->validateString($data->nombre_ambiente)){

                    $valid['nombre_ambiente'] = true;

                }else{
                    //Retornamos el error: 
                    die('{"register" : false, "error" : "Campo nombre_ambiente: No debe contener caracteres alfanumericos."}');
                }
            }else{
                //Retornamos el error: 
                die('{"register" : false, "error" : "Campo nombre_ambiente: No debe estar vacio."}');
            }

            //Validamos la propiedad 'description':
            if(!empty($data->description)){
                
                if($validate->validateString($data->description)){

                    $valid['description'] = true;

                }else{
                    //Retornamos el error: 
                    die('{"register" : false, "error" : "Campo description: No debe contener caracteres alfanumericos."}');
                }
            }else{
                //Retornamos el error: 
                die('{"register" : false, "error" : "Campo description: No debe estar vacio."}');
            }

            //Validamos la propiedad 'estado':
            if(!empty($data->estado)){
                
                if($validate->validateString($data->estado)){

                    $valid['estado'] = true;

                }else{
                    //Retornamos el error: 
                    die('"register" : false, {"error" : "Campo estado: No debe contener caracteres alfanumericos."}');
                }
            }

            //Retornamos una respuesta: 
            return ['register' => true, 'fileds' => $valid];
        }
    }

    //Metodo para validar los datos de una actualizacion: 
    public function updateData()
    {
        //Instanciamos la clase 'Validate', para validar los datos recibidos: 
        $validate = new Validate;
        //Declaramos el array 'valid', para anexar los campos validados: 
        $valid = [];

        if(isset($_SESSION['register'])){
            //Asignamos la instancia que contiene la sesion a la variable 'data': 
            $data = $_SESSION['register'];

            //Validamos la propiedad 'nombre_ambiente':
            if(!empty($data->nombre_ambiente)){
                
                if($validate->validateString($data->nombre_ambiente)){

                    $valid['nombre_ambiente'] = true;

                }else{
                    //Retornamos el error: 
                    die('{"register" : false, "error" : "Campo new_nombre_ambiente: No debe contener caracteres alfanumericos."}');
                }
            }else{
                //Retornamos el error: 
                die('{"register" : false, "error" : "Campo new_nombre_ambiente: No debe estar vacio."}');
            }

            //Validamos la propiedad 'description':
            if(!empty($data->description)){
                
                if($validate->validateString($data->description)){

                    $valid['description'] = true;

                }else{
                    //Retornamos el error: 
                    die('{"register" : false, "error" : "Campo new_description: No debe contener caracteres alfanumericos."}');
                }
            }else{
                //Retornamos el error: 
                die('{"register" : false, "error" : "Campo new_description: No debe estar vacio."}');
            }

            //Retornamos una respuesta: 
            return ['register' => true, 'fileds' => $valid];
        }
    }
}