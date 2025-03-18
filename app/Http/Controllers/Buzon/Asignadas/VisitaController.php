<?php

namespace App\Http\Controllers\Buzon\Asignadas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitaController extends Controller
{
    public function index(Request $request)
    {
        try {
            $id_visitas = DB::table('tb_vis_asignadas')->where('id_usuario', session('id_usuario'))->pluck('id_visitas')->toArray();
            $asignadas = DB::table('tb_vis_asignadas')->where('id_usuario', session('id_usuario'))->get();

            $count_vis = 0;
            $visita = DB::table('tb_visitas')
                ->whereIn('id', $id_visitas)
                ->whereIn('estado', [0, 1])->where(['eliminado' => 0])->get()
                ->map(function ($vis) use ($asignadas, &$count_vis) {
                $asignada = $asignadas->where('id_visitas', $vis->id)->first()?->created_at ?? 'N/A';

                if ($vis->estado == 0) {
                    $count_vis++;
                }
                $estadoVisita = [
                    "0" => ['c' => 'warning', 't' => 'Sin Iniciar'],
                    "1" => ['c' => 'primary', 't' => 'En Proceso'],
                ];
                $badge_visitas = '<label class="badge badge-' . $estadoVisita[$vis->estado]['c'] . '" style="font-size: .7rem;">' . $estadoVisita[$vis->estado]['t'] . '</label>';

                return [
                    'estado' => $badge_visitas,
                    'registrado' => $vis->created_at ?? null,
                    'id_sucursal' => $vis->id_sucursal,
                    'asignado' => $asignada,
                    'programado' => $vis->fecha,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => $badge_visitas,
                        'button' => [
                            ['funcion' => "ShowDetailVis(this, $vis->id)", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                            $vis->estado == 0 ? ['funcion' => "StartVisita($vis->id, $vis->estado)", 'texto' => '<i class="' . ($vis->estado != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($vis->estado != 2 ? 'Iniciar' : 'Reiniciar') . ' Visita'] : null,
                            $vis->estado == 1 ? ['funcion' => "OrdenVisita(this, '$vis->id')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de Visita'] : null,
                        ]
                    ])
                ];
            })->values();

            return ['data' => $visita, 'count_vis' => $count_vis];
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
