<?php

namespace App\Http\Controllers\Turno;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TurnoController extends Controller
{
    function view()
    {
        // $this->validarPermisos(1);
        try {
            $data = [];

            $data['usuarios'] = DB::table('usuarios')->where('estatus', 1)->get()->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'value' => $u->id_usuario,
                    'dValue' => base64_encode(json_encode(['id' => $u->id_usuario, 'doc' => $u->ndoc_usuario, 'nombre' => $nombre])),
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });

            return view('turno.turno', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
