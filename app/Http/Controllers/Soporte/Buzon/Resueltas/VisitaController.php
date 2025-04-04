<?php

namespace App\Http\Controllers\Soporte\Buzon\Resueltas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitaController extends Controller
{
    public function index(Request $request)
    {
        $sucursal = $request->query('sucursal');
        $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
        $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
        try {
            $whereVis = ['estado' => 2, 'eliminado' => 0];
            if (intval($sucursal)) {
                $whereVis['id_sucursal'] = intval($sucursal);
            }

            $id_visitas = DB::table('tb_vis_asignadas')->where('id_usuario', session('id_usuario'))->pluck('id_visitas')->toArray();
            $seguimientos = DB::table('tb_vis_seguimiento')->whereIn('id_visitas', $id_visitas)->get()->groupBy('id_visitas');
            $ordenes = DB::table('tb_orden_visita')->select('id', 'cod_orden_visita', 'id_visita', 'created_at')
                ->whereIn('id_visita', $id_visitas)->get()->groupBy('id_visita');

            $visitas = DB::table('tb_visitas')->select('id', 'id_sucursal', 'created_at')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereVis)->get()->filter(function ($vis) use ($ordenes) {
                    return $ordenes->has($vis->id); // Verifica si hay una orden para esta incidencia
                })->map(function ($vis) use($ordenes, $seguimientos) {
                    $id = $vis->id;
                    $orden = $ordenes->get($id)?->first();
                    $seguimiento = $seguimientos[$id] ?? collect();

                    return [
                        'cod_orden' => $orden->cod_orden_visita ?? null,
                        'id_sucursal' => $vis->id_sucursal,
                        'fecha_vis' => $vis->created_at ?? null,
                        'iniciado' => $seguimiento->where('estado', 0)->first()?->created_at ?? 'N/A',
                        'finalizado' => $seguimiento->where('estado', 1)->first()?->created_at ?? 'N/A',
                        'acciones' => $this->DropdownAcciones([
                            'tittle' => "Acciones",
                            'button' => [
                                ['funcion' => "ShowDetailVis(this, $id)", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                                ['funcion' => "OrdenPdfVis('$orden->cod_orden_visita')", 'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF']
                            ],
                        ])
                    ];
            })->values();

            return $visitas;
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
