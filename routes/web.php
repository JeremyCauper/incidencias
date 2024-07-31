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

// Gestion del login
Route::redirect('/', url('/inicio'));
Route::get('/inicio', [LoginController::class, 'viewLogin'])->name('login')->middleware('guest');
Route::post('/iniciar', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);



// Gestion de incidencias
Route::get('/soporte', [IncidenciaController::class, 'view'])->middleware('auth');
Route::get('/soporte/dataInd', [IncidenciaController::class, 'dataInd']);
Route::get('/soporte/datatable', [IncidenciaController::class, 'datatable']);
Route::post('/soporte/create', [IncidenciaController::class, 'create']);
Route::get('/soporte/show/{id}', [IncidenciaController::class, 'show']);
Route::post('/soporte/edit/{id}', [IncidenciaController::class, 'edit']);
Route::post('/soporte/destroy/{id}', [IncidenciaController::class, 'destroy']);

Route::get('/viewListMenu', [MenuController::class, 'viewListMenu']);
Route::get('/extractPermisos', [MenuController::class, 'extractPermisos']);



// Gestion de Usuarios
Route::get('/control-de-usuario/usuarios', [UserController::class, 'view'])->middleware('auth');

Route::get('/usuarios/datatable', [UserController::class, 'datatable']);
Route::post('/usuarios/create', [UserController::class, 'create']);
Route::get('/usuarios/show/{id}', [UserController::class, 'show']);
Route::post('/usuarios/edit/{id}', [UserController::class, 'edit']);
Route::post('/usuarios/editstatus/{id}', [UserController::class, 'editstatus']);
Route::get('/consultaDni/{dni}', [UserController::class, 'consultaDni']);



Route::get('/control-de-usuario/mi-perfil', function () {
    return view('dashboard.users.miperfil');
})->middleware('auth');



// Gestion de Empresas
Route::get('/soport-empresa/empresas', function () {    
    return view('dashboard.company.empresas');
})->middleware('auth');

Route::get('/soport-empresa/grupos', function () {    
    return view('dashboard.company.grupos');
})->middleware('auth');

Route::get('/soport-empresa/sucursales', function () {    
    return view('dashboard.company.sucursales');
})->middleware('auth');



Route::get('/datepicker', function () {    
    return view('pruebas.datepicker');
});