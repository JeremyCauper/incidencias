<?php

namespace App\Http\Controllers\Reporte\Rci;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        try {
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

            $incidencias = DB::table('tb_incidencias')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereInc)
                ->get();

            $estados = [
                ['name' => 'Sin Asignar', 'value' => 0, 'itemStyle' => ['color' => 'rgb(228, 161, 27)']], // 0
                ['name' => 'Asignada', 'value' => 0, 'itemStyle' => ['color' => 'rgb(84, 180, 211)']], // 1
                ['name' => 'En Proceso', 'value' => 0, 'itemStyle' => ['color' => 'rgb(59, 113, 202)']], // 2
                ['name' => 'Finalizado', 'value' => 0, 'itemStyle' => ['color' => 'rgb(20, 164, 77)']], // 3
                ['name' => 'Faltan Datos', 'value' => 0, 'itemStyle' => ['color' => 'rgb(220, 76, 100)']], // 4
                ['name' => 'Cierre Sistema', 'value' => 0, 'itemStyle' => ['color' => 'rgb(159, 166, 178)']], // 5
            ];

            // $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();

            $incidencias->map(function ($items) use (&$estados) {
                $estados[$items->estado_informe]['value']++;
            });
            $data['estados'] = $estados;

            $problemas = JsonDB::table('problema')->get()->keyBy('id');
            $data['problemas'] = $incidencias->groupBy('id_problema')
                ->map(function ($items, $id) use($problemas) {
                    return [
                        'name' => $problemas[$id]->codigo,
                        'series' => [ 'problemas' => count($items) ],
                    ];
                })
                ->sortByDesc('total')
                ->take(10)
                ->values();


            return ['data' => $data];

            /*$whereInc = ['estatus' => 1];
            if (!empty($ruc)) {
                $whereInc['ruc_empresa'] = $ruc;
            }

            if (intval($sucursal)) {
                $whereInc['id_sucursal'] = intval($sucursal);
            }

            $cod_incidencias = DB::table('tb_inc_tipo')->whereIn('id_tipo_inc', explode(",", $tIncidencia))->pluck('cod_incidencia')->toArray();

            // return $cod_incidencias;

            $incidencias = DB::table('tb_incidencias')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereInc)
                ->whereIn('cod_incidencia', $cod_incidencias)
                ->get();
            $seguimientos = DB::table('tb_inc_seguimiento')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
            $inc_asig = DB::table('tb_inc_asignadas')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
            $inc_tipo = DB::table('tb_inc_tipo')->select('cod_incidencia', 'id_tipo_inc')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');

            $ordenes = DB::table('tb_orden_servicio')->whereIn('cod_incidencia', $cod_incidencias)->get();
            $id_contac_ordens = $ordenes->pluck('id_contacto')->toArray();
            $contac_ordens = DB::table('tb_contac_ordens')->whereIn('id', $id_contac_ordens)->get();

            $incidencias = $incidencias->map(function ($incidencia) use ($ordenes, $seguimientos, $inc_asig, $inc_tipo, $contac_ordens) {
                $orden = $ordenes->where('cod_incidencia', $incidencia->cod_incidencia)->first();
                $cod_ordens = empty($orden) ? "Sin Orden" : $orden->cod_ordens;

                // Usamos get() para evitar "Undefined array key"
                $asignados = $inc_asig->get($incidencia->cod_incidencia, collect())->pluck('id_usuario')->toArray();
                $tipoInc = collect($inc_tipo[$incidencia->cod_incidencia])->pluck('id_tipo_inc')->toArray();
                $seguimiento = $seguimientos->get($incidencia->cod_incidencia, collect());

                $contac = false;
                if (!empty($orden->id_contacto)) {
                    $contac_data = $contac_ordens->firstWhere('id', $orden->id_contacto);
                    $contac = !empty($contac_data->firma_digital) && !empty($contac_data->nro_doc) && !empty($contac_data->nombre_cliente);
                }

                // Configurar el estado del informe
                $estadoInforme = [
                    "0" => ['c' => 'warning', 't' => 'Sin Asignar'],
                    "1" => ['c' => 'info', 't' => 'Asignada'],
                    "2" => ['c' => 'primary', 't' => 'En Proceso'],
                    "3" => ['c' => 'success', 't' => 'Finalizado'],
                    "4" => ['c' => 'danger', 't' => 'Faltan Datos'],
                    "5" => ['c' => 'danger', 't' => 'Cierre Sistema']
                ];
                $badge_informe = '<label class="badge badge-' . $estadoInforme[$incidencia->estado_informe]['c'] . '" style="font-size: .7rem;">' . $estadoInforme[$incidencia->estado_informe]['t'] . '</label>';

                return [
                    'cod_incidencia' => $incidencia->cod_incidencia,
                    'estado' => $badge_informe,
                    'cod_orden' => (string) (
                        '<label class="badge badge-' . (empty($orden) ? "warning" : "info") .
                        '" style="font-size: .7rem;">' . (empty($orden) ? "Sin Orden" : $orden->cod_ordens) .
                        '</label>'
                    ) ?? null,
                    'fecha_inc' => $incidencia->created_at ?? null,
                    'asignados' => $asignados ?? null,
                    'empresa' => $incidencia->ruc_empresa,
                    'sucursal' => $incidencia->id_sucursal,
                    'tipo_incidencia' => $tipoInc,
                    'tipo_soporte' => $incidencia->id_tipo_soporte,
                    'problema' => $incidencia->id_problema,
                    'subproblema' => $incidencia->id_subproblema,
                    'iniciado' => $seguimiento->where('estado', 0)->first()?->created_at ?? 'Sin Iniciar',
                    'finalizado' => $seguimiento->where('estado', 1)->first()?->created_at ?? 'Sin Terminar',
                ];
            });

            return ['data' => $incidencias];*/
        } catch (Exception $th) {
            return response()->json(['error' => $th]);
        }
    }
}