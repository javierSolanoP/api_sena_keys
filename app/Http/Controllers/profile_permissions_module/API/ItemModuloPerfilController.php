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
                ->select('item_modulos.nombre_item_modulo as nombre_modulo', 'modulo_perfils.item_modulo_id as item_modulo', 'perfils.nombre_perfil as perfil', 'modulo_perfils.perfil_id')
                
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
        if(!empty($request->input(key:'nombre_modulo')) && !empty($request->input(key:'perfil'))){

            // Si los argumentos contienen caracteres de tipo mayusculas. Los pasamos a minusculas pra llevar una nomenclatura estandar:
            $nombre_modulo = strtolower($request->input(key:'nombre_modulo'));
            $nombre_perfil = strtolower($request->input(key:'perfil'));

            // Instanciamos el controlador del modelo 'Perfil' y 'ItemModulo, para validar que existan en el sistema: 
            $itemModuleController = new ItemModuloController;
            $profileController = new PerfilController;

            // Validamos que existan:
            $validateItemModule = $itemModuleController->show(nombre_modulo: $nombre_modulo);
            $validateProfile    = $profileController->show(nombre_perfil: $nombre_perfil);

            // Si existe el modulo, extraemos su 'id': 
            if($validateItemModule['query']){

                // Extraemos su 'id': 
                $item_module_id = $validateItemModule['module']['id_item_modulo'];
            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => $validateItemModule['error']];
            }

            // Si existe el perfil, extraemos su 'id':
            if($validateProfile['query']){

                // Extraemos su 'id' y se lo asignamos a la variable:
                $perfil_id = $validateProfile['profile']['id_perfil'];
            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => $validateProfile['error']];
            }

            // Realizamos la consulta a la tabla de la DB, para validar que no exista el perfil: 
            $model = ModelsItemModuloPerfil::where('item_modulo_id', $item_module_id)
                                           ->where('perfil_id', $perfil_id)
                                           ->first();

            // Sino existe ese registro en la tabla de la DB, validamos lo argumentos recibidos:
            if(!$model){

                // Instanciamos la clase 'ItemModuleProfile', para validar los argumentos: 
                $itemModuleProfile = new ItemModuloPerfil(nombre_modulo: $request->input(key:'item_modulo'),
                                                          perfil: $nombre_perfil);

                // Asignamos la instancia a la sesion 'item-modulo-perfil', con sus propiedades cargadas de datos: 
                $_SESSION['item-modulo-perfil'] = $itemModuleProfile;

                // Validamos las propiedades: 
                $validateItemModuleProfile = $itemModuleProfile->registerData();

                // Si las propiedades han sido validadas, realizamos el registro: 
                if($validateItemModuleProfile['register']){

                    try{

                        // Realizamos el registro en la tabla de la DB:
                        ModelsItemModuloPerfil::create(['item_modulo_id' => $item_module_id,
                                                        'perfil_id'=> $perfil_id]);

                        // Retornamos la respuesta:
                        return $validateItemModuleProfile;

                    }catch(Exception $e){
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $e->getMessage()];
                    }

                }else{
                    // Retornamos el error: 
                    return $validateItemModuleProfile;
                }

            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => 'Ya existe esa asignacion en el sistema.'];
            }

        }else{
            // Retornamos el error: 
            return ['register' => false, 'error' => "Campo 'nombre_modulo' o 'perfil': No deben estar vacios."];
        }
    }

    // Metodo para retornar un registro especifico de la tabla de la DB:
    public function show($id_perfil)
    {
        // Realizamos la consulta a la tabla de la DB: 
        $model = DB::table(table: 'item_modulo_perfils', as: 'modulo_perfils')

                // Referenciamos el registro especifico: 
                ->where(column: 'perfil_id', operator: '=', value: $id_perfil)
                
                // Realizamos la consulta a la tabla del modelo 'Perfil':
                ->join(table: 'perfils', first: 'modulo_perfils.perfil_id', operator: '=', second: 'perfils.id_perfil')
                
                // Realizamos la consulta a la tabla del modelo 'ItemModulo':
                ->join(table: 'item_modulos', first: 'modulo_perfils.item_modulo_id', operator: '=', second: 'item_modulos.id_item_modulo')
                
                //  Seleccionamos los campos que requerimos de todas las tablas: 
                ->select('item_modulos.nombre_item_modulo as nombre_modulo', 'modulo_perfils.item_modulo_id as item_modulo', 'perfils.nombre_perfil as perfil', 'modulo_perfils.perfil_id')
                
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
        if(!empty($request->input(key:'nombre_modulo')) 
           && !empty($request->input(key:'perfil'))
           && !empty($request->input(key:'new_nombre_modulo'))
           && !empty($request->input(key:'new_perfil'))){

            // Si los argumentos contienen caracteres de tipo mayusculas. Los pasamos a minusculas pra llevar una nomenclatura estandar:
            $nombre_modulo = strtolower($request->input(key:'nombre_modulo'));
            $nombre_perfil = strtolower($request->input(key:'perfil'));
            $new_nombre_modulo = strtolower($request->input(key:'new_nombre_modulo'));
            $new_nombre_perfil = strtolower($request->input(key:'new_perfil'));

            // Instanciamos el controlador del modelo 'Perfil' y 'ItemModulo, para validar que existan en el sistema: 
            $itemModuleController = new ItemModuloController;
            $profileController = new PerfilController;

            // Validamos que existan:
            $validateItemModule = $itemModuleController->show(nombre_modulo: $nombre_modulo);
            $validateProfile    = $profileController->show(nombre_perfil: $nombre_perfil);

            // Si existe el modulo, extraemos su 'id': 
            if($validateItemModule['query']){

                // Extraemos su 'id': 
                $item_module_id = $validateItemModule['module']['id_item_modulo'];
            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => $validateItemModule['error']];
            }

            // Si existe el perfil, extraemos su 'id':
            if($validateProfile['query']){

                // Extraemos su 'id' y se lo asignamos a la variable:
                $perfil_id = $validateProfile['profile']['id_perfil'];
            }else{
                // Retornamos el error: 
                return ['register' => false, 'error' => $validateProfile['error']];
            }

            // Realizamos la consulta a la tabla de la DB, para validar que exista el perfil con ese modulo asignado: 
            $model = ModelsItemModuloPerfil::where('item_modulo_id', $item_module_id)
                                           ->where('perfil_id', $perfil_id);
                                        
            // Validamos que exista ese registro: 
            $validateItemModuleProfileModel = $model->first();

            // Si existe ese registro en la tabla de la DB, validamos lo argumentos recibidos:
            if($validateItemModuleProfileModel){

                // Instanciamos la clase 'ItemModuleProfile', para validar los argumentos: 
                $itemModuleProfile = new ItemModuloPerfil(nombre_modulo: $new_nombre_modulo,
                                                          perfil: $new_nombre_perfil);

                // Asignamos la instancia a la sesion 'item-modulo-perfil', con sus propiedades cargadas de datos: 
                $_SESSION['item-modulo-perfil'] = $itemModuleProfile;

                // Validamos las propiedades: 
                $validateItemModuleProfile = $itemModuleProfile->updateData();

                // Si las propiedades han sido validadas, realizamos el registro: 
                if($validateItemModuleProfile['register']){

                    // Validamos que existan:
                    $new_validateItemModule = $itemModuleController->show(nombre_modulo: $new_nombre_modulo);
                    $new_validateProfile    = $profileController->show(nombre_perfil: $new_nombre_perfil);

                    // Si existe el modulo, extraemos su 'id': 
                    if($new_validateItemModule['query']){

                        // Extraemos su 'id': 
                        $new_item_module_id = $new_validateItemModule['module']['id_item_modulo'];
                    }else{
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $new_validateItemModule['error']];
                    }

                    // Si existe el perfil, extraemos su 'id':
                    if($new_validateProfile['query']){

                        // Extraemos su 'id' y se lo asignamos a la variable:
                        $new_perfil_id = $new_validateProfile['profile']['id_perfil'];
                    }else{
                        // Retornamos el error: 
                        return ['register' => false, 'error' => $new_validateProfile['error']];
                    }

                    try{

                        // Realizamos el registro en la tabla de la DB:
                        $model->update(['item_modulo_id' => $new_item_module_id,
                                        'perfil_id'=> $new_perfil_id]);

                        // Retornamos la respuesta:
                        return $validateItemModuleProfile;

                    }catch(Exception $e){
                        // Retornamos el error: 
                        return ['update' => false, 'error' => $e->getMessage()];
                    }

                }else{
                    // Retornamos el error: 
                    return $validateItemModuleProfile;
                }

            }else{
                // Retornamos el error: 
                return ['update' => false, 'error' => 'No existe esa asignacion en el sistema.'];
            }

        }else{
            // Retornamos el error: 
            return ['update' => false, 'error' => "Campo 'nombre_modulo' o 'perfil' o 'new_nombre_modulo' o 'new_perfil': No deben estar vacios."];
        }
    }
}
