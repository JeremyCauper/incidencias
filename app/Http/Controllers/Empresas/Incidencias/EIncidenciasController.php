<?php

namespace App\Http\Controllers\Empresas\Incidencias;

use App\Helpers\Problema;
use App\Helpers\SubProblema;
use App\Helpers\TipoEstacion;
use App\Helpers\TipoIncidencia;
use App\Helpers\TipoSoporte;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EIncidenciasController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            // Obtener información externa de la API
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->where('ruc', ((array) config('ajustes.empresa'))['ruc'])->get()->keyBy('id');

            // Obtener información de base de datos local
            $data['tEstacion'] = collect((new TipoEstacion())->all())->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tSoporte'] = collect((new TipoSoporte())->all())->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'tipo', 'color', 'selected', 'estatus', 'eliminado')->keyBy('id');
            $data['problema'] = collect((new Problema())->all())->select('id', 'codigo', 'descripcion', 'tipo_soporte', 'estatus')->keyBy('id');
            $data['sproblema'] = collect((new SubProblema())->all())->select('id', 'codigo_problema', 'descripcion', 'prioridad', 'estatus')->keyBy('id');
            $data['usuarios'] = DB::table('tb_personal')->get()->keyBy('id_usuario')->map(function ($user) {
                $nombre = $this->formatearNombre($user->nombres, $user->apellidos);
                return (object) [
                    'id' => $user->id_usuario,
                    'nombre' => $nombre,
                ];
            });

            return view('cliente.incidencias.incidencias', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $sucursal = $request->query('sucursal');
            $tSoporte = $request->query('tSoporte');
            $tEstado = $request->query('tEstado');
            $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
            $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');

            $whereInc = [
                'ruc_empresa' => ((array) config('ajustes.empresa'))['ruc'],
                'estatus' => 1
            ];

            if (intval($sucursal)) {
                $whereInc['id_sucursal'] = intval($sucursal);
            }

            $incidencias = DB::table('tb_incidencias')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
                ->where($whereInc)
                ->when($tSoporte, function ($query, $tSoporte) {
                    return $query->whereIn('id_tipo_soporte', explode(",", $tSoporte));
                })
                ->when($tEstado, function ($query, $tEstado) {
                    return $query->whereIn('estado_informe', explode(",", $tEstado));
                })
                ->get();

            $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();

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
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            [
                                'funcion' => "ShowDetail(this, '$incidencia->cod_incidencia')",
                                'texto' => '<i class="fas fa-eye text-info me-2"></i> Detalle Incidencia'
                            ],
                            !empty($orden) ? [
                                'funcion' => "OrdenPdf('$cod_ordens')",
                                'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF'
                            ] : null,
                            !empty($orden) ? [
                                'funcion' => "OrdenTicket('$cod_ordens')",
                                'texto' => '<i class="fas fa-ticket text-warning me-2"></i> Ver Ticket'
                            ] : null,
                        ]
                    ])
                ];
            });

            return ['data' => $incidencias];
        } catch (Exception $th) {
            return response()->json(['error' => $th]);
        }
    }
}
