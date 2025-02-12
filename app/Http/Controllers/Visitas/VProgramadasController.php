<?php

namespace App\Http\Controllers\Visitas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

            $visitas = DB::table('tb_visitas')->where(['eliminado' => 0])->whereNot('estado', 2)->get()->map(function ($val) use($asignadas, $sucursales, $usuario) {
                // Configurar el estado del informe
                $estadoVisita = [
                    "0" => ['c' => 'warning', 't' => 'Sin Iniciar'],
                    "1" => ['c' => 'primary', 't' => 'En Proceso'],
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
                            ['funcion' => "ShowDetail(this, $val->id)", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                            $val->estado == 0 ? ['funcion' => "StartVisita($val->id, $val->estado)", 'texto' => '<i class="' . ($val->estado != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado != 2 ? 'Iniciar' : 'Reiniciar') . ' Visita'] : null,
                            $val->estado != 1 ? ['funcion' => "ShowAssign(this, $val->id)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'] : null,
                            $val->estado == 1 ? ['funcion' => "OrdenDetail(this, '$val->id')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de Visita'] : null,
                            ['funcion' => "DeleteVisita($val->id)", 'texto' => '<i class="far fa-trash-can text-danger me-2"></i> Eliminar'],
                        ],
                    ])
                ];
            });

            return $visitas;
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    public function detail(string $id)
    {
        try {
            // Consultas iniciales para obtener datos de la visita, asignaciones y seguimiento
            $visita = DB::table('tb_visitas')->where(['id' => $id])->first();
            if (!$visita) {
                throw new Exception('La vista que buscas no existe', 404);
            }
            $asignados = DB::table('tb_vis_asignadas')->where('id_visitas', $id)->get();
            $seguimiento = DB::table('tb_vis_seguimiento')->where('id_visitas', $id)->get();
            // Obtenemos todos los usuarios activos y los almacenamos en un array asociativo por id
            $usuarios = DB::table('usuarios')->where('estatus', 1)->get()->keyBy('id_usuario')->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return (object) [
                    "foto" => $u->foto_perfil,
                    "tecnico" => $nombre,
                    "email" => $u->email_corporativo,
                    "telefono" => $u->tel_corporativo
                ];
            });

            $sucursal = DB::table('tb_sucursales')->where('id', $visita->id_sucursal)->first();
            $empresa = DB::table('tb_empresas')->where('ruc', $sucursal->ruc)->first();

            $visita->sucursal = $sucursal->nombre;
            $visita->direccion = $sucursal->direccion;
            $visita->empresa = "{$empresa->ruc} - {$empresa->razon_social}";

            // Construcción del arreglo de datos
            $data = [
                $this->formatInfoData($usuarios, $visita->id_creador, $visita->id_creador, $visita->created_at, "Registró la visita")
            ];

            // Procesamos las asignaciones de la visita
            foreach ($asignados as $a) {
                $data[] = $this->formatInfoData($usuarios, $a->creador, $a->id_usuario, $a->created_at, "Asignó la Visita a <b>{$usuarios[$a->id_usuario]->tecnico}</b>");
            }

            // Procesamos el seguimiento de la visita
            foreach ($seguimiento as $s) {
                $estadoTexto = $s->estado ? "Finalizó la visita" : "Inició la visita";
                $data[] = $this->formatInfoData($usuarios, $s->id_usuario, $s->id_usuario, $s->created_at, $estadoTexto);
            }
            return response()->json(['success' => true, 'message' => '', 'data' => ['visita' => $visita, 'seguimiento' => $data]]);
        } catch (Exception $e) {
            return response()->json(["success" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Formatea la información de contacto del usuario.
     */
    private function formatInfoData($usuario, $creador, $personal, $date, $text)
    {
        return [
            'img' => asset("front/images/auth/{$usuario[$creador]->foto}"),
            'nombre' => $usuario[$creador]->tecnico,
            'text' => $text,
            'contacto' => '<i class="fab fa-whatsapp text-success"></i> ' . $usuario[$personal]->telefono . ' / <i class="far fa-envelope text-danger"></i> ' . $usuario[$personal]->email,
            'date' => $date
        ];
    }

    public function startVisita(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'estado' => 'required|integer'
            ]);

            if ($validator->fails())
                return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

            $validacion = DB::table('tb_vis_asignadas')->where('id_visitas', $request->id)->count();
            if (!$validacion) {
                return response()->json(['success' => true, 'message' => 'No se puede iniciar la visita, ya que no tienen un tecnico asignado.'], 500);
            }
            DB::beginTransaction();
            $accion = $request->estado == 0 ? 1 : 0;
            $id = $request->id;
            if ($accion == 0) {
                DB::table('tb_vis_seguimiento')->where('id_visitas', $id)->delete();
            } else {
                DB::table('tb_vis_seguimiento')->insert([
                    'id_visitas' => $id,
                    'id_usuario' => Auth::user()->id_usuario,
                    'fecha' => now()->format('Y-m-d'),
                    'hora' => now()->format('H:i:s'),
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }
            DB::table('tb_visitas')->where('id', $id)->update(['estado' => $accion]);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'La visita se ' . ($accion == 1 ? '' : 're') . 'inició con exito.'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            if (Auth::user()->tipo_acceso != 3) {
                $validator = Validator::make($request->all(), [
                    'id' => 'required|integer'
                ]);
    
                if ($validator->fails())
                    return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

                DB::beginTransaction();
                DB::table('tb_visitas')->where('id', $request->id)->update(['eliminado' => 1]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'La visita se eliminó con exito'
                ], 200);
            }
            return response()->json([
                'success' => false,
                'message' => 'No tiene los permisos requeridos'
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }
}
