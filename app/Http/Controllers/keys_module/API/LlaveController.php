<?php

namespace App\Http\Controllers\keys_module\API;

use App\Http\Controllers\Controller;
use App\Models\Llave;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LlaveController extends Controller
{
    //Metodo para retornar todos los registros de la tabla de la DB: 
    public function index()
    {
        //Realizamos la consulta en la tabla de la DB: 
        $model = Llave::select('id_llave',  'ambiente_id as ambiente', 'url_codigo_qr')->get();

        try{

            foreach($model as $key){

                //Reemplazamos la clave foranea 'ambiente_id', por el valor que contiene el campo 'nombre_ambiente' en la tabla 'ambientes' de la DB: 
                $key->ambiente = $key->environments->nombre_ambiente;

                //Luego eliminamos la demas informacion que no utilizaremos de la tabla 'ambientes' de la DB: 
                unset($key->environments);
            }

            //Retornamos la respuesta: 
            return ['query' => true, 'keys' => $model];
        }catch(Exception $e)
        {
            return $e->getMessage();
        }
    }

    //Metodo para registrar una nueva llave en la tabla dde la DB: 
    public function store(Request $request)
    {
        //Si el argumento recibido contiene caracteres tipo mayusculas, los convertimos en tipo minusculas para llevar una nomenclatura estandar: 
        $nombre_ambiente = strtolower($request->input(key: 'ambiente'));

        //Validamos que el argumento no este vacio: 
        if(!empty($nombre_ambiente)){

            //Instanciamos el controlador del modelo 'Ambiente', para validar que exista el ambiente: 
            $environmentController = new AmbienteController;

            //Validamos que exista el ambiente: 
            $validateEnvironment = $environmentController->show(ambiente: $nombre_ambiente);
            
            //Si existe, extraemos su 'id' y realizamos el registro de la nueva llave en la tabla de la DB: 
            if($validateEnvironment['query']){

                try{

                    //Extraemos el 'id': 
                    $ambiente_id = $validateEnvironment['environment']['id_ambiente'];

                    //Realizamos la consulta a la tabla de la DB: 
                    $model = Llave::where('ambiente_id', $ambiente_id);

                    //Validamos que no exista esa llave: 
                    $validateKey = $model->first();

                    //Sino existe, realizamos el nuevo registro:
                    if(!$validateKey){

                        //El codigo de la llave sera el 'id' del ambiente relacionado con la llave: 
                        $codigo_llave = $ambiente_id.'y'.$ambiente_id.'$'.$ambiente_id.'Q'.$ambiente_id;

                        
                        //Generamos un 'codigo QR' del campo 'codigo_llave': 
                        QrCode::size(300)->backgroundColor(255,90,0)->errorCorrection('H')->generate($codigo_llave, ".././storage/app/public/codigo_qr/$codigo_llave.svg");

                        //Asignamos la url donde esta ubicado el nuevo 'codigo QR': 
                        $url_codigo_qr = "/storage/codigo_qr/$codigo_llave.svg";

                        //Realizamos el nuevo registro: 
                        Llave::create(['ambiente_id' => $ambiente_id,
                                    'url_codigo_qr' => $url_codigo_qr,
                                    'codigo_llave' => $codigo_llave]);

                        //Retornamos la respuesta: 
                        return ['register' => true];

                    }else{
                        //Retornamos el error: 
                        return ['register' => false, 'error' => 'Ya existe esa llave en el sistema.'];
                    }
                }catch(Exception $e){
                    //Retornamos el error: 
                    return ['register' => false, 'error' => $e->getMessage()];
                }
            }else{
                //Retornamos el error: 
                return ['register' => false, 'error' => $validateEnvironment['error']];
            }
        }else{
            //Retornamos el error: 
            return ['register' => false, 'error' => 'Campo ambiente: No debe estar vacio.'];
        }
    }

    //Metodo para retornar la informacion de una llave especifica: 
    public function show($codigo_llave)
    {
        //Realizamos la consulta en la tabla de la DB: 
        $model = Llave::select('id_llave', 'ambiente_id as ambiente', 'url_codigo_qr')->where('codigo_llave', $codigo_llave);
        
        //Validamos si existe esa llave en la tabla de la DB: 
        $validateKey = $model->first();

        //Si existe, retornamos la informacion de la llave: 
        if($validateKey){

            //Reemplazamos la clave foranea 'ambiente_id', por el valor que contiene el campo 'nombre_ambiente' en la tabla 'ambientes' de la DB: 
            $validateKey->ambiente = $validateKey->environments->nombre_ambiente;

            //Luego eliminamos la demas informacion que no utilizaremos de la tabla 'ambientes' de la DB: 
            unset($validateKey->environments);

            //Retornamos una respuesta: 
            return ['query' =>  true, 'key' => $validateKey]; 
        }else{
            //Eliminamos el 'codigo QR', en el caso de que pertenezca a una llave eliminada: 
            Storage::delete("public/codigo_qr/$codigo_llave.svg");
            //Retornamos el error: 
            return ['query' => false, 'error' => 'No existe esa llave en el sistema.'];
        }
    }
}