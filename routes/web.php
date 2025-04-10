<?php

use App\Helpers\TipoIncidencia;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LoginEmpresaController;
use App\Http\Controllers\Cliente\Incidencias\CIncidenciasController;
use App\Http\Controllers\Soporte\Buzon\BAsignadasController;
use App\Http\Controllers\Soporte\Buzon\BResueltasController;
use App\Http\Controllers\Soporte\Buzon\Resueltas\IncidenciaController as RIncidenciaController;
use App\Http\Controllers\Soporte\Buzon\Resueltas\VisitaController as RVisitaController;
use App\Http\Controllers\Soporte\Buzon\Asignadas\IncidenciaController as AIncidenciaController;
use App\Http\Controllers\Soporte\Buzon\Asignadas\VisitaController as AVisitaController;
use App\Http\Controllers\Soporte\Consultas\ConsultasController;
use App\Http\Controllers\Soporte\Empresas\EmpresasController;
use App\Http\Controllers\Soporte\Empresas\GruposController;
use App\Http\Controllers\Soporte\Empresas\SucursalesController;
use App\Http\Controllers\Soporte\Incidencias\RegistradasController;
use App\Http\Controllers\Soporte\Incidencias\ResueltasController;
use App\Http\Controllers\Soporte\Mantenimientos\Menu\MenuController;
use App\Http\Controllers\Soporte\Mantenimientos\Menu\SubMenuController;
use App\Http\Controllers\Soporte\Mantenimientos\Problema\ProblemaController;
use App\Http\Controllers\Soporte\Mantenimientos\Problema\SubProblemaController;
use App\Http\Controllers\Soporte\Orden\OrdenController;
use App\Http\Controllers\Soporte\Orden\OrdenVisitaController;
use App\Http\Controllers\Soporte\Turno\TurnoController;
use App\Http\Controllers\Soporte\Usuario\UsuarioController;
use App\Http\Controllers\Soporte\Visitas\TerminadasController;
use App\Http\Controllers\Soporte\Visitas\VProgramadasController;
use App\Http\Controllers\Soporte\Visitas\VSucursalesController;
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
Route::get('/soporte/ConsultaDni/{dni}', [ConsultasController::class, 'ConsultaDni']);

Route::redirect('/', url('/soporte'));
Route::get('/soporte', [LoginController::class, 'view'])->name('login')->middleware('guest:web');
Route::post('/soporte/iniciar', [LoginController::class, 'login']);
Route::get('/soporte/logout', [LoginController::class, 'logout'])->name('logout');;
Route::get('/validarTurno/{id}', [LoginController::class, 'validarTurno']);

Route::get('/soporte/incidencias/registradas', [RegistradasController::class, 'view'])->middleware('auth');
Route::get('/soporte/incidencias/registradas/index', [RegistradasController::class, 'index']);
Route::get('/soporte/incidencias/registradas/detail/{cod}', [RegistradasController::class, 'detail']);
Route::get('/soporte/incidencias/registradas/{id}', [RegistradasController::class, 'show']);
Route::post('/soporte/incidencias/registradas/registrar', [RegistradasController::class, 'create']);
Route::post('/soporte/incidencias/registradas/actualizar', [RegistradasController::class, 'update']);
Route::post('/soporte/incidencias/registradas/assignPer', [RegistradasController::class, 'assignPer']);
Route::post('/soporte/incidencias/registradas/destroy/{id}', [RegistradasController::class, 'destroy']);
Route::post('/soporte/incidencias/registradas/startInc', [RegistradasController::class, 'startInc']);
Route::get('/soporte/incidencias/registradas/searchCliente/{dni}', [RegistradasController::class, 'searchCliente']);

Route::get('/soporte/incidencias/resueltas', [ResueltasController::class, 'view'])->middleware('auth');
Route::get('/soporte/incidencias/resueltas/index', [ResueltasController::class, 'index']);
Route::get('/soporte/incidencias/resueltas/detail/{cod}', [ResueltasController::class, 'detail']);
Route::get('/soporte/incidencias/resueltas/showSignature/{cod}', [ResueltasController::class, 'showSignature']);

Route::post('/soporte/orden/create', [OrdenController::class, 'create']);
Route::post('/soporte/orden/editCodAviso', [OrdenController::class, 'editCodAviso']);
Route::post('/soporte/orden/addSignature', [OrdenController::class, 'addSignature']);
Route::get('/soporte/orden/documentopdf/{cod}', [OrdenController::class, 'CreatePdf']);
Route::get('/soporte/orden/documentoticket/{cod}', [OrdenController::class, 'CreateTicket']);

