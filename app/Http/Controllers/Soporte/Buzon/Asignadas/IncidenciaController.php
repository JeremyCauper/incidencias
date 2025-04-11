<?php

namespace App\Http\Controllers\Soporte\Buzon\Asignadas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncidenciaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $cod_incidencias = DB::table('tb_inc_asignadas')->where('id_usuario', session('id_usuario'))->pluck('cod_incidencia')->toArray();
            $asignadas = DB::table('tb_inc_asignadas')->where('id_usuario', session('id_usuario'))->get();

            $count_asig = 0;
            $incidencia = DB::table('tb_incidencias')
                ->whereIn('cod_incidencia', $cod_incidencias)
                ->whereIn('estado_informe', [1, 2, 4])->where(['estatus' => 1])->get()
                ->map(function ($inc) use ($asignadas, &$count_asig) {
                $asignada = $asignadas->where('cod_incidencia', $inc->cod_incidencia)->first()?->created_at ?? 'N/A';

                if ($inc->estado_informe == 1) {
                    $count_asig++;
                }
                $estadoInforme = [
                    "1" => ['c' => 'info', 't' => 'Asignada'],
                    "2" => ['c' => 'primary', 't' => 'En Proceso'],
                    "4" => ['c' => 'danger', 't' => 'Faltan Datos']
                ];
                $badge_informe = '<label class="badge badge-' . $estadoInforme[$inc->estado_informe]['c'] . '" style="font-size: .7rem;">' . $estadoInforme[$inc->estado_informe]['t'] . '</label>';

                return [
                    'cod_inc' => $inc->cod_incidencia ?? null,
                    'estado_informe' => $inc->estado_informe,
                    'estado' => $badge_informe,
                    'registrado' => $inc->created_at ?? null,
                    'iniciado' => $asignada,
                    'id_sucursal' => $inc->id_sucursal,
                    'id_tipo_estacion' => $inc->id_tipo_estacion,
                    'problema' => $inc->id_problema,
                    'subproblema' => $inc->id_subproblema,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => $badge_informe,
                        'button' => [
                            ['funcion' => "ShowDetailInc(this, '$inc->cod_incidencia')", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                            $inc->estado_informe == 1 ? ['funcion' => "StartInc('$inc->cod_incidencia', $inc->estado_informe)", 'texto' => '<i class="' . ($inc->estado_informe != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($inc->estado_informe != 2 ? 'Iniciar' : 'Reiniciar') . ' Incidencia'] : null,
                            $inc->estado_informe == 2 ? ['funcion' => "OrdenDetail(this, '$inc->cod_incidencia')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de servicio'] : null,
                            $inc->estado_informe == 4 ? ['funcion' => "AddCodAviso(this, '$inc->cod_incidencia')", 'texto' => '<i class="far fa-file-code text-warning me-2"></i> Añadir Cod. Aviso'] : null,
                        ]
                    ])
                ];
            })->values();

            return ['data' => $incidencia, 'count_asig' => $count_asig];
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
