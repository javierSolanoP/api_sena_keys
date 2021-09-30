<?php

use App\Http\Controllers\keys_assignment_module\API\AsignacionLlaveUsuario;
use App\Http\Controllers\keys_module\API\LlaveController;
use App\Http\Controllers\LlaveController as ControllersLlaveController;
use App\Http\Controllers\profile_module\API\TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get(uri: '/test/{form}', action: [TestController::class, 'index']);
Route::post(uri: '/test', action: [TestController::class, 'store']);
Route::get(uri: '/test/{form}/{data}', action: [TestController::class, 'show']);
Route::delete(uri: '/test/{form}/{data}', action: [TestController::class, 'destroy']);

Route::apiResource(name: '/zonas', controller: 'App\Http\Controllers\keys_module\API\ZonaController');
Route::apiResource(name: '/ambientes', controller: 'App\Http\Controllers\keys_module\API\AmbienteController');
Route::apiResource(name: '/llaves', controller: 'App\Http\Controllers\keys_module\API\LlaveController');

Route::apiResource(name: '/asignacion', controller: 'App\Http\Controllers\keys_assignment_module\API\AsignacionLlaveUsuario');
Route::post(uri: '/update-stock-keys', action: [AsignacionLlaveUsuario::class, 'updateStock']);
Route::get(uri: '/stock-keys', action: [AsignacionLlaveUsuario::class, 'stock']);

Route::apiResource(name: '/modulos', controller: 'App\Http\Controllers\profile_permissions_module\API\ItemModuloController');
Route::apiResource(name: '/modulos-perfiles', controller: 'App\Http\Controllers\profile_permissions_module\API\ItemModuloPerfilController');

Route::post(uri: '/images-keys', action: [ControllersLlaveController::class, 'store']);