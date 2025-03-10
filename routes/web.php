<?php

use App\Helpers\TipoIncidencia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Buzon\BAsignadasController;
use App\Http\Controllers\Buzon\BResueltasController;
use App\Http\Controllers\Buzon\Resueltas\IncidenciaController as RIncidenciaController;
use App\Http\Controllers\Buzon\Resueltas\VisitaController as RVisitaController;
use App\Http\Controllers\Buzon\Asignadas\IncidenciaController as AIncidenciaController;
use App\Http\Controllers\Buzon\Asignadas\VisitaController as AVisitaController;
use App\Http\Controllers\Consultas\ConsultasController;
use App\Http\Controllers\Empresas\EmpresasController;
use App\Http\Controllers\Empresas\GruposController;
use App\Http\Controllers\Empresas\SucursalesController;
use App\Http\Controllers\Incidencias\RegistradasController;
use App\Http\Controllers\Incidencias\ResueltasController;
use App\Http\Controllers\Mantenimientos\Menu\MenuController;
use App\Http\Controllers\Mantenimientos\Menu\SubMenuController;
use App\Http\Controllers\Mantenimientos\Problema\ProblemaController;
use App\Http\Controllers\Mantenimientos\Problema\SubProblemaController;
use App\Http\Controllers\Orden\OrdenController;
use App\Http\Controllers\Orden\OrdenVisitaController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Visitas\TerminadasController;
use App\Http\Controllers\Visitas\VProgramadasController;
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
Route::get('/incidencias/registradas/{id}', [RegistradasController::class, 'show']);
Route::post('/incidencias/registradas/registrar', [RegistradasController::class, 'create']);
Route::post('/incidencias/registradas/actualizar', [RegistradasController::class, 'update']);
Route::post('/incidencias/registradas/assignPer', [RegistradasController::class, 'assignPer']);
Route::post('/incidencias/registradas/destroy/{id}', [RegistradasController::class, 'destroy']);
Route::post('/incidencias/registradas/startInc', [RegistradasController::class, 'startInc']);
Route::get('/incidencias/registradas/searchCliente/{dni}', [RegistradasController::class, 'searchCliente']);

Route::get('/incidencias/resueltas', [ResueltasController::class, 'view'])->middleware('auth');
Route::get('/incidencias/resueltas/index', [ResueltasController::class, 'index']);
Route::get('/incidencias/resueltas/detail/{cod}', [ResueltasController::class, 'detail']);
Route::get('/incidencias/resueltas/showSignature/{cod}', [ResueltasController::class, 'showSignature']);

Route::post('/orden/create', [OrdenController::class, 'create']);
Route::post('/orden/editCodAviso', [OrdenController::class, 'editCodAviso']);
Route::post('/orden/addSignature', [OrdenController::class, 'addSignature']);
Route::get('/orden/documentopdf/{cod}', [OrdenController::class, 'CreatePdf']);
Route::get('/orden/documentoticket/{cod}', [OrdenController::class, 'CreateTicket']);

Route::post('/orden-visita/create', [OrdenVisitaController::class, 'create']);
Route::get('/orden-visita/documentopdf/{cod}', [OrdenVisitaController::class, 'CreatePdf']);

// Visitas Tecnicas
Route::get('/visitas/sucursales', [VSucursalesController::class, 'view'])->middleware('auth');
Route::get('/visitas/sucursales/index', [VSucursalesController::class, 'index'])->middleware('auth');
Route::get('/visitas/sucursales/{id}', [VSucursalesController::class, 'show']);
Route::post('/visitas/sucursales/create', [VSucursalesController::class, 'create']);

Route::get('/visitas/programadas/index', [VProgramadasController::class, 'index'])->middleware('auth');
Route::get('/visitas/programadas/show/{id}', [VProgramadasController::class, 'show']);
Route::get('/visitas/programadas/detail/{id}', [VProgramadasController::class, 'detail']);
Route::post('/visitas/programadas/startVisita', [VProgramadasController::class, 'startVisita']);
Route::post('/visitas/programadas/destroy', [VProgramadasController::class, 'destroy']);
Route::post('/visitas/programadas/assignPer', [VProgramadasController::class, 'assignPer']);

Route::get('/visitas/terminadas', [TerminadasController::class, 'view'])->middleware('auth');
Route::get('/visitas/terminadas/index', [TerminadasController::class, 'index']);
Route::get('/visitas/terminadas/{id}', [TerminadasController::class, 'show']);


Route::get('/empresas/empresas', [EmpresasController::class, 'view'])->middleware('auth');
Route::get('/empresas/empresas/index', [EmpresasController::class, 'index']);
Route::get('/empresas/empresas/{id}', [EmpresasController::class, 'show']);
Route::post('/empresas/empresas/registrar', [EmpresasController::class, 'create']);
Route::post('/empresas/empresas/actualizar', [EmpresasController::class, 'update']);
Route::post('/empresas/empresas/cambiarEstado', [EmpresasController::class, 'changeStatus']);

