<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Mantenimientos\AreasController;
use App\Http\Controllers\Mantenimientos\TipoAccesoController;
use App\Http\Controllers\Mantenimientos\MenuController;
use App\Http\Controllers\Incidencias\IncidenciaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', url('/inicio'));
Route::get('/inicio', [LoginController::class, 'viewLogin'])->name('login')->middleware('guest');
Route::post('/inicio', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);


Route::get('/soporte', function () {
    $incidenciaController = new IncidenciaController();
    $resumenInc = $incidenciaController->resumenInc();
    
    return view('dashboard.soporte.panel', ['resumenInc' => $resumenInc]);
})->middleware('auth');

Route::get('/viewListMenu', [MenuController::class, 'viewListMenu']);
Route::get('/extractPermisos', [MenuController::class, 'extractPermisos']);
Route::get('/resumenInc', [IncidenciaController::class, 'resumenInc']);



/* Control de Usuarios */
Route::get('/control-de-usuario/usuarios', function () {
    $areasController = new AreasController();
    $areas = $areasController->fillSelect();
    
    $tipoAccesoController = new TipoAccesoController();
    $tipoAcceso = $tipoAccesoController->fillSelect();
    
    $menuController = new MenuController();
    $menu = $menuController->extractPermisos();
    
    return view('dashboard.users.usuarios', ['areas' => $areas, 'tipoAcceso' => $tipoAcceso, 'menu' => $menu]);
})->middleware('auth');

Route::get('/consultaDni/{dni}', [UserController::class, 'consultaDni']);
Route::get('/DataTableUser', [UserController::class, 'DataTableUser']);
Route::post('/register', [UserController::class, 'RegisterUser']);
Route::get('/showusu/{id}', [UserController::class, 'ShowUser']);
Route::post('/editusu/{id}', [UserController::class, 'EditUser']);
Route::post('/updateEstatus/{id}', [UserController::class, 'UpdateEstatus']);


Route::get('/control-de-usuario/mi-perfil', function () {
    return view('dashboard.users.miperfil');
})->middleware('auth');



/* Empresas Control */
Route::get('/soport-empresa/empresas', function () {    
    return view('dashboard.company.empresas');
})->middleware('auth');

Route::get('/soport-empresa/grupos', function () {    
    return view('dashboard.company.grupos');
})->middleware('auth');

Route::get('/soport-empresa/sucursales', function () {    
    return view('dashboard.company.sucursales');
})->middleware('auth');

Route::get('/iconos', function () {    
    return view('icons');
});
