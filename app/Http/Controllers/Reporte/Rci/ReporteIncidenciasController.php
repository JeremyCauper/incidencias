<?php

namespace App\Http\Controllers\Reporte\Rci;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\ErrorHandler\Collecting;

class ReporteIncidenciasController extends Controller
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

            return view('reporte.rci.reporte_incidencias', ['data' => $data]);
        } catch (Exception $e) {
            Log::error('Error inesperado: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // try {
            $ruc = $request->query('ruc');
            $sucursal = $request->query('sucursal');
            $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
            $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
            $data = [];

            $whereInc = ['estatus' => 1];
            if ($ruc) {
                $whereInc['ruc_empresa'] = $ruc;
            }
            if (intval($sucursal)) {
                $whereInc['id_sucursal'] = intval($sucursal);
            }

            $whereVis = [];
            if (intval($sucursal)) {
                $whereVis['id_sucursal'] = intval($sucursal);
            }

            $incidencias = DB::table('tb_incidencias')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereInc)
                ->get();
            $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();

            $visitas = DB::table('tb_visitas')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereVis)
                ->get();
            $id_visitas = $visitas->pluck('id')->toArray();

            $estados = [
                ['name' => 'Sin Asignar', 'value' => 0, 'itemStyle' => ['color' => 'rgb(228, 161, 27)']], // 0
                ['name' => 'Asignada', 'value' => 0, 'itemStyle' => ['color' => 'rgb(84, 180, 211)']], // 1
                ['name' => 'En Proceso', 'value' => 0, 'itemStyle' => ['color' => 'rgb(59, 113, 202)']], // 2
                ['name' => 'Finalizado', 'value' => 0, 'itemStyle' => ['color' => 'rgb(20, 164, 77)']], // 3
                ['name' => 'Faltan Datos', 'value' => 0, 'itemStyle' => ['color' => 'rgb(220, 76, 100)']], // 4
                ['name' => 'Cierre Sistema', 'value' => 0, 'itemStyle' => ['color' => 'rgb(159, 166, 178)']], // 5
            ];

            $niveles = [
                ['name' => 'N1 - REMOTO', 'value' => 0, 'itemStyle' => ['color' => 'rgb(159, 166, 178)']],
                ['name' => 'N2 - PRESENCIAL', 'value' => 0, 'itemStyle' => ['color' => 'rgb(84, 180, 211)']],
                ['name' => 'N3 - PROVEEDOR', 'value' => 0, 'itemStyle' => ['color' => 'rgb(51, 45, 45)']],
            ];

            $incidencias->map(function ($items) use (&$estados) {
                $estados[$items->estado_informe]['value']++;
            });
            $data['estados'] = $estados;

            $inc_asignadas = DB::table('tb_inc_asignadas')->whereIn('cod_incidencia', $cod_incidencias)->select('id_usuario')->get()->groupBy('id_usuario');
            $vis_asignadas = DB::table('tb_vis_asignadas')->whereIn('id_visitas', $id_visitas)->select('id_usuario')->get()->groupBy('id_usuario');

            $data['personal'] = DB::table('tb_personal')->where(['id_area' => 1, 'estatus' => 1])->whereIn('tipo_acceso', [2, 3, 4])->get()
                ->map(function ($val) use ($inc_asignadas, $vis_asignadas) {
                    $apellidos = $this->formatearNombre($val->apellidos);
                    $nombres = $this->formatearNombre($val->nombres, $val->apellidos);
                    return [
                        'name' => $apellidos,
                        'text' => "$val->ndoc_usuario $nombres",
                        'series' => [
                            'incidencias' => isset($inc_asignadas[$val->id_usuario]) ? count($inc_asignadas[$val->id_usuario]) : 0,
                            'visitas' => isset($vis_asignadas[$val->id_usuario]) ? count($vis_asignadas[$val->id_usuario]) : 0
                        ]
                    ];
                });

            $problemas = JsonDB::table('problema')->get()->keyBy('id');
            $data['problemas'] = $incidencias->groupBy('id_problema')
                ->map(function ($items, $id) use ($problemas) {
                    return [
                        'name' => $problemas[$id]->codigo,
                        'text' => $problemas[$id]->descripcion,
                        'series' => ['problemas' => count($items)],
                    ];
                })
                ->sortByDesc('total')
                ->take(10)
                ->values();

            DB::table('tb_inc_tipo')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia')
                ->map(function ($items, $id) use (&$niveles) {
                    $item = collect($items)->sortByDesc('created_at')->first();
                    $niveles[$item->id_tipo_inc - 1]['value']++;
                });
            $data['niveles'] = $niveles;

            return $this->message(data: ['data' => $data]);
        // } catch (Exception $e) {
        //     return $this->message(message: "Ocurrió un error interno en el servidor.", data: ['error' => $e->getMessage(), 'linea' => $e->getLine()], status: 500);
        // }
    }
}