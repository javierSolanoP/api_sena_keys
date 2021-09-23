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
        $model = User::all();

        //Si existen registros, los retornamos: 
        if($model){

            //Eliminamos el 'hash' del campo password de cada registro, por motivos de seguridad: 
            foreach($model as $user){
                unset($user['password']);
            }

            //Retornamos la respuesta: 
            return ['query' => true, 'users' => $model];

        }else{
            //Retornamos el error: 
            return ['query' => false, 'error' => 'No existen usuarios en el sistema.'];
        }
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
        $model = User::where('email', $email);

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
    public function show($email)
    {
        //Realizamos la consulta a la DB: 
        $model = User::where('email', $email);

        //Validamos que no exista ese usuario en la tabla de la DB: 
        $validateUser = $model->first();
 
        //Si existe, retornamos el usuario solicitado: 
        if($validateUser){

            //Eliminamos el 'hash' del campo password, por motivos de seguridad: 
            unset($validateUser['password']);
            //Retornamos la respuesta: 
            return ['query' => true, 'user' => $validateUser];

        }else{
            //Retornamos el error:
            return ['query' => false, 'error' => 'No existe ese perfil en el sistema.'];
        }
    }

    //Metodo para actualizar la informacion basica del usuario en especifico de la tabla en la DB:
    public function update($email)
    {
        //
    }

    //Metodo para eliminar un usuario en especifico de la tabla en la DB:
    public function destroy($email)
    {
        //Realizamos la consulta a la DB: 
        $model = User::where('email', $email);

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
