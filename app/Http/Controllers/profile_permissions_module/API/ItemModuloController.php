<?php

namespace App\Http\Controllers\profile_permissions_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\profile_permissions_module\class\ItemModulo;
use App\Models\ItemModulo as ModelsItemModulo;
use Exception;
use Illuminate\Http\Request;

class ItemModuloController extends Controller
{
    // Metodo para retornar todos los modulos de la tabla de la DB:
    public function index()
    {
        // Realizamos la consulta a la tabla de la DB: 
        $model = ModelsItemModulo::select('id_item_modulo', 'nombre_item_modulo as nombre_modulo', 'item_modulo as modulo', 'url_item_modulo as url', 'icono_item_modulo as icon', 'orden')
                                ->get();

        // Retornamos una respuesta: 
        return ['query' => true, 'modules' => $model];
    }

    // Metodo para registrar un nuevo modulo en la tabla de la DB: 
    public function store(Request $request)
    {
        //Validamos que los argumentos no esten vacios: 
        if(!empty($request->input(key: 'item_module'))
            && !empty($request->input(key: 'nombre_item_module'))  
            && !empty($request->input(key: 'url_item_module')) 
            && !empty($request->input(key: 'icono_item_module')) 
            && !empty($request->input(key: 'orden'))){

            // Realizamos la consulta a la tabla de la DB:
            $model = ModelsItemModulo::where('nombre_item_modulo', $request->input(key: 'nombre_item_module'));

            //  Validamos que no exista ese item modulo en el sistema: 
            $validateItemModule = $model->first();

            //  Sino existe, realizamos la validacion de los argumentos recibidos: 
            if(!$validateItemModule){

                // Instanciamos la clase 'ItemModulo', para validar los argumentos recibidos: 
                $itemModule = new ItemModulo(item_module: $request->input(key: 'item_module'),
                                            nombre_item_module: $request->input(key: 'nombre_item_module'),
                                            url_item_module: $request->input(key: 'url_item_module'),
                                            icono_item_module: $request->input(key: 'icono_item_module'),
                                            orden: $request->input(key: 'orden'));

                // Enviamos la instancia 'itemModule' a la sesion 'item-modulo', con sus propiedades cargadas de datos: 
                $_SESSION['item-modulo'] = $itemModule;

                //  Validamos las propiedades: 
                $validateItemModuleData = $itemModule->registerData();

                // Si las propiedades han sido validadas, realizamos el registro: 
                if($validateItemModuleData['register']){

                    try{

                        // Realizamos el registro:
                        ModelsItemModulo::create(['item_modulo' => $request->input(key: 'item_module'),
                                                  'nombre_item_modulo' => $request->input(key: 'nombre_item_module'),
                                                  'url_item_modulo' => $request->input(key: 'url_item_module'),
                                                  'icono_item_modulo' => $request->input(key: 'icono_item_module'),
                                                  'orden' => $request->input(key: 'orden')]);

                        // Retornamos la respuesta: 
                        return $validateItemModuleData;

                    }catch(Exception $e){
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $e->getMessage()];
                    }

                }else{
                    // Retornamos el error:
                    return ['register' => false, 'error' => $validateItemModuleData['error']];
                }
            
            }else{
                //  Retornamos el error:
                return ['register' => false, 'error' => 'Ya existe ese modulo en el sistema.'];
            }

        }else{
            // Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'item_module' o 'nombre_item_module' o 'url_item_module' o 'icono_item_module' o 'orden': No deben estar vacios."];
        }
    }

    // Metodo para retornar el registro de un modulo en especifico: 
    public function show($nombre_modulo)
    {
        // Realizamos la consulta en la tabla de la DB:
        $model = ModelsItemModulo::select('id_item_modulo', 'nombre_item_modulo as nombre_modulo', 'item_modulo as modulo', 'url_item_modulo as url', 'icono_item_modulo as icon', 'orden')
                                ->where('nombre_item_modulo', $nombre_modulo);
    
        // Validamos si existe ese item modulo en la tabla de la DB:
        $validateItemModule = $model->first();

        // Si existe, lo retornamos: 
        if($validateItemModule){

            //Retornamos la respuesta: 
            return ['query' => true, 'module'=> $validateItemModule];

        }else{
            // Retornamos el error: 
            return ['query' =>  false, 'error' => 'No existe ese modulo en el sistema.'];
        }
    }

    // Metodo para actualizar el registro de un modulo en especifico: 
    public function update(Request $request)
    {
        //Validamos que los argumentos no esten vacios: 
        if(!empty($request->input(key: 'new_item_module')) 
            && !empty($request->input(key: 'nombre_item_module'))  
            && !empty($request->input(key: 'new_url_item_module')) 
            && !empty($request->input(key: 'new_icono_item_module')) 
            && !empty($request->input(key: 'new_orden'))){

            // Realizamos la consulta a la tabla de la DB:
            $model = ModelsItemModulo::where('nombre_item_modulo', $request->input(key: 'nombre_item_module'));

            //  Validamos que exista ese item modulo en el sistema: 
            $validateItemModule = $model->first();

            //  Si existe, realizamos la validacion de los argumentos recibidos: 
            if($validateItemModule){

                // Instanciamos la clase 'ItemModulo', para validar los argumentos recibidos: 
                $itemModule = new ItemModulo(item_module: $request->input(key: 'new_item_module'),
                                            nombre_item_module: $request->input(key: 'nombre_item_module'),
                                            url_item_module: $request->input(key: 'new_url_item_module'),
                                            icono_item_module: $request->input(key: 'new_icono_item_module'),
                                            orden: $request->input(key: 'new_orden'));

                // Enviamos la instancia 'itemModule' a la sesion 'item-modulo', con sus propiedades cargadas de datos: 
                $_SESSION['item-modulo'] = $itemModule;

                //  Validamos las propiedades: 
                $validateItemModuleData = $itemModule->registerData();

                // Si las propiedades han sido validadas, realizamos el registro: 
                if($validateItemModuleData['register']){

                    try{

                        // Realizamos el registro:
                        $model->update(['item_modulo' => $request->input(key: 'new_item_module'),
                                        'nombre_item_modulo' => $request->input(key: 'nombre_item_module'),
                                        'url_item_modulo' => $request->input(key: 'new_url_item_module'),
                                        'icono_item_modulo' => $request->input(key: 'new_icono_item_module'),
                                        'orden' => $request->input(key: 'new_orden')]);

                        // Retornamos la respuesta: 
                        return $validateItemModuleData;

                    }catch(Exception $e){
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $e->getMessage()];
                    }

                }else{
                    // Retornamos el error:
                    return $validateItemModuleData;
                }
            
            }else{
                //  Retornamos el error:
                return ['register' => false, 'error' => 'No existe ese modulo en el sistema.'];
            }

        }else{
            // Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'item_module' o 'url_item_module' o 'icono_item_module' o 'orden': No deben estar vacios."];
        }
    }

    // Metodo para eliminar el registro de un modulo en especifico: 
    public function destroy($item_modulo)
    {
        // Realizamos la consulta en la tabla de la DB:
        $model = ModelsItemModulo::select('id_item_modulo', 'item_modulo as modulo', 'url_item_modulo as url', 'icono_item_modulo as icon', 'orden')
                                ->where('item_modulo', $item_modulo);
    
        // Validamos si existe ese item modulo en la tabla de la DB:
        $validateItemModule = $model->first();

        // Si existe, lo eliminamos: 
        if($validateItemModule){

            try{

                // Eliminamos el modulo de la tabla de la DB: 
                $model->delete();

                // Retornamos una respuesta: 
                return ['delete' => true];

            }catch(Exception $e){
                // Retornamos el error: 
                return ['delete' => false, 'error' => $e->getMessage()];
            }

        }else{
            // Retornamos el error: 
            return ['delete' =>  false, 'error' => 'No existe ese modulo en el sistema.'];
        }
    }
}
