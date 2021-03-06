<?php

namespace App\Http\Controllers\users_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\profile_module\API\PerfilController;
use App\Http\Controllers\users_module\class\User as ClassUser;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Metodo para retornar todos los registros de la tabla en la DB: 
    public function index()
    {
        //Realizamos la consulta a la DB: 
        $model = User::select('perfil_id as perfil', 'identificacion', 'nombre', 'apellido')->get();

        //Recorremos cada registro, para hacerle ajustes a la informacion: 
        foreach($model as $user){

            //Asignamos el nombre del perfil al que pertenece esa clave foranea 'perfil_id': 
            $user->perfil = $user->profile->nombre_perfil;

            //Eliminamos la demas informacion del perfil que no requiramos: 
            unset($user->profile);
        }

        //Retornamos una respuesta: 
        return ['query' => true, 'users' => $model];
    }

    //Metodo para registrar un nuevo usuario en la tabla de la DB:
    public function store(
        $identificacion,
        $nombre,
        $apellidos,
        $codigo_barras,
        $email,
        $password,
        $confirmPassword,
        $perfil
    )
    {
        //Realizamos la consulta a la DB: 
        $model = User::where('codigo_barras', $codigo_barras);

        //Validamos si existe el usuario en la DB:
        $validateUser = $model->first();

        //Sino existe, realizamos la validacion de los argumentos recibidos:  
        if(!$validateUser){

            //Instanciamos el controlador de la clase 'Perfil', para validar si existe el perfil: 
            $profileController = new PerfilController;

            //Validamos si existe ese perfil: 
            $validateProfile = $profileController->show($perfil);

            //Si existe, realizamos la validacion de los argumentos: 
            if($validateProfile['query']){

                //Instanciamos la clase 'User', para poder realizar la validacion:
                $user = new ClassUser(identificacion: $identificacion,
                                    nombre: $nombre,
                                    apellidos: $apellidos,
                                    codigo_barras: $codigo_barras,
                                    email: $email,
                                    password: $password,
                                    confirmPassword: $confirmPassword); 

                //Enviamos la instancia 'user' al trait 'MethodsUser', con las propiedades cargadas de informacion: 
                $_SESSION['registerData'] = $user;

                //Validamos la informacion: 
                $validateRegister = $user->registerData();

                //Si la informacion se valida correctamente, realizamos el registro del nuevo usuario: 
                if($validateRegister['register']){

                    try{
                        //Realizamos la insercion del registro del nuevo usuario en la tabla de la DB: 
                        User::create(['perfil_id' => $validateProfile['profile']['id_perfil'],
                        'identificacion' => $identificacion,
                        'nombre' => $nombre,
                        'apellido' => $apellidos,
                        'codigo_barras' => $codigo_barras,
                        'email' => $email,
                        'password' => $validateRegister['fields']['password']]);

                        //Eliminamos el 'hash' de la password generado por el metodo 'registerData()', por motivos de seguridad: 
                        unset($validateRegister['fields']['password']);

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
                return ['register' => false, 'error' => $validateProfile['error']];
            } 
        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'Ya existe ese usuario en el sistema.'];
        }
    }

    //Metodo para retornar un usuario en especifico de la tabla en la DB:
    public function show($codigo_barras)
    {
        //Realizamos la consulta a la DB: 
        $model = User::select('id_usuario', 'perfil_id as perfil', 'identificacion', 'nombre', 'apellido')->where('codigo_barras', $codigo_barras);

        //Validamos que exista el usuario en la tabla de la DB: 
        $validateUser = $model->first();

        //Si existe, retornamos la informacion del usuario: 
        if($validateUser){

            //Asignamos el nombre del perfil al que pertenece esa clave foranea 'perfil_id': 
            $validateUser->perfil = $validateUser->profile->nombre_perfil;

            //Eliminamos la demas informacion del perfil que no requiramos: 
            unset($validateUser->profile);

            //Retornamos la respuesta: 
            return ['query' => true, 'user' => $validateUser];

        }else{
            //Retornamos el error: 
            return ['query' => false, 'error' => 'No existe ese usuario en el sistema.'];
        }
    }

    //Metodo para actualizar la informacion basica del usuario en especifico de la tabla en la DB:
    public function update($codigo_barras)
    {
        //
    }

    //Metodo para eliminar un usuario en especifico de la tabla en la DB:
    public function destroy($codigo_barras)
    {
        //Realizamos la consulta a la DB: 
        $model = User::where('codigo_barras', $codigo_barras);

        //Validamos que no exista ese usuario en la tabla de la DB: 
        $validateUser = $model->first();
 
        //Si existe, eliminamos el usuario solicitado: 
        if($validateUser){

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
            return ['delete' => false, 'error' => 'No existe ese usuario en el sistema.'];
        }
    }
}
