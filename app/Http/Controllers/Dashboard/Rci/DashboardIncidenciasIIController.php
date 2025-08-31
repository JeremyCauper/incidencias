<?php

namespace App\Http\Controllers\Dashboard\Rci;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class DashboardIncidenciasIIController extends Controller
{
    public function view()
    {
        // $this->validarPermisos(6, 7);
        try {
            $data = [];
            // Obtener información externa de la API
            $data['company'] = DB::table('tb_empresas')->select(['id', 'ruc', 'razon_social', 'direccion', 'contrato', 'codigo_aviso', 'status'])->get()->keyBy('ruc');
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->get()->keyBy('id');

            // Obtener información de base de datos local
            $data['tEstacion'] = JsonDB::table('tipo_estacion')->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tSoporte'] = JsonDB::table('tipo_soporte')->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tIncidencia'] = JsonDB::table('tipo_incidencia')->select('id', 'descripcion', 'tipo', 'color', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['problema'] = JsonDB::table('problema')->select('id', 'codigo', 'descripcion', 'tipo_soporte', 'estatus')->keyBy('id');
            $data['sproblema'] = JsonDB::table('sub_problema')->select('id', 'codigo_problema', 'descripcion', 'prioridad', 'estatus')->keyBy('id');
            $data['usuarios'] = DB::table('tb_personal')->get()->keyBy('id_usuario')->map(function ($user) {
                $nombre = $this->formatearNombre($user->nombres, $user->apellidos);
                return (object) [
                    'id' => $user->id_usuario,
                    'nombre' => $nombre,
                ];
            });

            return view('dashboard.rci.dashboard_incidencias2', ['data' => $data]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }
}