Route::get('/empresas/grupos', [GruposController::class, 'view'])->middleware('auth');
Route::get('/empresas/grupos/index', [GruposController::class, 'index']);
Route::get('/empresas/grupos/{id}', [GruposController::class, 'show']);
Route::post('/empresas/grupos/registrar', [GruposController::class, 'create']);
Route::post('/empresas/grupos/actualizar', [GruposController::class, 'update']);
Route::post('/empresas/grupos/cambiarEstado', [GruposController::class, 'changeStatus']);

Route::get('/empresas/sucursales', [SucursalesController::class, 'view'])->middleware('auth');
Route::get('/empresas/sucursales/index', [SucursalesController::class, 'index']);
Route::get('/empresas/sucursales/{id}', [SucursalesController::class, 'show']);
Route::post('/empresas/sucursales/registrar', [SucursalesController::class, 'create']);
Route::post('/empresas/sucursales/actualizar', [SucursalesController::class, 'update']);
Route::post('/empresas/sucursales/cambiarEstado', [SucursalesController::class, 'changeStatus']);

Route::get('/control-de-usuario/usuarios', [UsuarioController::class, 'view'])->middleware('auth');
Route::get('/control-de-usuario/usuarios/index', [UsuarioController::class, 'index']);
Route::get('/control-de-usuario/usuarios/{id}', [UsuarioController::class, 'show']);
Route::post('/control-de-usuario/usuarios/registrar', [UsuarioController::class, 'create']);
Route::post('/control-de-usuario/usuarios/actualizar', [UsuarioController::class, 'update']);
Route::post('/control-de-usuario/usuarios/cambiarEstado', [UsuarioController::class, 'changeStatus']);

// Manenimiento Problemas
Route::get('/mantenimiento/problemas/problemas', [ProblemaController::class, 'view'])->middleware('auth');
Route::get('/mantenimiento/problemas/problemas/index', [ProblemaController::class, 'index']);
Route::get('/mantenimiento/problemas/problemas/{id}', [ProblemaController::class, 'show']);
Route::post('/mantenimiento/problemas/problemas/registrar', [ProblemaController::class, 'create']);
Route::post('/mantenimiento/problemas/problemas/actualizar', [ProblemaController::class, 'update']);
Route::post('/mantenimiento/problemas/problemas/cambiarEstado', [ProblemaController::class, 'changeStatus']);

Route::get('/mantenimiento/problemas/subproblemas', [SubProblemaController::class, 'view'])->middleware('auth');
Route::get('/mantenimiento/problemas/subproblemas/index', [SubProblemaController::class, 'index']);
Route::get('/mantenimiento/problemas/subproblemas/{id}', [SubProblemaController::class, 'show']);
Route::post('/mantenimiento/problemas/subproblemas/registrar', [SubProblemaController::class, 'create']);
Route::post('/mantenimiento/problemas/subproblemas/actualizar', [SubProblemaController::class, 'update']);
Route::post('/mantenimiento/problemas/subproblemas/cambiarEstado', [SubProblemaController::class, 'changeStatus']);

Route::get('/mantenimiento/menu/menu', [MenuController::class, 'view'])->middleware('auth');
Route::get('/mantenimiento/menu/menu/index', [MenuController::class, 'index']);
Route::get('/mantenimiento/menu/menu/{id}', [MenuController::class, 'show']);
Route::post('/mantenimiento/menu/menu/registrar', [MenuController::class, 'create']);
Route::post('/mantenimiento/menu/menu/actualizar', [MenuController::class, 'update']);
Route::post('/mantenimiento/menu/menu/cambiarEstado', [MenuController::class, 'changeStatus']);

Route::get('/mantenimiento/menu/submenu', [SubMenuController::class, 'view'])->middleware('auth');
Route::get('/mantenimiento/menu/submenu/index', [SubMenuController::class, 'index']);
Route::get('/mantenimiento/menu/submenu/{id}', [SubMenuController::class, 'show']);
Route::post('/mantenimiento/menu/submenu/registrar', [SubMenuController::class, 'create']);
Route::post('/mantenimiento/menu/submenu/actualizar', [SubMenuController::class, 'update']);
Route::post('/mantenimiento/menu/submenu/cambiarEstado', [SubMenuController::class, 'changeStatus']);

// Buzon Soporte
Route::get('/buzon-personal/asignadas', [BAsignadasController::class, 'view'])->middleware('auth');
Route::get('/buzon-personal/incidencias/asignadas/index', [AIncidenciaController::class, 'index']);
Route::get('/buzon-personal/visitas/asignadas/index', [AVisitaController::class, 'index']);


Route::get('/buzon-personal/resueltas', [BResueltasController::class, 'view'])->middleware('auth');
Route::get('/buzon-personal/incidencias/resueltas/index', [RIncidenciaController::class, 'index']);
Route::get('/buzon-personal/visitas/resueltas/index', [RVisitaController::class, 'index']);


Route::get('/tipo_incidencia/index', [TipoIncidencia::class, 'all']);
Route::get('/tipo_incidencia/{id}', [TipoIncidencia::class, 'show']);


Route::get('/asignacion-turno', function (){
    return view('turno.turno');
});