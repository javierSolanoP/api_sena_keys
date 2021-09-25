<?php

namespace App\Http\Controllers\Require\Trait;

use App\Http\Controllers\Require\Class\Validate;

trait MethodsZone {

    //Metodo para validar los datos: 
    public function registerData()
    {
        //Instanciamos la clase 'Validate', para validar los datos recibidos: 
        $validate = new Validate;

        if(isset($_SESSION['register'])){

            //Asignamos la instancia que contiene la sesion a la variable 'data': 
            $data = $_SESSION['register'];

            //Validamos la propiedad 'nombre_zona':
            if($validate->validateString($data->nombre_zona)){

                //Retornamos una respuesta: 
                return ['register' => true];
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => 'Campo nombre_zona: No debe contener caracteres alfanumericos.'];
            }
        }
    }
}