<?php

namespace App\Http\Controllers\keys_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\keys_module\class\Ambiente as ClassAmbiente;
use App\Models\Ambiente;
use Exception;
use Illuminate\Http\Request;

class AmbienteController extends Controller
{
    //Metodo para retornar todos los registros de la tabla en la DB: 
    public function index()
    {
        //Realizamos la consulta en la DB: 
        $model = Ambiente::select('id_ambiente', 'zona_id as zona', 'nombre_ambiente')->get();

        foreach($model as $environment){
            $environment->zona = $environment->zone->nombre_zona;
            unset($environment->zone);
        }

        //Retornamos la respuesta: 
        return ['query' => true, 'environments' => $model];
    }

    //Metodo para registrar un nuevo ambiente en la tabla de la DB: 
    public function store(Request $request)
    {
        //Si los argumentos tienen caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $nombre_zona     = strtolower($request->input(key: 'nombre_zona')); 
        $nombre_ambiente = strtolower($request->input(key: 'nombre_ambiente'));
        $description     = strtolower($request->input(key: 'description'));

        //Validamos que el argumento 'nombre_zona' no este vacio: 
        if(!empty($nombre_zona)){
            
            //Instanciamos el controlador del modelo 'Zona', para validar que exista la zona: 
            $zoneController = new ZonaController;

            //Validamos que exista: 
            $validateZone = $zoneController->show(nombre_zona: $nombre_zona);

            //Si existe extraemos su 'id': 
            if($validateZone['query']){
                //Extraemos el 'id': 
                $id_zona = $validateZone['zone']['id_zona'];
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateZone['error']];
            }
        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'Campo nombre_zona: Debe contener informacion.'];
        }

        //Realizamos la consulta a la DB: 
        $model = Ambiente::where('nombre_ambiente', $nombre_ambiente);

        //Validamos que no exista ese ambiente en la tabla de la DB: 
        $validateEnvironment = $model->first();

        //Sino existe, validamos los argumentos recibidos: 
        if(!$validateEnvironment){

            //Instanciamos la clase 'Ambiente', para validar los argumentos recibidos: 
            $environment = new ClassAmbiente(nombre_ambiente: $nombre_ambiente,
                                             description: $description);

            //Enviamos la instancia al trait 'MethodsEnvironment', con las propiedades cargadas: 
            $_SESSION['register'] = $environment;

            //Realizamos la validacion: 
            $validateData = $environment->registerData();

            //Si los argumentos son validados correctamente,  realizamos el registro: 
            if($validateData){

                try{

                    //Por defecto, el estado de ambiente sera 'activo': 
                    static $state = 'activo';

                    //Realizamos el registro: 
                    Ambiente::create(['zona_id' => $id_zona,
                                      'nombre_ambiente' => $nombre_ambiente,
                                      'description' => $description,
                                      'estado' => $state]);

                    //Retornamos la respuesta: 
                    return ['register' => true];

                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateData['error']];
            }
        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'Ya existe ese ambiente en el sistema.'];
        }
    }

    //Metodo para retornar la informacion de un ambiente especifico:
    public function show($ambiente)
    {
        //Si el argumento tiene caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $environmentName = strtolower($ambiente); 

        //Realizamos la consulta en la DB: 
        $model = Ambiente::select('id_ambiente', 'nombre_ambiente', 'zona_id as zona', 'description')->where('nombre_ambiente', $environmentName);

        //Validamos si no existe ese ambiente en la tabla de la DB: 
        $validateEnvironment = $model->first();

        //Si existe, validamos el argumento recibido:
        if($validateEnvironment){

            //Cambiamos la clave foranea de 'zona_id', por el nombre de la zona coreesponidente a la clave: 
            $validateEnvironment->zona = $validateEnvironment->zone->nombre_zona;

            //Eliminamos la demas informacion que no requerimos de la tabla 'llaves': 
            unset($validateEnvironment->zone);

            //Retornamos la respuesta: 
            return ['query' => true, 'environment' => $validateEnvironment];
        }else{
            //Retornamos el error: 
            return ['query' => false, 'error' => 'No existe ese ambiente en el sistema.'];
        }
    }

    //Metodo para actualizar la informacion de un ambiente especifico: 
    public function update(Request $request)
    {
        //Si los argumentos tienen caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $nombre_ambiente     = strtolower($request->input(key: 'nombre_ambiente'));
        $new_nombre_ambiente = strtolower($request->input(key: 'new_nombre_ambiente'));
        $new_description     = strtolower($request->input(key: 'new_description'));

        //Realizamos la consulta a la DB: 
        $model = Ambiente::where('nombre_ambiente', $nombre_ambiente);

        //Validamos que exista ese ambiente en la tabla de la DB: 
        $validateEnvironment = $model->first();

        //Si existe, validamos los argumentos recibidos: 
        if($validateEnvironment){

            //Instanciamos la clase 'Ambiente', para validar los argumentos recibidos: 
            $environment = new ClassAmbiente(nombre_ambiente: $new_nombre_ambiente,
                                             description: $new_description);

            //Enviamos la instancia al trait 'MethodsEnvironment', con las propiedades cargadas: 
            $_SESSION['register'] = $environment;

            //Realizamos la validacion: 
            $validateData = $environment->updateData();

            //Si los argumentos son validados correctamente,  realizamos el registro: 
            if($validateData['register']){

                try{

                    //Por defecto, el estado de ambiente sera 'activo': 
                    static $state = 'activo';

                    //Realizamos el registro: 
                    $model->update(['nombre_ambiente' => $new_nombre_ambiente,
                                    'description' => $new_description,
                                    'estado' => $state]);

                    //Retornamos la respuesta: 
                    return ['register' => true];

                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateData['error']];
            }
        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'No existe ese ambiente en el sistema.'];
        }
    }

    //Metodo para eliminar un ambiente especifico: 
    public function destroy($ambiente)
    {
        //Si el argumento tiene caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $environmentName = strtolower($ambiente); 

        //Realizamos la consulta en la DB: 
        $model = Ambiente::where('nombre_ambiente', $environmentName);

        //Validamos si no existe ese ambiente en la tabla de la DB: 
        $validateEnvironment = $model->first();

        //Si existe, validamos el argumento recibido:
        if($validateEnvironment){

            try{
                
                //Eliminamos el registro de la zona en la tabla de la DB: 
                $model->delete();

                //Retornamos la respuesta: 
                return ['delete' => true];

            }catch(Exception $e){
                //Retornamos el error: 
                return ['delete' => false, 'error' => $e->getMessage()];
            }
        }else{
            //Retornamos el error: 
            return ['delete' => false, 'error' => 'No existe ese ambiente en el sistema.'];
        }
    }
}