Route::post('/soporte/orden-visita/create', [OrdenVisitaController::class, 'create']);
Route::get('/soporte/orden-visita/documentopdf/{cod}', [OrdenVisitaController::class, 'CreatePdf']);

// Visitas Tecnicas
Route::get('/soporte/visitas/sucursales', [VSucursalesController::class, 'view'])->middleware('auth');
Route::get('/soporte/visitas/sucursales/index', [VSucursalesController::class, 'index'])->middleware('auth');
Route::get('/soporte/visitas/sucursales/{id}', [VSucursalesController::class, 'show']);
Route::post('/soporte/visitas/sucursales/create', [VSucursalesController::class, 'create']);

Route::get('/soporte/visitas/programadas/index', [VProgramadasController::class, 'index'])->middleware('auth');
Route::get('/soporte/visitas/programadas/show/{id}', [VProgramadasController::class, 'show']);
Route::get('/soporte/visitas/programadas/detail/{id}', [VProgramadasController::class, 'detail']);
Route::post('/soporte/visitas/programadas/startVisita', [VProgramadasController::class, 'startVisita']);
Route::post('/soporte/visitas/programadas/destroy', [VProgramadasController::class, 'destroy']);
Route::post('/soporte/visitas/programadas/assignPer', [VProgramadasController::class, 'assignPer']);

Route::get('/soporte/visitas/terminadas', [TerminadasController::class, 'view'])->middleware('auth');
Route::get('/soporte/visitas/terminadas/index', [TerminadasController::class, 'index']);
Route::get('/soporte/visitas/terminadas/{id}', [TerminadasController::class, 'show']);


Route::get('/soporte/empresas/empresas', [EmpresasController::class, 'view'])->middleware('auth');
Route::get('/soporte/empresas/empresas/index', [EmpresasController::class, 'index']);
Route::get('/soporte/empresas/empresas/{id}', [EmpresasController::class, 'show']);
Route::post('/soporte/empresas/empresas/registrar', [EmpresasController::class, 'create']);
Route::post('/soporte/empresas/empresas/actualizar', [EmpresasController::class, 'update']);
Route::post('/soporte/empresas/empresas/cambiarEstado', [EmpresasController::class, 'changeStatus']);

Route::get('/soporte/empresas/grupos', [GruposController::class, 'view'])->middleware('auth');
Route::get('/soporte/empresas/grupos/index', [GruposController::class, 'index']);
Route::get('/soporte/empresas/grupos/{id}', [GruposController::class, 'show']);
Route::post('/soporte/empresas/grupos/registrar', [GruposController::class, 'create']);
Route::post('/soporte/empresas/grupos/actualizar', [GruposController::class, 'update']);
Route::post('/soporte/empresas/grupos/cambiarEstado', [GruposController::class, 'changeStatus']);

Route::get('/soporte/empresas/sucursales', [SucursalesController::class, 'view'])->middleware('auth');
Route::get('/soporte/empresas/sucursales/index', [SucursalesController::class, 'index']);
Route::get('/soporte/empresas/sucursales/{id}', [SucursalesController::class, 'show']);
Route::post('/soporte/empresas/sucursales/registrar', [SucursalesController::class, 'create']);
Route::post('/soporte/empresas/sucursales/actualizar', [SucursalesController::class, 'update']);
Route::post('/soporte/empresas/sucursales/cambiarEstado', [SucursalesController::class, 'changeStatus']);

Route::get('/soporte/control-de-usuario/usuarios', [UsuarioController::class, 'view'])->middleware('auth');
Route::get('/soporte/control-de-usuario/usuarios/index', [UsuarioController::class, 'index']);
Route::get('/soporte/control-de-usuario/usuarios/{id}', [UsuarioController::class, 'show']);
Route::post('/soporte/control-de-usuario/usuarios/registrar', [UsuarioController::class, 'create']);
Route::post('/soporte/control-de-usuario/usuarios/actualizar', [UsuarioController::class, 'update']);
Route::post('/soporte/control-de-usuario/usuarios/cambiarEstado', [UsuarioController::class, 'changeStatus']);

