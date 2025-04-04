<?php

namespace App\Http\Controllers\Cliente\Incidencias;

use App\Helpers\TipoIncidencia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CIncidenciasController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            // Obtener información externa de la API
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->where('ruc', session('empresa')->ruc)->get()->keyBy('id');

            // Obtener información de base de datos local
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problema'] = $this->fetchAndParseDbData('tb_problema', ["id_problema as id", 'tipo_incidencia', 'estatus'], "CONCAT(codigo, ' - ', descripcion) AS text");
            $data['sproblema'] = $this->fetchAndParseDbData('tb_subproblema', ["id_subproblema as id", 'id_problema', 'estatus'], "CONCAT(codigo_sub, ' - ', descripcion) AS text");
            $data['usuarios'] = DB::table('tb_personal')->get()->keyBy('id_usuario')->map(function ($user) {
                $nombre = $this->formatearNombre($user->nombres, $user->apellidos);
                return (object)[
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
        $ruc = session('empresa')->ruc;
        $sucursal = $request->query('sucursal');
        $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
        $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
    
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
        $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();
    
        $seguimientos = DB::table('tb_inc_seguimiento')
            ->whereIn('cod_incidencia', $cod_incidencias)
            ->get()
            ->groupBy('cod_incidencia');
        $inc_asig = DB::table('tb_inc_asignadas')
            ->whereIn('cod_incidencia', $cod_incidencias)
            ->get()
            ->groupBy('cod_incidencia');
    
        $ordenes = DB::table('tb_orden_servicio')
            ->whereIn('cod_incidencia', $cod_incidencias)
            ->get();
        $id_contac_ordens = $ordenes->pluck('id_contacto')->toArray();
        $contac_ordens = DB::table('tb_contac_ordens')
            ->whereIn('id', $id_contac_ordens)
            ->get();
    
        $incidencias = $incidencias->map(function ($incidencia) use ($ordenes, $seguimientos, $inc_asig, $contac_ordens) {
            $orden = $ordenes->where('cod_incidencia', $incidencia->cod_incidencia)->first();
            $cod_ordens = empty($orden) ? "Sin Orden" : $orden->cod_ordens;
            
            // Usamos get() para evitar "Undefined array key"
            $asignados = $inc_asig->get($incidencia->cod_incidencia, collect())
                ->pluck('id_usuario')
                ->toArray();
                
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
                'cod_orden' => (string)(
                    '<label class="badge badge-' . (empty($orden) ? "warning" : "info") .
                    '" style="font-size: .7rem;">' . (empty($orden) ? "Sin Orden" : $orden->cod_ordens) .
                    '</label>'
                ) ?? null,
                'fecha_inc' => $incidencia->created_at ?? null,
                'asignados' => $asignados ?? null,
                'empresa' => $incidencia->ruc_empresa,
                'sucursal' => $incidencia->id_sucursal,
                'tipo_incidencia' => $incidencia->id_tipo_incidencia,
                'problema' => $incidencia->id_problema,
                'subproblema' => $incidencia->id_subproblema,
                'iniciado' => $seguimiento->where('estado', 0)->first()?->created_at ?? 'Sin Iniciar',
                'finalizado' => $seguimiento->where('estado', 1)->first()?->created_at ?? 'Sin Terminar',
                'acciones' => $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        [
                            'funcion' => "ShowDetail(this, '$incidencia->cod_incidencia')",
                            'texto' => '<i class="fas fa-info text-info me-2"></i> Detalle Incidencia'
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
    }    
}
