<?php

namespace App\Http\Controllers\Dashboard\Rci;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\ErrorHandler\Collecting;

class DashboardIncidenciasController extends Controller
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

            return view('dashboard.rci.dashboard_incidencias', ['data' => $data]);
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

        $problema = JsonDB::table('problema')->select('id')->where('codigo', 'PS-0003')->first();

        $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();
        $cod_incidencias_mante = $incidencias->where('id_problema', $problema->id)->pluck('cod_incidencia')->toArray();

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

        $inc_asignadas = DB::table('tb_inc_asignadas')->whereIn('cod_incidencia', $cod_incidencias)->select('cod_incidencia', 'id_usuario')->get();
        $inc_mantenimiento = DB::table('tb_inc_asignadas')->whereIn('cod_incidencia', $cod_incidencias_mante)->select('cod_incidencia', 'id_usuario')->get();
        $inc_tipo = DB::table('tb_inc_tipo')->select('cod_incidencia', 'id_tipo_inc', 'created_at')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia')
            ->map(function ($items) {
                return collect($items)->sortByDesc('created_at')->first();
            })->values();
        $vis_asignadas = DB::table('tb_vis_asignadas')->whereIn('id_visitas', $id_visitas)->select('id_usuario')->get();

        $data['personal'] = DB::table('tb_personal')->where(['id_area' => 1, 'estatus' => 1])->whereIn('tipo_acceso', [2, 3, 4])->get()
            ->map(function ($val) use ($inc_asignadas, $vis_asignadas, $inc_tipo, $inc_mantenimiento) {
                $incAsignadas = $inc_asignadas->where('id_usuario', $val->id_usuario);
                $incAsignadasM = $inc_mantenimiento->where('id_usuario', $val->id_usuario);
                $visAsignadas = $vis_asignadas->where('id_usuario', $val->id_usuario);
                $tipo = $inc_tipo->whereIn('cod_incidencia', $incAsignadas->pluck('cod_incidencia')->toArray());
                $apellidos = $this->formatearNombre($val->apellidos);
                $nombres = $this->formatearNombre($val->nombres, $val->apellidos);
                $transporte = [
                    'fas fa-laptop',
                    'fas fa-person-hiking text-success',
                    'fas fa-motorcycle text-danger'
                ];
                return [
                    'name' => $apellidos,
                    'text' => "$val->ndoc_usuario $nombres",
                    'series' => [
                        'incidencias' => $incAsignadas->count(),
                        'visitas' => $visAsignadas->count(),
                        'mantenimientos' => $incAsignadasM->count()
                    ],
                    'transporte' => $transporte[$val->transporte],
                    'idTecnico' => $val->id_usuario,
                    'niveles' => [
                        'n1' => $tipo->where('id_tipo_inc', 1)->count(),
                        'n2' => $tipo->where('id_tipo_inc', 2)->count(),
                    ]
                ];
            });

        $problemas = JsonDB::table('problema')->get()->keyBy('id');
        $subproblemas = JsonDB::table('sub_problema')->get()->keyBy('id');
        $data['problemas'] = $incidencias->groupBy('id_problema')
            ->map(function ($items, $id) use ($problemas) {
                return [
                    'name' => $problemas[$id]->codigo,
                    'text' => "{$problemas[$id]->codigo} - {$problemas[$id]->descripcion}",
                    'tipo_soporte' => $problemas[$id]->tipo_soporte,
                    'series' => ['problemas' => count($items)],
                    'total' => count($items), // opcional, más fácil para ordenar
                ];
            })
            ->sortByDesc('total') // ordenar de mayor a menor
            ->take(10)
            ->values();


        $data['subproblemas'] = collect($incidencias->groupBy('id_subproblema')
            ->map(function ($items, $id) use ($subproblemas) {
                return [
                    'codigo' => $subproblemas[$id]->codigo_problema,
                    'name' => $subproblemas[$id]->descripcion,
                    'text' => "{$subproblemas[$id]->prioridad} - {$subproblemas[$id]->descripcion}",
                    'series' => ['sub_problemas' => count($items)]
                ];
            })->toArray())->groupBy('codigo');


        DB::table('tb_inc_tipo')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia')
            ->map(function ($items, $id) use (&$niveles) {
                $item = collect($items)->sortByDesc('created_at')->first();
                $niveles[$item->id_tipo_inc - 1]['value']++;
            });
        $data['niveles'] = $niveles;

        if ($ruc) {
            $infoData = DB::table('tb_sucursales')->select('id', 'ruc', 'nombre')->where('ruc', $ruc)->get()->keyBy('id');
        } else {
            $infoData = DB::table('tb_empresas')->select('ruc', 'razon_social')->get()->keyBy('ruc');
        }
        $data['contable'] = $incidencias->groupBy($ruc ? 'id_sucursal' : 'ruc_empresa')
            ->map(function ($items, $key) use ($infoData, $ruc) {
                $name = $ruc ? $infoData[$key]->nombre : $key;
                $text = $ruc ? $infoData[$key]->nombre : "{$key} - {$infoData[$key]->razon_social}";
                return [
                    'name' => $name,
                    'text' => $text,
                    'series' => [($ruc ? 'sucursal' : 'empresa') => $items->count()],
                    'total' => $items->count(), // opcional, más fácil para ordenar
                ];
            })
            ->sortByDesc('total') // ordenar de mayor a menor
            ->take(10)
            ->values()
            ->toArray();

        return $this->message(data: ['data' => $data]);
        // } catch (Exception $e) {
        //     return $this->message(message: "Ocurrió un error interno en el servidor.", data: ['error' => $e->getMessage(), 'linea' => $e->getLine()], status: 500);
        // }
    }
}