// Manenimiento Problemas
Route::get('/soporte/mantenimiento/problemas/problemas', [ProblemaController::class, 'view'])->middleware('auth');
Route::get('/soporte/mantenimiento/problemas/problemas/index', [ProblemaController::class, 'index']);
Route::get('/soporte/mantenimiento/problemas/problemas/{id}', [ProblemaController::class, 'show']);
Route::post('/soporte/mantenimiento/problemas/problemas/registrar', [ProblemaController::class, 'create']);
Route::post('/soporte/mantenimiento/problemas/problemas/actualizar', [ProblemaController::class, 'update']);
Route::post('/soporte/mantenimiento/problemas/problemas/cambiarEstado', [ProblemaController::class, 'changeStatus']);

Route::get('/soporte/mantenimiento/problemas/subproblemas', [SubProblemaController::class, 'view'])->middleware('auth');
Route::get('/soporte/mantenimiento/problemas/subproblemas/index', [SubProblemaController::class, 'index']);
Route::get('/soporte/mantenimiento/problemas/subproblemas/{id}', [SubProblemaController::class, 'show']);
Route::post('/soporte/mantenimiento/problemas/subproblemas/registrar', [SubProblemaController::class, 'create']);
Route::post('/soporte/mantenimiento/problemas/subproblemas/actualizar', [SubProblemaController::class, 'update']);
Route::post('/soporte/mantenimiento/problemas/subproblemas/cambiarEstado', [SubProblemaController::class, 'changeStatus']);

Route::get('/soporte/mantenimiento/menu/menu', [MenuController::class, 'view'])->middleware('auth');
Route::get('/soporte/mantenimiento/menu/menu/index', [MenuController::class, 'index']);
Route::get('/soporte/mantenimiento/menu/menu/{id}', [MenuController::class, 'show']);
Route::post('/soporte/mantenimiento/menu/menu/registrar', [MenuController::class, 'create']);
Route::post('/soporte/mantenimiento/menu/menu/actualizar', [MenuController::class, 'update']);
Route::post('/soporte/mantenimiento/menu/menu/cambiarEstado', [MenuController::class, 'changeStatus']);
Route::post('/soporte/mantenimiento/menu/menu/cambiarOrdenMenu', [MenuController::class, 'changeOrdenMenu']);

Route::get('/soporte/mantenimiento/menu/submenu', [SubMenuController::class, 'view'])->middleware('auth');
Route::get('/soporte/mantenimiento/menu/submenu/index', [SubMenuController::class, 'index']);
Route::get('/soporte/mantenimiento/menu/submenu/{id}', [SubMenuController::class, 'show']);
Route::post('/soporte/mantenimiento/menu/submenu/registrar', [SubMenuController::class, 'create']);
Route::post('/soporte/mantenimiento/menu/submenu/actualizar', [SubMenuController::class, 'update']);
Route::post('/soporte/mantenimiento/menu/submenu/cambiarEstado', [SubMenuController::class, 'changeStatus']);

// Buzon Soporte
Route::get('/soporte/buzon-personal/asignadas', [BAsignadasController::class, 'view'])->middleware('auth');
Route::get('/soporte/buzon-personal/incidencias/asignadas/index', [AIncidenciaController::class, 'index']);
Route::get('/soporte/buzon-personal/visitas/asignadas/index', [AVisitaController::class, 'index']);


Route::get('/soporte/buzon-personal/resueltas', [BResueltasController::class, 'view'])->middleware('auth');
Route::get('/soporte/buzon-personal/incidencias/resueltas/index', [RIncidenciaController::class, 'index']);
Route::get('/soporte/buzon-personal/visitas/resueltas/index', [RVisitaController::class, 'index']);


Route::get('/soporte/tipo_incidencia/index', [TipoIncidencia::class, 'all']);
Route::get('/soporte/tipo_incidencia/{id}', [TipoIncidencia::class, 'show']);


Route::get('/soporte/asignacion-turno', [TurnoController::class, 'view'])->middleware('auth');
Route::get('/soporte/asignacion-turno/index', [TurnoController::class, 'index']);
Route::post('/soporte/asignacion-turno/registrar', [TurnoController::class, 'create']);
Route::post('/soporte/asignacion-turno/actualizar', [TurnoController::class, 'update']);
Route::post('/soporte/asignacion-turno/eliminar', [TurnoController::class, 'destroy']);



Route::get('/empresa', [LoginEmpresaController::class, 'view'])->name('login')->middleware('guest:client');
Route::post('/empresa/iniciar', [LoginEmpresaController::class, 'loginClient']);
Route::get('/empresa/logout', [LoginEmpresaController::class, 'logout'])->name('logout.empresa');

Route::get('/empresa/incidencias', [CIncidenciasController::class, 'view'])->middleware('auth:client');
Route::get('/empresa/incidencias/index', [CIncidenciasController::class, 'index']);