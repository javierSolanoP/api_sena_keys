<?php

namespace App\Http\Controllers\profile_module\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\profile_module\API\PerfilController;
use App\Http\Controllers\users_module\API\UserController;
use Exception;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index($form)
    {
        switch($form){

            case 'perfil': 
                $profileController = new PerfilController;
                return $profileController->index();
            break; 

            case 'user': 
                $userController = new UserController;
                return $userController->index();
            break;
        }
    }

    public function store(Request $request)
    {
        switch($request->input(key: 'form')){

            case 'perfil': 
                $profileController = new PerfilController;
                return $profileController->store(nombre_perfil: $request->input(key: 'nombre_perfil'),
                                                 tipo_permiso: $request->input(key: 'tipo_permiso'));
            break; 

            case 'user': 
                $userController = new UserController;
                return $userController->store(identificacion: $request->input(key: 'identificacion'),
                                              nombre: $request->input(key: 'nombre'),
                                              apellidos: $request->input(key: 'apellidos'),
                                              codigo_barras: $request->input(key: 'codigo_barras'),
                                              email: $request->input(key: 'email'),
                                              password: $request->input(key: 'password'),
                                              confirmPassword: $request->input(key: 'confirmPassword'),
                                              perfil: $request->input(key: 'perfil'));
            break;
        }
    }

    public function show($form, $data)
    {
        switch($form){

            case 'perfil': 
                $profileController = new PerfilController;
                return $profileController->show(nombre_perfil: $data);
            break; 

            case 'user': 
                $userController = new UserController;
                return $userController->show(codigo_barras: $data);
            break;
        }
    }

    public function destroy($form, $data)
    {
        switch($form){

            case 'perfil': 
                $profileController = new PerfilController;
                return $profileController->destroy(nombre_perfil: $data);
            break; 

            case 'user': 
                $userController = new UserController;
                return $userController->destroy(codigo_barras: $data);
            break;
        }
    }
}
