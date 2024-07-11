<?php

use App\Http\Controllers\Auth\LoginController;
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
    return view('dashboard.soporte.panel');
})->middleware('auth');

Route::get('/control-de-usuario/usuarios', function () {
    return view('dashboard.users.usuarios');
})->middleware('auth');

Route::get('/control-de-usuario/mi-perfil', function () {
    return view('dashboard.users.miperfil');
})->middleware('auth');
