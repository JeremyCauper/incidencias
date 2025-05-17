<?php

namespace App\Http\Controllers\Soporte\Visitas;

use App\Http\Controllers\Controller;
use App\Services\SqlStateHelper;
use Exception;
use Illuminate\Database\QueryException;
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
            $usuario = DB::table('tb_personal')->select('id_usuario', 'nombres', 'apellidos')->get()->keyBy('id_usuario');
            $asignadas = DB::table('tb_vis_asignadas')->get()->groupBy('id_visitas')->map(function ($items) use ($usuario) {
                $id_usu = [];
                foreach ($items as $item) {
                    $id_usu[] = $this->formatearNombre($usuario[$item->id_usuario]->nombres, $usuario[$item->id_usuario]->apellidos);
                }
                return $id_usu;
            });
            $conteo = [
                "vEnProceso" => 0,
                "vSinIniciar" => 0
            ];

            $visitas = DB::table('tb_visitas')->where(['eliminado' => 0])->whereNot('estado', 2)->get()->map(function ($val) use ($asignadas, &$conteo) {
                // Configurar el estado del informe
                $estadoVisita = [
                    "0" => ['c' => 'warning', 't' => 'Sin Iniciar'],
                    "1" => ['c' => 'primary', 't' => 'En Proceso'],
                ];
                switch ($val->estado) {
                    case 0:
                        $conteo['vSinIniciar']++;
                        break;

                    case 1:
                        $conteo['vEnProceso']++;
                        break;
                }
                $badge_visitas = '<label class="badge badge-' . $estadoVisita[$val->estado]['c'] . '" style="font-size: .7rem;">' . $estadoVisita[$val->estado]['t'] . '</label>';

                return [
                    'id' => $val->id,
                    'sucursal' => $val->id_sucursal,
                    'tecnicos' => join(", ", $asignadas[$val->id]),
                    'fecha' => $val->fecha,
                    'estado' => $badge_visitas,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => $badge_visitas,
                        'button' => [
                            ['funcion' => "ShowDetail(this, $val->id)", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                            $val->estado == 0 ? ['funcion' => "StartVisita($val->id, $val->estado)", 'texto' => '<i class="' . ($val->estado != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado != 2 ? 'Iniciar' : 'Reiniciar') . ' Visita'] : null,
                            ['funcion' => "ShowAssign(this, $val->id)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'],
                            $val->estado == 1 ? ['funcion' => "OrdenVisita(this, '$val->id')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de Visita'] : null,
                            ['funcion' => "DeleteVisita($val->id)", 'texto' => '<i class="far fa-trash-can text-danger me-2"></i> Eliminar'],
                        ],
                    ])
                ];
            });

            return ["data" => $visitas, "conteo" => $conteo];
        } catch (QueryException $e) {
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo obtener informacion la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Verificamos si se encontró la visita por ID
            $visita = DB::table('tb_visitas')->where(['id' => $id])->first();
            $ordenv = DB::table('tb_orden_visita')->select('cod_orden_visita')->where('id_visita', $id)->first();
            if ($ordenv) {
                $visita->cod_ordenv = $ordenv->cod_orden_visita;
                $visita->new_cod_ordenv = DB::select('CALL GetCodeOrdVis(?)', [date('y')])[0]->cod_orden;
            }

            if (!$visita) {
                return $this->message(message: 'La vista que buscas no existe', status: 204);
            }

            $id_asignados = DB::table('tb_vis_asignadas')->where('id_visitas', $id)->get()->pluck('id_usuario')->toArray();
            $visita->personal_asig = DB::table('tb_personal')->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos'])
                ->whereIn('id_usuario', $id_asignados)->get()
                ->map(function ($usu) {
                    $nombre = $this->formatearNombre($usu->nombres, $usu->apellidos);
                    return [
                        'id' => $usu->id_usuario,
                        'dni' => $usu->ndoc_usuario,
                        'tecnicos' => $nombre
                    ];
                });
            $visita->seguimiento = DB::table('tb_vis_seguimiento')->where('id_visitas', $id)->get()->keyBy('estado');

            return $this->message(data: ['data' => $visita]);
        } catch (QueryException $e) {
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo obtener informacion la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function detail(string $id)
    {
        try {
            // Consultas iniciales para obtener datos de la visita, asignaciones y seguimiento
            $visita = DB::table('tb_visitas')->where(['id' => $id])->first();
            if (!$visita) {
                return $this->message(message: 'La vista que buscas no existe', status: 404);
            }
            $asignados = DB::table('tb_vis_asignadas')->where('id_visitas', $id)->get();
            $seguimiento = DB::table('tb_vis_seguimiento')->where('id_visitas', $id)->get();
            // Obtenemos todos los usuarios activos y los almacenamos en un array asociativo por id
            $personal = DB::table('tb_personal')->get()->keyBy('id_usuario')->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return (object) [
                    "foto" => $u->foto_perfil,
                    "tecnico" => $nombre,
                    "email" => $u->email_corporativo,
                    "telefono" => $u->tel_corporativo
                ];
            });

            // Construcción del arreglo de datos
            $data = [
                "registro" => $this->formatInfoData(
                    $personal,
                    $visita->id_creador,
                    $visita->id_creador,
                    $visita->created_at
                )
            ];

            // Procesamos las asignaciones: agrupamos por creador usando groupBy
            $asignadosGrouped = $asignados->groupBy('creador');
    
            // Recorremos cada grupo de asignaciones y formateamos la información
            $data['asignado'] = $asignadosGrouped->map(function ($items, $creatorId) use ($personal) {
                // Información del usuario creador de la asignación
                $info = $this->formatInfoData($personal, $creatorId, $creatorId, "");
                // Se agregan los técnicos asignados
                $info['tecnicos'] = $items->map(function ($item) use ($personal) {
                    return $this->formatInfoData(
                        $personal,
                        $item->id_usuario,
                        $item->id_usuario,
                        $item->created_at
                    );
                })->toArray();
                return $info;
            })->values()->toArray(); 

            // Procesamos el seguimiento: se asigna a "inicio" o "final" según el estado
            foreach ($seguimiento as $item) {
                $estadoTexto = $item->estado ? "final" : "inicio";
                $data[$estadoTexto] = $this->formatInfoData(
                    $personal,
                    $item->id_usuario,
                    $item->id_usuario,
                    $item->created_at
                );
            }

            return $this->message(data: ['data' => ['visita' => $visita, 'seguimiento' => $data]]);

        } catch (QueryException $e) {
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo obtener detalle la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Formatea la información de contacto del usuario.
     */
    private function formatInfoData($usuario, $creador, $vPersonal, $date)
    {
        $imagen = $usuario[$creador]->foto ?? 'user_auth.jpg';
        return [
            'img' => asset("front/images/auth/{$imagen}"),
            'nombre' => $usuario[$creador]->tecnico,
            'telefono' => $usuario[$vPersonal]->telefono ?: "999999999",
            'email' => $usuario[$vPersonal]->email ?: "soporte01@rcingenieros.com",
            'date' => $date
        ];
    }

    public function startVisita(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                // 'estado' => 'required|integer'
            ]);

            if ($validator->fails())
                return $this->message(data: ['required' => $validator->errors()], status: 422);

            $iniciado = DB::table('tb_visitas')->select('estado')->where('id', $request->id)->first();
            if ($iniciado->estado == 1) {
                return $this->message(message: "La visita ya fue iniciada y está en <b>proceso</b>.", status: 202);
            }

            $validacion = DB::table('tb_vis_asignadas')->where('id_visitas', $request->id)->count();
            if (!$validacion) {
                return $this->message(message: "No se puede iniciar la visita, ya que no tienen un tecnico asignado.", status: 204);
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

            return $this->message(message: 'La visita se ' . ($accion == 1 ? '' : 're') . 'inició con exito.');
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo iniciar la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function assignPer(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer'
            ]);
            if ($validator->fails())
                return $this->message(data: ['required' => $validator->errors()], status: 422);

            $vPersonal = $request->personal_asig;

            if (count($request->personal_asig)) {
                $arr_personal_new = [];
                $indice_new = 0;
                $arr_personal_del = [];
                foreach ($vPersonal as $k => $val) {
                    if (!$val['eliminado'] && $val['registro']) {
                        $arr_personal_new[$indice_new]['id_visitas'] = $request->id;
                        $arr_personal_new[$indice_new]['id_usuario'] = $val['id'];
                        $arr_personal_new[$indice_new]['creador'] = Auth::user()->id_usuario;
                        $arr_personal_new[$indice_new]['fecha'] = now()->format('Y-m-d');
                        $arr_personal_new[$indice_new]['hora'] = now()->format('H:i:s');
                        $arr_personal_new[$indice_new]['created_at'] = now()->format('Y-m-d H:i:s');
                        $vPersonal[$val['id']]['registro'] = 0;
                        $indice_new++;
                    }
                    if ($val['eliminado'] && !$val['registro']) {
                        array_push($arr_personal_del, $val['id']);
                        unset($vPersonal[$val['id']]);
                    }
                }
            }

            if (!count($vPersonal)) {
                return $this->message(message: 'Tiene que tener almenos un personal asignado', status: 500);
            }

            DB::beginTransaction();
            if (!empty($arr_personal_new)) {
                DB::table('tb_vis_asignadas')->insert($arr_personal_new);
            }

            if (!empty($arr_personal_del) && $request->estado) {
                DB::table('tb_vis_asignadas')->where('id_visitas', $request->id)->where('id_usuario', $arr_personal_del)->delete();
            }

            DB::commit();
            return $this->message(message: 'Personal asignado con éxito', data: ['data' => ['personal' => $vPersonal]]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo asignar personal a la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
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
                    return $this->message(data: ['required' => $validator->errors()], status: 422);

                DB::beginTransaction();
                DB::table('tb_visitas')->where('id', $request->id)->update(['eliminado' => 1]);
                DB::commit();

                return $this->message(message: "La visita se eliminó con exito");
            }
            return $this->message(message: "No tiene los permisos requeridos", status: 401);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo eliminar la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
