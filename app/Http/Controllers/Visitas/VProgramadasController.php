<?php

namespace App\Http\Controllers\Visitas;

use App\Http\Controllers\Controller;
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
                            ['funcion' => "ShowAssign(this, $val->id)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'],
                            $val->estado == 1 ? ['funcion' => "OrdenVisita(this, '$val->id')", 'texto' => '<i class="fas fa-book-medical text-primary me-2"></i> Orden de Visita'] : null,
                            ['funcion' => "DeleteVisita($val->id)", 'texto' => '<i class="far fa-trash-can text-danger me-2"></i> Eliminar'],
                        ],
                    ])
                ];
            });

            return $visitas;
        } catch (QueryException $e) {
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
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
            if (!$visita) {
                return $this->message(message: 'La vista que buscas no existe', status: 404);
            }

            $sucursal = DB::table('tb_sucursales')->where('id', $visita->id_sucursal)->first();
            $empresa = DB::table('tb_empresas')->where('ruc', $sucursal->ruc)->first();

            $visita->sucursal = $sucursal->nombre;
            $visita->direccion = $sucursal->direccion;
            $visita->empresa = "{$empresa->ruc} - {$empresa->razon_social}";

            $visita->personal_asig = DB::table('tb_vis_asignadas')->select(['id_visitas', 'id_usuario'])->where('id_visitas', $id)->get();

            return $this->message(data: ['data' => $visita]);
        } catch (QueryException $e) {
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
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
            return $this->message(data: ['data' => ['visita' => $visita, 'seguimiento' => $data]]);
        } catch (QueryException $e) {
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
                return $this->message(data: ['required' => $validator->errors()], status: 422);

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
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
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

            $personal = $request->personal_asig;
            
            if (count($request->personal_asig)) {
                $arr_personal_new = [];
                $indice_new = 0;
                $arr_personal_del = [];
                foreach ($personal as $k => $val) {
                    if (!$val['eliminado'] && $val['registro']) {
                        $arr_personal_new[$indice_new]['id_visitas'] = $request->id;
                        $arr_personal_new[$indice_new]['id_usuario'] = $val['id'];
                        $arr_personal_new[$indice_new]['creador'] = Auth::user()->id_usuario;
                        $arr_personal_new[$indice_new]['fecha'] = now()->format('Y-m-d');
                        $arr_personal_new[$indice_new]['hora'] = now()->format('H:i:s');
                        $arr_personal_new[$indice_new]['created_at'] = now()->format('Y-m-d H:i:s');
                        $personal[$val['id']]['registro'] = 0;
                        $indice_new++;
                    }
                    if ($val['eliminado'] && !$val['registro']) {
                        array_push($arr_personal_del, $val['id']);
                        unset($personal[$val['id']]);
                    }
                }
            }

            if (!count($personal)) {
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
            return $this->message(message: 'Personal asignado con éxito', data: ['data' => ['personal' => $personal]]);
        } catch (QueryException $e) {
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        } finally {
            DB::rollBack();
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
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
