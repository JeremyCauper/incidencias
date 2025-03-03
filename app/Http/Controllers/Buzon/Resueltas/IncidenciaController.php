<?php

namespace App\Http\Controllers\Buzon\Resueltas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncidenciaController extends Controller
{
    public function index(Request $request)
    {
        $ruc = $request->query('ruc');
        $sucursal = $request->query('sucursal');
        $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
        $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
        try {
            $whereInc = ['estatus' => 1, 'estado_informe' => 3];
            if ($ruc) {
                $whereInc['ruc_empresa'] = $ruc;
            }
            if (intval($sucursal)) {
                $whereInc['id_sucursal'] = intval($sucursal);
            }

            $cod_incidencias = DB::table('tb_inc_asignadas')->where('id_usuario', session('id_usuario'))->pluck('cod_incidencia')->toArray();
            $seguimientos = DB::table('tb_inc_seguimiento')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
            $ordenes = DB::table('tb_orden_servicio')
                ->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');

            $incidencia = DB::table('tb_incidencias')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereInc)->get()->filter(function ($inc) use ($ordenes) {
                    return $ordenes->has($inc->cod_incidencia); // Verifica si hay una orden para esta incidencia
                })->map(function ($incidencia) use ($ordenes, $seguimientos) {
                    $orden = $ordenes->get($incidencia->cod_incidencia)?->first();
                    $seguimiento = $seguimientos[$incidencia->cod_incidencia] ?? collect();

                    return [
                        'cod_inc' => $incidencia->cod_incidencia ?? null,
                        'cod_orden' => '<label class="badge badge-info" style="font-size: .7rem;">' . $orden->cod_ordens . '</label>' ?? null,
                        'fecha_inc' => $incidencia->created_at ?? null,
                        'id_sucursal' => $incidencia->id_sucursal,
                        'iniciado' => $seguimiento->where('estado', 0)->first()?->created_at ?? 'N/A',
                        'finalizado' => $seguimiento->where('estado', 1)->first()?->created_at ?? 'N/A',
                        'acciones' => $this->DropdownAcciones([
                            'tittle' => 'Acciones',
                            'button' => [
                                ['funcion' => "ShowDetailInc(this, '$incidencia->cod_incidencia')", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                                ['funcion' => "OrdenPdfInc('$orden->cod_ordens')", 'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF'],
                                ['funcion' => "OrdenTicketInc('$orden->cod_ordens')", 'texto' => '<i class="fas fa-ticket text-warning me-2"></i> Ver Ticket']
                            ]
                        ])
                    ];
            })->values();

            return $incidencia;
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
