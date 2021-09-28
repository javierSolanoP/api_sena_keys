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
use Illuminate\Support\Facades\DB;

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
            $model = ModelsAsignacionLlaveUsuario::where('llave_id', $key_id)->orderBy('entregada_el', 'desc');

            //Validamos si existe esa asignacion en la tabla de la DB: 
            $validateKey = $model->first();

            //Si existe, validamos que no este en uso la llave: 
            if($validateKey){
                if($validateKey['en_uso'] == 'si'){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => 'La llave esta en uso.'];
                }
            }

            //obtenemos la fecha actual, para poder registrarla en la tabla: 
            $currentDate = new DateTime;

            try{

                //Realizamos el nuevo registro en la tabla:
                ModelsAsignacionLlaveUsuario::create(['usuario_id' => $user_id,
                                                    'llave_id' => $key_id,
                                                    'entregada_el' => $currentDate->format('Y-m-d H:i:s'),
                                                    'en_uso' => 'si']); 

                return ['register' => true];
            }catch(Exception $e){
                //Retornamos el error: 
                return ['register' => false, 'error' => $e->getMessage()];
            }

        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'codigo_barras' o 'codigo_llave': No deben estar vacios."];
        }
    }

    //Metodo para registrar una nueva asignacion en la tabla de la DB: 
    public function update(Request $request)
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

            //Si existe, lo actualizamos: 
            if($validateAssignment){

                //obtenemos la fecha actual, para poder registrarla en la tabla: 
                $currentDate = new DateTime;

                try{

                    //Realizamos la actualizaion del 'stock': 
                    $model->update(['en_uso' => 'no',
                                    'regresada_el' => $currentDate->format('Y-m-d H:i:s')]); 

                    return ['update' => true];

                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['update' => false, 'error' => $e->getMessage()];
                }

            }else{
                //Retornamos el error: 
                return ['update' => false, 'error' => 'Ya existe esa asignacion en el sistema.'];
            }

        }else{
            //Retornamos el error: 
            return ['update' => false, 'error' => "Campo 'codigo_barras' o 'codigo_llave': No deben estar vacios."];
        }
    }

    //Metodo para retornar las llaves que no esten en uso: 
    public function stock()
    {
        //Realizamos la consulta en la tabla de la DB: 
        $model = ModelsAsignacionLlaveUsuario::select('llave_id as llave',  'en_uso as stock', 'regresada_el')->orderBy('regresada_el', 'desc')->get()->groupBY('llave');
        return $model;
        foreach($model as $key){
            
            //Cambiamos el valor del campo 'llave' por la direccion URL de su 'codigo QR': 
            $key->llave = $key->keys->url_codigo_qr;

            //Instanciamos el controlador del modelo 'LLave', para extraer el ambiente al que pertenece la llave: 
            $keyController = new LlaveController;
            $environmentKey = $keyController->show(codigo_llave: $key->keys->codigo_llave);
            $key['ambiente'] = $environmentKey['key']['ambiente'];

            //Cambiamos el valor del campo 'stock' a 'disponible':
            $key->stock = 'disponible';


            //Eliminamos la demas informacion que no requerimos de la tabla 'llaves': 
            unset($key->keys);
            
        }

        //Retornamos la respuesta: 
        return ['query' => true, 'keys' => $model];

    }

    public function show($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
