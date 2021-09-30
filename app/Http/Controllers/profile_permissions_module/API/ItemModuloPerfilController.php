<?php

namespace App\Http\Controllers\profile_permissions_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\profile_module\API\PerfilController;
use App\Http\Controllers\profile_permissions_module\class\ItemModuloPerfil;
use App\Models\ItemModuloPerfil as ModelsItemModuloPerfil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemModuloPerfilController extends Controller
{
    // Metodo para retornar todos los registros de la tabla de la DB:
    public function index()
    {
        // Realizamos la consulta a la tabla de la DB: 
        $model = DB::table(table: 'item_modulo_perfils', as: 'modulo_perfils')
                
                // Realizamos la consulta a la tabla del modelo 'Perfil':
                ->join(table: 'perfils', first: 'modulo_perfils.perfil_id', operator: '=', second: 'perfils.id_perfil')
                
                // Realizamos la consulta a la tabla del modelo 'ItemModulo':
                ->join(table: 'item_modulos', first: 'modulo_perfils.item_modulo_id', operator: '=', second: 'item_modulos.id_item_modulo')
                
                //  Seleccionamos los campos que requerimos de todas las tablas: 
                ->select('modulo_perfils.item_modulo_id as item_modulo', 'perfils.nombre_perfil as perfil', 'modulo_perfils.perfil_id')
                
                // Ordenamos la consulta por el campo 'orden' de la tabla del modelo 'ItemModulo': 
                ->orderBy(column: 'item_modulos.orden', direction: 'asc')
                
                // Obtenemos los registros: 
                ->get()

                // Los agrupamos por el campo 'nombre_perfil' del modelo 'Pefil': 
                ->groupBy('perfil'); 

        // Retornamos la respuesta: 
        return ['query' => true, 'module_perfils' => $model];
    }

    // Metodo para realizar un nuevo registro en la tabla de la DB: 
    public function store(Request $request)
    {
        // Validamos que los argumentos no esten vacios: 
        if(!empty($request->input(key:'item_modulo')) && !empty($request->input(key:'perfil'))){

            // Si el argumento 'perfil' contiene caracteres de tipo mayusculas. Los pasamos a minusculas pra llevar una nomenclatura estandar:
            $nombre_perfil = strtolower($request->input(key:'perfil'));

            // Instanciamos el controlador del modelo 'Perfil', para validar que exista el perfil: 
            $profileController = new PerfilController;

            // Validamos que exista el perfil:
            $validateProfile = $profileController->show(nombre_perfil: $nombre_perfil);

            // Si existe, extraemos su 'id' y realizamos la consulta a la tabla del modulo 'ItemModuloPerfil' de la DB:
            if($validateProfile['query']){

                // Extraemos su 'id' y se lo asignamos a la variable:
                $perfil_id = $validateProfile['profile']['id_perfil'];

                // Realizamos la consulta a la tabla de la DB, para validar que no exista el perfil: 
                $model = ModelsItemModuloPerfil::where('item_modulo_id', $request->input(key:'item_modulo'))
                                               ->where('perfil_id', $perfil_id)
                                               ->first();

                // Sino existe ese registro en la tabla de la DB, validamos lo argumentos recibidos:
                if(!$model){

                    // Instanciamos la clase 'ItemModuleProfile', para validar los argumentos: 
                    $itemModuleProfile = new ItemModuloPerfil(item_modulo: $request->input(key:'item_modulo'),
                                                            perfil: $nombre_perfil);

                    // Asignamos la instancia a la sesion 'item-modulo-perfil', con sus propiedades cargadas de datos: 
                    $_SESSION['item-modulo-perfil'] = $itemModuleProfile;

                    // Validamos las propiedades: 
                    $validateItemModuleProfile = $itemModuleProfile->registerData();

                    // Si las propiedades han sido validadas, realizamos el registro: 
                    if($validateItemModuleProfile['register']){

                        try{

                            // Realizamos el registro en la tabla de la DB:
                            ModelsItemModuloPerfil::create(['item_modulo_id' => $request->input(key:'item_modulo'),
                                                            'perfil_id'=> $perfil_id]);

                            // Retornamos la respuesta:
                            return $validateItemModuleProfile;

                        }catch(Exception $e){
                            // Retornamos el error: 
                            return ['register' => false, 'error' => $e->getMessage()];
                        }

                    }else{
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $validateItemModuleProfile['error']];
                    }

                }else{
                    // Retornamos el error: 
                    return ['register' => false, 'error' => 'Ya existe esa asignacion en el sistema.'];
                }

            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => $validateProfile['error']];
            }

        }else{
            // Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'item_modulo' o 'perfil': No deben estar vacios."];
        }
    }

    // Metodo para retornar un registro especifico de la tabla de la DB:
    public function show($id_perfil)
    {
        // Realizamos la consulta a la tabla de la DB: 
        $model = DB::table(table: 'item_modulo_perfils', as: 'modulo_perfils')

                //  Referenciamos el registro especifico: 
                ->where(column: 'perfil_id', operator: '=', value: $id_perfil)
                
                // Realizamos la consulta a la tabla del modelo 'Perfil':
                ->join(table: 'perfils', first: 'modulo_perfils.perfil_id', operator: '=', second: 'perfils.id_perfil')
                
                // Realizamos la consulta a la tabla del modelo 'ItemModulo':
                ->join(table: 'item_modulos', first: 'modulo_perfils.item_modulo_id', operator: '=', second: 'item_modulos.id_item_modulo')
                
                //  Seleccionamos los campos que requerimos de todas las tablas: 
                ->select('modulo_perfils.item_modulo_id as item_modulo', 'perfils.nombre_perfil as perfil', 'modulo_perfils.perfil_id')
                
                // Ordenamos la consulta por el campo 'orden' de la tabla del modelo 'ItemModulo': 
                ->orderBy(column: 'item_modulos.orden', direction: 'asc')
                
                // Obtenemos los registros: 
                ->get()

                // Los agrupamos por el campo 'nombre_perfil' del modelo 'Pefil': 
                ->groupBy('perfil'); 

        // Retornamos la respuesta: 
        return ['query' => true, 'module_perfils' => $model];
    }

    // Metodo para actualizar un registro especifico en la tabla de la DB:
    public function update(Request $request)
    {
        // Validamos que los argumentos no esten vacios: 
        if(!empty($request->input(key:'item_modulo')) && !empty($request->input(key:'perfil'))){

            // Si el argumento 'perfil' contiene caracteres de tipo mayusculas. Los pasamos a minusculas pra llevar una nomenclatura estandar:
            $nombre_perfil = strtolower($request->input(key:'perfil'));

            // Instanciamos el controlador del modelo 'Perfil', para validar que exista el perfil: 
            $profileController = new PerfilController;

            // Validamos que exista el perfil:
            $validateProfile = $profileController->show(nombre_perfil: $nombre_perfil);

            // Si existe, extraemos su 'id' y realizamos la consulta a la tabla del modulo 'ItemModuloPerfil' de la DB:
            if($validateProfile['query']){

                // Extraemos su 'id' y se lo asignamos a la variable:
                $perfil_id = $validateProfile['profile']['id_perfil'];

                // Realizamos la consulta a la tabla de la DB, para validar que exista el registro: 
                $model = ModelsItemModuloPerfil::where('item_modulo_id', $request->input(key:'item_modulo'))
                                                ->where('perfil_id', $perfil_id)
                                                ->first();

                // Si existe ese registro en la tabla de la DB, validamos lo argumentos recibidos:
                if($model){

                    // Instanciamos la clase 'ItemModuleProfile', para validar los argumentos: 
                    $itemModuleProfile = new ItemModuloPerfil(item_modulo: $request->input(key:'item_modulo'),
                                                            perfil: $nombre_perfil);

                    // Asignamos la instancia a la sesion 'item-modulo-perfil', con sus propiedades cargadas de datos: 
                    $_SESSION['item-modulo-perfil'] = $itemModuleProfile;

                    // Validamos las propiedades: 
                    $validateItemModuleProfile = $itemModuleProfile->registerData();

                    // Si las propiedades han sido validadas, realizamos el registro: 
                    if($validateItemModuleProfile['register']){

                        try{

                            // Realizamos el registro en la tabla de la DB:
                            $model->update(['item_modulo_id' => $request->input(key:'item_modulo'),
                                            'perfil_id'=> $perfil_id]);

                            // Retornamos la respuesta:
                            return $validateItemModuleProfile;

                        }catch(Exception $e){
                            // Retornamos el error: 
                            return ['register' => false, 'error' => $e->getMessage()];
                        }

                    }else{
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $validateItemModuleProfile['error']];
                    }

                }else{
                    // Retornamos el error: 
                    return ['register' => false, 'error' => 'No existe esa asignacion en el sistema.'];
                }

            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => $validateProfile['error']];
            }

        }else{
            // Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'item_modulo' o 'perfil': No deben estar vacios."];
        }
    }
}
