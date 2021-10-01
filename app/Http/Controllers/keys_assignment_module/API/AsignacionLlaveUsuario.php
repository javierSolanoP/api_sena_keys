<?php

namespace App\Http\Controllers\keys_assignment_module\API;

use App\Http\Controllers\Controller;
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
        try{

            // Realizamos la consulta a la tabla de la DB:  
            $model = DB::table(table: 'asignacion_llave_usuarios', as:'asignacion')
                        
                    // Realizamos la consulta a la tabla del modelo 'Llaves':   
                    ->join(table: 'llaves', first: 'llaves.id_llave', operator: '=', second: 'asignacion.llave_id')
                        
                    // Realizamos la consulta a la tabla del modelo 'Ambientes':   
                    ->join(table: 'ambientes', first: 'ambientes.id_ambiente', operator: '=', second: 'llaves.ambiente_id')
                        
                    // Realizamos la consulta a la tabla del modelo 'Zonas':   
                    ->join(table: 'zonas', first: 'ambientes.zona_id', operator: '=', second: 'zonas.id_zona')

                    // Realizamos la consulta a la tabla del modelo 'User':   
                    ->join(table: 'users', first: 'asignacion.usuario_id', operator: '=', second: 'users.id_usuario')
                        
                    // Seleccionamos los campos que requerimos de todas las tablas: 
                    ->select('users.codigo_barras as usuario', 'asignacion.entregada_el as fecha_de_asignacion',  'asignacion.regresada_el as fecha_de_regreso', 'zonas.nombre_zona as zona', 'ambientes.nombre_ambiente', 'ambientes.imagen_ambiente', 'llaves.imagen_llave', 'llaves.url_codigo_qr as codigo_qr', 'llaves.codigo_llave')
                        
                    // Oredanamos la consulta por el campo 'fecha_asigancion' de manera scendente, para asi obtener el registro de asignacion actual:  
                    ->orderBy(column: 'fecha_de_asignacion', direction: 'asc')
                        
                    // Obtenemos los registros: 
                    ->get()

                    // Agrupamos los registros por el campo 'zona': 
                    ->groupBy(groupBy: 'zona');

            // Retornamos la respuesta: 
            return ['query' => true, 'assignments_history' =>['zones' => $model]];

        }catch(Exception $e){
            // Retornamos el error: 
            return ['query' => false, 'error' => $e->getMessage()];
        }  
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
            $model = ModelsAsignacionLlaveUsuario::where('llave_id', $key_id)->where('usuario_id', $user_id)->orderBy('entregada_el', 'desc');

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
                return ['update' => false, 'error' => 'La llave no esta en uso.'];
            }

        }else{
            //Retornamos el error: 
            return ['update' => false, 'error' => "Campo 'codigo_barras' o 'codigo_llave': No deben estar vacios."];
        }
    }

    // Metodo para retornar las llaves que no esten en uso: 
    public function stock()
    {
        try{

            // Realizamos la consulta a la tabla de la DB:  
            $model = DB::table(table: 'asignacion_llave_usuarios', as:'asignacion')
                        
                        // Realizamos la consulta a la tabla del modelo 'Llaves':   
                        ->join(table: 'llaves', first: 'llaves.id_llave', operator: '=', second: 'asignacion.llave_id')
                        
                        // Realizamos la consulta a la tabla del modelo 'Ambientes':   
                        ->join(table: 'ambientes', first: 'ambientes.id_ambiente', operator: '=', second: 'llaves.ambiente_id')
                        
                        // Realizamos la consulta a la tabla del modelo 'Zonas':   
                        ->join(table: 'zonas', first: 'ambientes.zona_id', operator: '=', second: 'zonas.id_zona')
                        
                        // Seleccionamos los campos que requerimos de todas las tablas: 
                        ->select('asignacion.entregada_el as fecha_de_asignacion',  'asignacion.regresada_el as fecha_de_regreso', 'zonas.nombre_zona as zona', 'ambientes.nombre_ambiente', 'ambientes.imagen_ambiente', 'llaves.imagen_llave', 'llaves.url_codigo_qr as codigo_qr', 'llaves.codigo_llave', 'asignacion.en_uso as ocupado')
                        
                        // Oredanamos la consulta por el campo 'fecha_asigancion' de manera scendente, para asi obtener el registro de asignacion actual:  
                        ->orderBy(column: 'fecha_de_asignacion', direction: 'asc')
                        
                        // Obtenemos los registros: 
                        ->get()

                        // Agrupamos los registros por el campo 'zona': 
                        ->groupBy(groupBy: 'zona');

            // Declaramos un array para almacenar el primer registro de cada grupo: 
            $registers = [];

            // Iteramos cada grupo para extraer sus respectivos ambientes: 
            foreach($model as $group){

                // Iteramos cada ambiente para extraer la ultima vez que fue ocupado: 
                foreach($group as $environment){

                    // Almacenamos el registro en el array declarado:
                    $registers[$environment->zona][$environment->nombre_ambiente] = $environment;
                }

            }

            // Retornamos la respuesta: 
            return ['query' => true, 'zones' => $registers];

        }catch(Exception $e){
            // Retornamos el error: 
            return ['query' => false, 'error' => $e->getMessage()];
        }  

    }

    // Metodo para retornar las asiganciones de un usuario especifico: 
    public function show($codigo_barras)
    {
        // Instanciamos el controlador del modelo 'User', para validar que exista el usuario: 
        $userController = new UserController; 

        // Validamos que exista el usuario: 
        $validateUser = $userController->show(codigo_barras: $codigo_barras);

        // Si existe, extraemos su 'id': 
        if($validateUser['query']){

            // Extraemos su 'id': 
            $user_id = $validateUser['user']['id_usuario'];

            try{
                // Realizamos la consulta a la tabla de la DB:  
                $model = DB::table(table: 'asignacion_llave_usuarios', as:'asignacion')

                            // Consultamos la asignacion requerida: 
                            ->where(column: 'usuario_id', operator: '=', value: $user_id)

                            // Realizamos la consulta a la tabla del modelo 'Llaves':   
                            ->join(table: 'llaves', first: 'llaves.id_llave', operator: '=', second: 'asignacion.llave_id')

                            // Realizamos la consulta a la tabla del modelo 'Ambientes':   
                            ->join(table: 'ambientes', first: 'ambientes.id_ambiente', operator: '=', second: 'llaves.ambiente_id')

                            // Realizamos la consulta a la tabla del modelo 'Zonas':   
                            ->join(table: 'zonas', first: 'ambientes.zona_id', operator: '=', second: 'zonas.id_zona')
                            
                            // Realizamos la consulta a la tabla del modelo 'User':   
                            ->join(table: 'users', first: 'asignacion.usuario_id', operator: '=', second: 'users.id_usuario')

                            // Seleccionamos los campos que requerimos de todas las tablas: 
                            ->select('users.codigo_barras as usuario','asignacion.entregada_el as fecha_de_asignacion',  'asignacion.regresada_el as fecha_de_regreso', 'zonas.nombre_zona as zona', 'ambientes.nombre_ambiente', 'ambientes.imagen_ambiente', 'llaves.imagen_llave', 'llaves.url_codigo_qr as codigo_qr', 'llaves.codigo_llave', 'asignacion.en_uso as ocupado')

                            // Oredanamos la consulta por el campo 'fecha_asigancion' de manera scendente, para asi obtener el registro de asignacion actual:  
                            ->orderBy(column: 'fecha_de_asignacion', direction: 'asc')

                            // Obtenemos los registros: 
                            ->get()

                            // Agrupamos los registros por el campo 'zona': 
                            ->groupBy(groupBy: 'zona');

                // Declaramos un array para almacenar el primer registro de cada grupo: 
                $registers = [];

                // Iteramos cada grupo para extraer sus respectivos ambientes: 
                foreach($model as $group){

                    // Iteramos cada ambiente para extraer la ultima vez que fue ocupado: 
                    foreach($group as $environment){

                        // Almacenamos el registro en el array declarado:
                        $registers[$environment->zona][$environment->nombre_ambiente] = $environment;
                    }

                }

                // Retornamos la respuesta: 
                return ['query' => true, 'zones' => $registers];

            }catch(Exception $e){
                // Retornamos el error: 
                return ['query' => false, 'error' => $e->getMessage()];
            }      

        }else{
            // Retornamos el error: 
            return ['query' => false, 'error' => $validateUser['error']];
        }

    }

    public function destroy($codigo_barras, $codigo_llave)
    {
        //Validamos si los argumentos no estan vacios: 
        if(!empty($codigo_barras) && !empty($codigo_llave)){

            //Instanciamos los controladores de los modelos 'Usuario' y 'Llave', para validar si existe el usuario y la llave: 
            $userController = new UserController;
            $keyController = new LlaveController;

            //Validamos si existe el usuario y la llave: 
            $validateUser = $userController->show(codigo_barras: $codigo_barras);
            $validateKey = $keyController->show(codigo_llave: $codigo_llave);

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
            $model = ModelsAsignacionLlaveUsuario::where('llave_id', $key_id)->where('usuario_id', $user_id);

            //Validamos si no existe esa asignacion en la tabla de la DB: 
            $validateAssignment = $model->first();

            //Si existe, lo eliminamos: 
            if($validateAssignment){

                try{

                    //Realizamos la eliminacion del registro: 
                    $model->delete(); 

                    // Retornamos una respuesta:  
                    return ['delete' => true];

                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['delete' => false, 'error' => $e->getMessage()];
                }

            }else{
                //Retornamos el error: 
                return ['delete' => false, 'error' => 'La llave no esta en uso.'];
            }

        }else{
            //Retornamos el error: 
            return ['delete' => false, 'error' => "Campo 'codigo_barras' o 'codigo_llave': No deben estar vacios."];
        }
        
    }
}
