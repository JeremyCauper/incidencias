<?php

namespace App\Http\Controllers\Visitas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Empresas\EmpresasController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VSucursalesController extends Controller
{
    public function view()
    {
        $this->validarPermisos(3, 1);
        try {
            $data = [];
            $data['usuarios'] = DB::table('usuarios')->where('estatus', 1)->get()->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'value' => $u->id_usuario,
                    'dValue' => base64_encode(json_encode(['id' => $u->id_usuario, 'doc' => $u->ndoc_usuario, 'nombre' => $nombre])),
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });
            $data['empresas'] = (new EmpresasController())->index();
            
            return view('visitas.sucursales', ['data' => $data]);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $empresas = DB::table('tb_empresas')->where('contrato', 1)->get()->keyBy('ruc');
            $visitas = DB::table('tb_visitas')->whereMonth('fecha', now()->format('m'))->get()->groupBy('id_sucursal')
                ->map(function ($items) {
                    return $items->mapWithKeys(function ($item) {
                        return [
                            $item->id => $item
                        ];
                    });
                });

            $sucursales = DB::table('tb_sucursales')->where('v_visitas', 1)->get()
                ->filter(fn($val) => isset($empresas[$val->ruc])) // Filtra solo las sucursales con empresas activas
                ->map(function ($val) use ($empresas, $visitas) {
                    $vRealizadas = count($visitas[$val->id] ?? []);
                    $totalVisitas = $empresas[$val->ruc]->visitas;
                    $badgeKey = $vRealizadas ? ($vRealizadas == $totalVisitas ? 'completado' : $vRealizadas) : 0;

                    if ($badgeKey == 'completado') {
                        $acciones = '<button class="btn btn-primary btn-sm px-2" onclick="CompletadoVisita(' . $val->id . ')" data-mdb-ripple-init>
                                        <i class="fas fa-check-double"></i> Completada
                                    </button>';
                    }
                    else if ($badgeKey) {
                        $acciones = '<button class="btn btn-info btn-sm px-2" onclick="DetalleVisita(' . $val->id . ')" data-mdb-ripple-init>
                                        <i class="fas fa-user-check"></i> Asignada
                                    </button>';
                    }
                    else {
                        $acciones = '<button class="btn btn-secondary btn-sm px-2" onclick="AsignarVisita(' . $val->id . ')" data-mdb-ripple-init>
                                        <i class="fas fa-user-gear"></i> Asignar
                                    </button>';
                    }

                    return [
                        'id' => $val->id,
                        'ruc' => $val->ruc,
                        'sucursal' => $val->nombre,
                        'visita' => $badgeKey,
                        'vRealizadas' => $vRealizadas,
                        'totalVisitas' => $totalVisitas,
                        'acciones' => $acciones
                    ];
                })->values(); // Resetea las claves (opcional si no necesitas una colección indexada por ID)

            return $sucursales;
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'idSucursal' => 'required|integer',
                'fecha_visita' => 'required|date'
            ]);

            if ($validator->fails())
                return response()->json([ 'success' => false, 'message' => '', 'validacion' => $validator->errors() ]);

            $personal = $request->personal;
            DB::beginTransaction();
            $idVisita = DB::table('tb_visitas')->insertGetId([
                'id_sucursal' => $request->idSucursal,
                'id_creador' => Auth::user()->id_usuario,
                'fecha' => $request->fecha_visita,
                'hora' => now()->format('H:i:s'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            if (count($personal)) {
                $arr_personal = [];
                foreach ($personal as $k => $val) {
                    $arr_personal[$k]['id_visitas'] = $idVisita;
                    $arr_personal[$k]['id_usuario'] = $val['id'];
                    $arr_personal[$k]['creador'] = Auth::user()->id_usuario;
                    $arr_personal[$k]['fecha'] = now()->format('Y-m-d');
                    $arr_personal[$k]['hora'] = now()->format('H:i:s');
                    $arr_personal[$k]['created_at'] = now()->format('Y-m-d H:i:s');
                }
                DB::table('tb_vis_asignadas')->insert($arr_personal);
            }
            DB::commit();
            return response()->json([ 'success' => true, 'message' => 'Exito al asignar visita' ]);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $sucursal = DB::table('tb_sucursales')->where('id', $id)->first();
            $empresa = DB::table('tb_empresas')->where('ruc', $sucursal->ruc)->first();
            $usuario = DB::table('usuarios')->select('nombres', 'apellidos', 'id_usuario')->get()->keyBy('id_usuario');
            /*$vis_asignadas = DB::table('tb_vis_asignadas')->get()->groupBy('id_visitas');
            return $vis_asignadas;*/

            $visitas = DB::table('tb_visitas')->where('id_sucursal', $id)->whereMonth('fecha', now()->format('m'))->get()->map(function ($v) use ($usuario) {
                $nombre = $this->formatearNombre($usuario[$v->id_creador]->nombres, $usuario[$v->id_creador]->apellidos);
                $v->creador = $nombre;
                return $v;
            });
            
            if ($sucursal) {
                $vRealizadas = count($visitas ?? []);
                $totalVisitas = $empresa->visitas;
                $diasVisitas = $empresa->dias_visita;
                $fechaSumada = null;
                if (count($visitas)) {
                    $ultimoElemento = $visitas[count($visitas) - 1];
                    $fechaSumada = date('Y-m-d', strtotime($ultimoElemento->fecha . " +$diasVisitas days"));
                }
                $result = [
                    'id' => $sucursal->id,
                    'ruc' => $sucursal->ruc,
                    'razonSocial' => $empresa->razon_social,
                    'sucursal' => $sucursal->nombre,
                    'direccion' => $sucursal->direccion,
                    'contrato' => $empresa->contrato,
                    'totalVisitas' => $totalVisitas,
                    'vRealizadas' => $vRealizadas,
                    'diasVisitas' => $diasVisitas,
                    'visitas' => $visitas,
                    'message' => "Podrá asignar visitas después del $fechaSumada."
                ];
            } else {
                $result = null;
            }
            
            return response()->json(['success' => true, 'message' => '', 'data' => $result]);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
