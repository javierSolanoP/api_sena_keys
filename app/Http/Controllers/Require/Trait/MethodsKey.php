<?php 

namespace App\Http\Controllers\Require\Trait;

use App\Http\Controllers\Require\Class\Validate;

trait MethodsKey {

    //Metodo para validar los datos: 
    public function registerData()
    {
        //Instanciamos la clase 'Validate', para validar los datos recibidos: 
        $validate = new Validate;

        if(isset($_SESSION['register'])){

            //Asignamos la instancia que contiene la sesion a la variable 'data': 
            $data = $_SESSION['register'];

            //Validamos la propiedad 'nombre_ambiente':
            if($validate->validateString($data->nombre_ambiente)){

                //Retornamos una respuesta: 
                return ['register' => true];
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => 'Campo nombre_ambiente: No debe contener caracteres de texto.'];
            }
        }
    }
}