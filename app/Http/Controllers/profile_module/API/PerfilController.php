<?php

namespace App\Http\Controllers\profile_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\profile_module\class\Perfil as ClassPerfil;
use App\Models\Perfil;
use Exception;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    //Metodo para retornar todos los registros de la tabla en la DB: 
    public function index()
    {
        //Realizamos la consulta a la DB: 
        $model = Perfil::all();

        //Si existen registros, los retornamos: 
        if($model){
            //Retornamos la respuesta: 
            return ['query' => true, 'profiles' => $model];
        }else{
            //Retornamos el error: 
            return ['query' => false, 'error' => 'No existen perfiles en el sistema.'];
        }
    }

    //Metodo para registrar un nuevo perfil en la tabla de la DB: 
    public function store($nombre_perfil, $tipo_permiso)
    {
        //Convertimos los caracteres de tipo mayusculas de los argumentos, para seguir una nomenclatura estandar: 
        $perfil = strtolower($nombre_perfil);
        $permiso = strtolower($tipo_permiso);

        //Realizamos la consulta a la DB: 
        $model = Perfil::where('nombre_perfil', $perfil);

        //Validamos que no exista ese perfil en la tabla de la DB: 
        $validateProfile = $model->first();

        //Sino existe, validamos los argumentos recibidos: 
        if(!$validateProfile){

            //Instanciamos la clase 'Perfil', para poder realizar la validacion:
            $profile = new ClassPerfil(nombre_perfil: $perfil,
                                       tipo_permiso: $permiso);

            //Enviamos la instancia 'user' al trait 'MethodsProfile', con las propiedades cargadas de informacion: 
            $_SESSION['profile'] = $profile;

            $validateRegister = $profile->registerData();

            //Si la informacion se valida correctamente, realizamos el registro del nuevo usuario: 
            if($validateRegister['register']){

                try{

                    //Realizamos la insercion del registro del nuevo usuario en la tabla de la DB:
                    Perfil::create(['nombre_perfil' => $perfil,
                                    'tipo_permiso' => $permiso]);

                    //Retornamos la respuesta: 
                    return $validateRegister;

                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateRegister['error']];
            }
        }else{
            //Retornamos el error:
            return ['register' => false, 'error' => 'Ya existe ese perfil en el sistema.'];
        }
    }

    //Metodo para retornar un perfil en especifico de la tabla de la DB: 
    public function show($nombre_perfil)
    {
        //Convertimos los caracteres de tipo mayusculas del argumento, para seguir una nomenclatura estandar: 
        $perfil = strtolower($nombre_perfil);

        //Realizamos la consulta a la DB: 
        $model = Perfil::where('nombre_perfil', $perfil);

        //Validamos que no exista ese perfil en la tabla de la DB: 
        $validateProfile = $model->first();
 
        //Si existe, retornamos el perfil solicitado: 
        if($validateProfile){
            //Retornamos la respuesta: 
            return ['query' => true, 'profile' => $validateProfile];
        }else{
            //Retornamos el error:
            return ['query' => false, 'error' => 'No existe ese perfil en el sistema.'];
        }
    }

    //Metodo para actualizar el registro de un perfil en especifico: 
    public function update($nombre_perfil)
    {

    }

    //Metodo para eliminar el registro de un perfil en especifico: 
    public function destroy($nombre_perfil)
    {
        //Convertimos los caracteres de tipo mayusculas de los argumentos, para seguir una nomenclatura estandar: 
        $perfil = strtolower($nombre_perfil);

        //Realizamos la consulta a la DB: 
        $model = Perfil::where('nombre_perfil', $perfil);

        //Validamos que no exista ese perfil en la tabla de la DB: 
        $validateProfile = $model->first();
 
        //Si existe, eliminamos el perfil solicitado: 
        if($validateProfile){

            try{

                //Realizamos la eliminacion en la tabla de la DB: 
                $model->delete();
                return ['delete' => true];

            }catch(Exception $e){
                //Retornamos el error: 
                return ['delete' => false, 'error' => $e->getMessage()];
            }
        }else{
            //Retornamos el error:
            return ['delete' => false, 'error' => 'No existe ese perfil en el sistema.'];
        }
    }
}
