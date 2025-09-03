<?php

namespace App\Http\Controllers\Dashboard\Empresa;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EDashboardIncidenciasController extends Controller
{
    public function view()
    {
        // $this->validarPermisos(6, 7);
        try {
            $data = [];
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->where('ruc', ((array) session('empresa'))['ruc'])->get()->keyBy('id');

            return view('dashboard.empresa.dashboard_incidencias', ['data' => $data]);
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
        $sucursal = $request->query('sucursal');
        $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
        $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
        $data = [];

        $whereInc = [
            'ruc_empresa' => ((array) session('empresa'))['ruc'],
            'estatus' => 1
        ];

        if (intval($sucursal)) {
            $whereInc['id_sucursal'] = intval($sucursal);
        }

        $incidencias = DB::table('tb_incidencias')
            ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
            ->where($whereInc)
            ->whereIn('estado_informe', [0, 1, 2, 3, 4])
            ->get();
        $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();

        $estados = [
            ['name' => 'estado-sinasignar', 'value' => 0], // 0
            ['name' => 'estado-asignados', 'value' => 0], // 1
            ['name' => 'estado-enproceso', 'value' => 0], // 2
            ['name' => 'estado-finalizados', 'value' => 0], // 3
            ['name' => 'estado-faltandatos', 'value' => 0], // 4
            // ['name' => 'Cierre Sistema', 'value' => 0], // 5
        ];

        $niveles = [
            ['name' => 'n1', 'text' => 'REMOTO', 'color' => 'info', 'value' => 0],
            ['name' => 'n2', 'text' => 'PRESENCIAL', 'color' => 'warning', 'value' => 0],
            ['name' => 'n3', 'text' => 'PROVEEDOR', 'color' => 'purple', 'value' => 0],
        ];

        $data['fechas'] = $incidencias->groupBy('fecha_informe')
            ->map(function ($items, $fecha) {
                return [
                    'name' => $fecha,
                    'total' => count($items), // opcional, más fácil para ordenar
                ];
            })
            ->sortBy('name')
            ->values();

        $incidencias->map(function ($items) use (&$estados) {
            $estados[$items->estado_informe]['value']++;
        });
        $data['estados'] = $estados;

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

        $infoData = DB::table('tb_sucursales')->select('id', 'ruc', 'nombre')->where('ruc', ((array) session('empresa'))['ruc'])->get()->keyBy('id');
        $data['contable'] = $incidencias->groupBy('id_sucursal')
            ->map(function ($items, $key) use ($infoData) {
                $name = $infoData[$key]->nombre;
                $text = $infoData[$key]->nombre;
                return [
                    'name' => $name,
                    'text' => $text,
                    'series' => ['sucursal' => $items->count()],
                ];
            })
            ->take(10)
            ->values()
            ->toArray();

        return $this->message(data: ['data' => $data]);
        // } catch (Exception $e) {
        //     return $this->message(message: "Ocurrió un error interno en el servidor.", data: ['error' => $e->getMessage(), 'linea' => $e->getLine()], status: 500);
        // }
    }
}
