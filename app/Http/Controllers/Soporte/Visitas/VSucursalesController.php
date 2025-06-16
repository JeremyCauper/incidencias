<?php

namespace App\Http\Controllers\Soporte\Visitas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Empresas\EmpresasController;
use App\Services\SqlStateHelper;
use Exception;
use Illuminate\Database\QueryException;
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
            // Obtener información externa de la API
            $data['company'] = DB::table('tb_empresas')->select(['id', 'ruc', 'razon_social', 'direccion', 'contrato', 'codigo_aviso', 'status'])->get()->keyBy('ruc'); //$this->fetchAndParseApiData('empresas');
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');
            $data['usuarios'] = DB::table('tb_personal')->where(['id_area' => 1])->get()->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'value' => $u->id_usuario,
                    'dValue' => base64_encode(json_encode(['id' => $u->id_usuario, 'doc' => $u->ndoc_usuario, 'nombre' => $nombre])),
                    'id' => $u->id_usuario,
                    'doc' => $u->ndoc_usuario,
                    'nombre' => $nombre,
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });
            $data['cod_ordenv'] = DB::select("CALL GetCodeOrdVis(?)", [date('y')])[0]->cod_orden;

            return view('soporte.visitas.sucursales', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $empresas = DB::table('tb_empresas')->where('contrato', 1)->get()->keyBy('ruc');
            $visitas = DB::table('tb_visitas')->where('eliminado', 0)->whereMonth('fecha', now()->month)->get()->groupBy('id_sucursal');
            $conteo = ['vSinAsignar' => 0, 'vAsignadas' => 0];

            $sucursales = DB::table('tb_sucursales')
                ->where('v_visitas', 1)
                ->get()
                ->filter(fn($s) => isset($empresas[$s->ruc])) // Si sigues con ruc
                ->map(function ($s) use ($empresas, $visitas, &$conteo) {
                    $empresa = $empresas[$s->ruc];
                    $visitasSucursal = $visitas[$s->id] ?? collect();
                    $vRealizadas = $visitasSucursal->count();
                    $totalVisitas = $empresa->visitas;

                    $badge = $vRealizadas == $totalVisitas && $totalVisitas > 0 ? 'completado' : ($vRealizadas > 0 ? $vRealizadas : 0);

                    $estado = match (true) { $badge === 'completado' => 2, $badge > 0 => 1, default => 0};

                    match ($estado) {
                        2 => null,
                        1 => $conteo['vAsignadas']++,
                        0 => $conteo['vSinAsignar']++,
                    };

                    $acciones = [
                        2 => ['color' => 'primary', 'fn' => "DetalleVisita($s->id)", 'icon' => 'check-double'],
                        1 => ['color' => 'info', 'fn' => "DetalleVisita($s->id)", 'icon' => 'user-check'],
                        0 => ['color' => 'secondary', 'fn' => "AsignarVisita($s->id)", 'icon' => 'user-gear'],
                    ][$estado];
                    $accion = '<button class="btn btn-' . ($acciones['color']) . ' btn-sm px-2" onclick="' . ($acciones['fn']) . '" data-estado="' . $estado . '" data-mdb-ripple-init><i class="fas fa-' . ($acciones['icon']) . '"></i></button>';

                    return [
                        'id' => $s->id,
                        'ruc' => $s->ruc,
                        'sucursal' => $s->nombre,
                        'visita' => $badge,
                        'vRealizadas' => $vRealizadas,
                        'totalVisitas' => $totalVisitas,
                        'estado' => $estado,
                        'acciones' => $accion
                    ];
                })->sortBy('estado')->values();

            return ['data' => $sucursales, 'conteo' => $conteo];
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
                return $this->message(data: ['required' => $validator->errors()], status: 422);

            $vPersonal = $request->personal;
            DB::beginTransaction();
            $idVisita = DB::table('tb_visitas')->insertGetId([
                'id_sucursal' => $request->idSucursal,
                'id_creador' => Auth::user()->id_usuario,
                'fecha' => $request->fecha_visita,
                'hora' => now()->format('H:i:s'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            if (count($vPersonal)) {
                $arr_personal = [];
                foreach ($vPersonal as $k => $val) {
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
            return $this->message(message: "Exito al asignar visita");
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo registrar la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $sucursal = DB::table('tb_sucursales')->where('id', $id)->first();
            if (empty($sucursal)) {
                return $this->message(message: "No sé encontró el registro buscado.", status: 204);
            }
            $empresa = DB::table('tb_empresas')->where('ruc', $sucursal->ruc)->first();
            $usuario = DB::table('tb_personal')->select('nombres', 'apellidos', 'id_usuario')->get()->keyBy('id_usuario');

            $visitas = DB::table('tb_visitas')->where(['id_sucursal' => $id, 'eliminado' => 0])->whereMonth('fecha', now()->format('m'))->get()->map(function ($v) use ($usuario) {
                $nombre = $this->formatearNombre($usuario[$v->id_creador]->nombres, $usuario[$v->id_creador]->apellidos);
                $v->creador = $nombre;
                return $v;
            });

            $vRealizadas = count($visitas ?? []);
            $totalVisitas = $empresa->visitas;
            $diasVisitas = $empresa->dias_visita + 1;
            $fechaVisita = now()->addMonth()->startOfMonth()->format('Y-m-d');
            ;
            if (count($visitas) && $totalVisitas > $vRealizadas) {
                $ultimoElemento = $visitas[count($visitas) - 1];
                $fechaVisita = date('Y-m-d', strtotime($ultimoElemento->fecha . " +$diasVisitas days"));
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
                'message' => "Podrá asignar visitas desde el $fechaVisita."
            ];

            return $this->message(data: ['data' => $result]);
        } catch (QueryException $e) {
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo obtener la informacion la visita." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
