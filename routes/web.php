<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Consultas\ConsultasController;
use App\Http\Controllers\Empresas\EmpresasController;
use App\Http\Controllers\Empresas\GruposController;
use App\Http\Controllers\Empresas\SucursalesController;
use App\Http\Controllers\Incidencias\RegistradasController;
use App\Http\Controllers\Incidencias\ResueltasController;
use App\Http\Controllers\Mantenimientos\Problema\ProblemaController;
use App\Http\Controllers\Orden\OrdenController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Visitas\TerminadasController;
use App\Http\Controllers\Visitas\VSucursalesController;
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

// Consulta Dni
Route::get('/ConsultaDni/{dni}', [ConsultasController::class, 'ConsultaDni']);

Route::redirect('/', url('/inicio'));
Route::get('/inicio', [LoginController::class, 'view'])->name('login')->middleware('guest');
Route::post('/iniciar', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout']);


Route::get('/incidencias/registradas', [RegistradasController::class, 'view'])->middleware('auth');
Route::get('/incidencias/registradas/index', [RegistradasController::class, 'index']);
Route::get('/incidencias/registradas/detail/{cod}', [RegistradasController::class, 'detail']);
Route::post('/incidencias/registradas/create', [RegistradasController::class, 'create']);
Route::get('/incidencias/registradas/show/{id}', [RegistradasController::class, 'show']);
Route::post('/incidencias/registradas/edit/{id}', [RegistradasController::class, 'edit']);
Route::post('/incidencias/registradas/assignPer', [RegistradasController::class, 'assignPer']);
Route::post('/incidencias/registradas/destroy/{id}', [RegistradasController::class, 'destroy']);
Route::post('/incidencias/registradas/startInc', [RegistradasController::class, 'startInc']);
Route::get('/incidencias/registradas/searchCliente/{dni}', [RegistradasController::class, 'searchCliente']);

Route::get('/incidencias/resueltas', [ResueltasController::class, 'view'])->middleware('auth');
Route::get('/incidencias/resueltas/index', [ResueltasController::class, 'index']);
Route::get('/incidencias/resueltas/detail/{cod}', [ResueltasController::class, 'detail']);

Route::post('/orden/create', [OrdenController::class, 'create']);
Route::get('/orden/documentopdf/{cod}', [OrdenController::class, 'CreatePdf']);
Route::get('/orden/documentoticket/{cod}', [OrdenController::class, 'CreateTicket']);


Route::get('/empresas/grupos', [GruposController::class, 'view'])->middleware('auth');
Route::get('/empresas/grupos/index', [GruposController::class, 'index']);
Route::get('/empresas/grupos/{id}', [GruposController::class, 'show']);
Route::post('/empresas/grupos/registrar', [GruposController::class, 'create']);
Route::post('/empresas/grupos/actualizar', [GruposController::class, 'update']);
Route::post('/empresas/grupos/cambiarEstado', [GruposController::class, 'changeStatus']);

Route::get('/empresas/empresas', [EmpresasController::class, 'view'])->middleware('auth');
Route::get('/empresas/empresas/index', [EmpresasController::class, 'index']);
Route::get('/empresas/empresas/{id}', [EmpresasController::class, 'show']);
Route::post('/empresas/empresas/registrar', [EmpresasController::class, 'create']);
Route::post('/empresas/empresas/actualizar', [EmpresasController::class, 'update']);
Route::post('/empresas/empresas/cambiarEstado', [EmpresasController::class, 'changeStatus']);

Route::get('/empresas/sucursales', [SucursalesController::class, 'view'])->middleware('auth');
Route::get('/empresas/sucursales/index', [SucursalesController::class, 'index']);
Route::get('/empresas/sucursales/{id}', [SucursalesController::class, 'show']);
Route::post('/empresas/sucursales/registrar', [SucursalesController::class, 'create']);
Route::post('/empresas/sucursales/actualizar', [SucursalesController::class, 'update']);
Route::post('/empresas/sucursales/cambiarEstado', [SucursalesController::class, 'changeStatus']);

// Visitas Tecnicas
Route::get('/visitas/terminadas', [TerminadasController::class, 'view'])->middleware('auth');

Route::get('/visitas/sucursales', [VSucursalesController::class, 'view'])->middleware('auth');
Route::get('/visitas/sucursales/index', [VSucursalesController::class, 'index'])->middleware('auth');

Route::get('/control-de-usuario/usuarios', [UsuarioController::class, 'view'])->middleware('auth');
Route::get('/control-de-usuario/usuarios/index', [UsuarioController::class, 'index']);

// Manenimiento Problemas
Route::get('/mantenimiento/problemas/problemas', [ProblemaController::class, 'view'])->middleware('auth');
Route::get('/mantenimiento/problemas/problemas/index', [ProblemaController::class, 'index']);
Route::post('/mantenimiento/problemas/problemas/create', [ProblemaController::class, 'create']);
Route::get('/mantenimiento/problemas/problemas/show/{id}', [ProblemaController::class, 'show']);
Route::post('/mantenimiento/problemas/problemas/edit/{id}', [ProblemaController::class, 'edit']);
Route::post('/mantenimiento/problemas/problemas/destroy/{id}', [ProblemaController::class, 'destroy']);