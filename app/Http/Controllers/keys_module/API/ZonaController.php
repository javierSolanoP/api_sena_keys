<?php

namespace App\Http\Controllers\keys_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\keys_module\class\Zona as ClassZona;
use App\Models\Zona;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Translation\MessageCatalogue;

class ZonaController extends Controller
{
    //Metodo para retornar todos los registros de la tabla en la DB: 
    public function index()
    {
        //Realizamos la consulta en la DB: 
        $model = Zona::all();

        //Retornamos la respuesta: 
        return ['query' => true, 'zones' => $model];
    }

    //Metodo para registrar una nueva zona en la tabla de la DB: 
    public function store(Request $request)
    {
        //Si el argumento tiene caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $zoneName = strtolower($request->input(key: 'nombre_zona')); 

        //Realizamos la consulta en la DB: 
        $model = Zona::where('nombre_zona', $zoneName);

        //Validamos si no existe esa zona en la tabla de la DB: 
        $validateZone = $model->first();

        //Sino existe, validamos el argumento recibido:
        if(!$validateZone){

            //Instanciamos la clase 'Zona', para validar el argumento: 
            $zone = new ClassZona(nombre_zona: $zoneName);

            //Enviamos la instancia al trait 'MethodsZone', con las propiedades cargadas: 
            $_SESSION['register'] = $zone;

            //Realizamos la validacion: 
            $validateZoneName = $zone->registerData();

            //Si el argumento es validado correctamente, realizamos el registro: 
            if($validateZoneName['register']){

                try{

                    //Realizamos el registro: 
                    Zona::create(['nombre_zona' => $zoneName]);

                    //Retornamos la respuesta: 
                    return ['register' => true];

                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateZoneName['error']];
            }

        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'Ya existe esa zona en el sistema.'];
        }
    }

    //Metodo para retornar la informacion de una zona especifica: 
    public function show($nombre_zona)
    {
        //Si el argumento tiene caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $zoneName = strtolower($nombre_zona); 

        //Realizamos la consulta en la DB: 
        $model = Zona::where('nombre_zona', $zoneName);

        //Validamos si no existe esa zona en la tabla de la DB: 
        $validateZone = $model->first();

        //Si existe, validamos el argumento recibido:
        if($validateZone){

            //Retornamos la respuesta: 
            return ['query' => true, 'zone' => $validateZone];
        }else{
            //Retornamos el error: 
            return ['query' => false, 'error' => 'No existe esa zona en el sistema.'];
        }
    }

    //Metodo para actualizar la informacion de una zona especifica: 
    public function update(Request $request)
    {
        //Si los argumentos tienen caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $zoneName    = strtolower($request->input(key: 'nombre_zona'));
        $newZoneName = strtolower($request->input(key: 'new_nombre_zona'));

        //Realizamos la consulta en la DB: 
        $model = Zona::where('nombre_zona', $zoneName);
 
        //Validamos si no existe esa zona en la tabla de la DB: 
        $validateZone = $model->first();
 
        //Si existe, validamos el argumento recibido:
        if($validateZone){
 
            //Instanciamos la clase 'Zona', para validar el argumento: 
            $zone = new ClassZona(nombre_zona: $newZoneName);
 
            //Enviamos la instancia al trait 'MethodsZone', con las propiedades cargadas: 
            $_SESSION['register'] = $zone;
 
            //Realizamos la validacion: 
            $validateZoneName = $zone->registerData();
 
            //Si el argumento es validado correctamente, realizamos la actualizacion: 
            if($validateZoneName['register']){
 
                try{
 
                    //Realizamos la actualizacion: 
                    $model->update(['nombre_zona' => $newZoneName]);
 
                    //Retornamos la respuesta: 
                    return ['register' => true];
 
                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateZoneName['error']];
            }
        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'No existe esa zona en el sistema.'];
        }
    }

    //Metodo para eliminar una zona especifica: 
    public function destroy($nombre_zona)
    {
        //Si el argumento tiene caracteres tipo mayuscula, los convertimos en tipo minuscula. Para seguir una nomenclatura estandar: 
        $zoneName = strtolower($nombre_zona); 

        //Realizamos la consulta en la DB: 
        $model = Zona::where('nombre_zona', $zoneName);
 
        //Validamos si no existe esa zona en la tabla de la DB: 
        $validateZone = $model->first();
 
        //Si existe, validamos el argumento recibido:
        if($validateZone){
 
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
            return ['delete' => false, 'error' => 'No existe esa zona en el sistema.'];
        }
    }
}
