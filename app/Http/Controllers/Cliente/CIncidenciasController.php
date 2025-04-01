<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class CIncidenciasController extends Controller
{
    public function view()
    {
        try {
            return view('cliente.incidencias');
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
