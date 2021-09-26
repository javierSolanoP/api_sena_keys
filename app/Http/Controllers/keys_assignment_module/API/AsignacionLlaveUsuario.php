<?php

namespace App\Http\Controllers\keys_assignment_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\keys_module\API\AmbienteController;
use App\Http\Controllers\keys_module\API\LlaveController;
use App\Http\Controllers\users_module\API\UserController;
use App\Models\AsignacionLlaveUsuario as ModelsAsignacionLlaveUsuario;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class AsignacionLlaveUsuario extends Controller
{
    //Metodo para retornar todos los registros de la tabla de la DB: 
    public function index()
    {
        //
    }

    //Metodo para registrar una nueva asignacion en la tabla de la DB: 
    public function store(Request $request)
    {
        //Validamos si los argumentos no estan vacios: 
        if(!empty($request->input(key: 'codigo_barras')) && !empty($request->input(key: 'codigo_llave'))){

            //Instanciamos los controladores de los modelos 'Usuario' y 'Llave', para validar si existe el usuario y la llave: 
            $userController = new UserController;
            $keyController = new LlaveController;

            //Validamos si existe el usuario y la llave: 
            $validateUser = $userController->show(codigo_barras: $request->input(key: 'codigo_barras'));
            $validateKey = $keyController->show(codigo_llave: $request->input(key: 'codigo_llave'));

            //Sino existe el usuario, retornamos el error: 
            if(!$validateUser['query']){
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateUser['error']];
            }else{
                //Si existe, extraemos su 'id': 
                $user_id = $validateUser['user']['id_usuario'];
            }

            //Sino existe la llave, retornamos el error: 
            if(!$validateKey['query']){
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateKey['error']];
            }else{
                //Si existe, extraemos su 'id': 
                $key_id = $validateKey['key']['id_llave'];
            }

            //Realizamos la consulta a la tabla de la DB, para validar que no exista una asignacion para esas claves foraneas: 
            $model = ModelsAsignacionLlaveUsuario::where('usuario_id', $user_id)->where('llave_id', $key_id);

            //Validamos si no existe esa asignacion en la tabla de la DB: 
            $validateAssignment = $model->first();

            //Sino existe, lo registramos: 
            if(!$validateAssignment){

                //obtenemos la fecha actual, para poder registrarla en la tabla: 
                $currentDate = new DateTime;

                try{

                    return $currentDate->format('Y-m-d H:i:s');

                    //Realizamos el nuevo registro en la tabla:
                    ModelsAsignacionLlaveUsuario::create(['usuario_id' => $user_id,
                                                        'llave_id' => $key_id,
                                                        'entregada_el' => $currentDate->format('Y-m-d'),
                                                        'en_uso' => 'si']); 

                    return ['register' => true];
                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }

            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => 'Ya existe esa asignacion en el sistema.'];
            }

        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'codigo_barras' o 'codigo_llave': No deben estar vacios."];
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
