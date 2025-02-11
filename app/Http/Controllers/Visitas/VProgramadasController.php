<?php

namespace App\Http\Controllers\Visitas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VProgramadasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $usuario = DB::table('usuarios')->select('id_usuario', 'nombres', 'apellidos')->get()->keyBy('id_usuario');
            $asignadas = DB::table('tb_vis_asignadas')->get()->groupBy('id_visitas')->map(function ($items) use($usuario) {
                $id_usu = [];
                foreach ($items as $item) {
                    $id_usu[] = $this->formatearNombre($usuario[$item->id_usuario]->nombres, $usuario[$item->id_usuario]->apellidos);
                }
                return $id_usu;
            });
            $sucursales = DB::table('tb_sucursales')->select('id', 'nombre')->where('v_visitas', 1)->get()->keyBy('id');

            $visitas = DB::table('tb_visitas')->where(['eliminado' => 0, 'estado' => 0])->get()->map(function ($val) use($asignadas, $sucursales, $usuario) {
                // Configurar el estado del informe
                $estadoVisita = [
                    "0" => ['c' => 'warning', 't' => 'Sin Iniciar'],
                    "1" => ['c' => 'info', 't' => 'Asignada'],
                    "2" => ['c' => 'primary', 't' => 'En Proceso'],
                    "4" => ['c' => 'danger', 't' => 'Faltan Datos']
                ];
                $badge_visitas = '<label class="badge badge-' . $estadoVisita[$val->estado]['c'] . '" style="font-size: .7rem;">' . $estadoVisita[$val->estado]['t'] . '</label>';

                return [
                    'id' => $val->id,
                    'sucursal' => $sucursales[$val->id]->nombre,
                    'tecnicos' => join(", ", $asignadas[$val->id]),
                    'fecha' => $val->fecha,
                    'estado' => $badge_visitas,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => $badge_visitas,
                        'button' => [
                            ['funcion' => "ShowDetail(this, $val->id)", 'texto' => '<i class="fas fa-eye text-success me-2"></i> Ver Detalle'],
                            $val->estado == 0 ? ['funcion' => "StartInc($val->id, $val->estado)", 'texto' => '<i class="' . ($val->estado != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado != 2 ? 'Iniciar' : 'Reiniciar') . ' Visita'] : null,
                            $val->estado != 1 ? ['funcion' => "ShowAssign(this, $val->id)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'] : null,
                            $val->estado == 1 ? ['funcion' => "OrdenDetail(this, '$val->id')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de Visita'] : null,
                            $val->estado != 1 ? ['funcion' => "DeleteInc($val->id)", 'texto' => '<i class="far fa-trash-can text-danger me-2"></i> Eliminar'] : null,
                        ],
                    ])
                ];
            });

            return $visitas;
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }
}
