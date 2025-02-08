<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Empresas\EmpresasController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Throw_;

class ResueltasController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            $data['empresas'] = (new EmpresasController())->index();
            $data['sucursales'] = DB::table('tb_sucursales')->where('status', 1)->get()->keyBy('id');
            return view('incidencias.resueltas', ['data' => $data]);
        } catch (Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Terrible lo que pasará, ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $ruc = $request->query('ruc');
        $sucursal = $request->query('sucursal');
        $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
        $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
    
        $whereInc = ['estatus' => 1, 'estado_informe' => 3];
        if (intval($ruc)) {
            $whereInc['id_empresa'] = intval($ruc);
        }
        if (intval($sucursal)) {
            $whereInc['id_sucursal'] = intval($sucursal);
        }
        $incidencias = DB::table('tb_incidencias')->where($whereInc)->get();
        $ordenes = DB::table('tb_orden_servicio')
            ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])
            ->get()
            ->groupBy('cod_incidencia'); // Agrupa las órdenes por `cod_incidencia`
    
        $tipos_incidencia = DB::table('tb_tipo_incidencia')->get();
        $problemas = DB::table('tb_problema')->get();
        $subproblemas = DB::table('tb_subproblema')->get();
        $empresas = DB::table('tb_empresas')->get();
        $sucursales = DB::table('tb_sucursales')->get();
    
        $inc_seguimiento = DB::table('tb_inc_seguimiento')->get()->groupBy('cod_incidencia');
        $inc_asig = DB::table('tb_inc_asignadas')->get()->groupBy('cod_incidencia');
        $usuarios = DB::table('usuarios')->where('estatus', 1)->get()->keyBy('id_usuario')->map(function ($user) {
            $nombre = ucwords(strtolower("{$user->nombres} {$user->apellidos}"));
            return [
                'id' => $user->id_usuario,
                'nombre' => $nombre,
            ];
        });
    
        $resultado = $incidencias->filter(function ($inc) use ($ordenes) {
                return $ordenes->has($inc->cod_incidencia); // Verifica si hay una orden para esta incidencia
            })
            ->map(function ($incidencia) use ($ordenes, $tipos_incidencia, $problemas, $subproblemas, $empresas, $sucursales, $inc_seguimiento, $inc_asig, $usuarios) {
                $orden = $ordenes->get($incidencia->cod_incidencia)?->first(); // Obtén la primera orden correspondiente
                $tipo_incidencia = $tipos_incidencia->firstWhere('id_tipo_incidencia', $incidencia->id_tipo_incidencia);
                $problema = $problemas->firstWhere('id_problema', $incidencia->id_problema);
                $subproblema = $subproblemas->firstWhere('id_subproblema', $incidencia->id_subproblema);
                $empresa = $empresas->firstWhere('id', $incidencia->id_empresa);
                $sucursal = $sucursales->firstWhere('id', $incidencia->id_sucursal);
    
                $seguimiento = $inc_seguimiento[$incidencia->cod_incidencia] ?? collect();
                $asignados = $inc_asig[$incidencia->cod_incidencia]->map(fn($asig) => $usuarios[$asig->id_usuario]['nombre'] ?? 'N/A')->implode(', ');
    
                return [
                    'id_orden' => $orden->id_ordens ?? null,
                    'cod_orden' => $orden->cod_ordens ?? null,
                    'cod_incidencia' => $incidencia->cod_incidencia,
                    'tipo_incidencia' => $tipo_incidencia->descripcion ?? null,
                    'asignados' => $asignados,
                    'fecha_servicio' => $orden->created_at ?? null,
                    'empresa' => $empresa->razon_social ?? null,
                    'nombre_sucursal' => $sucursal->nombre ?? null,
                    'problema' => ($problema->descripcion ?? null) . ' / ' . ($subproblema->descripcion ?? null),
                    'iniciado' => $seguimiento->where('estado', 0)->first()?->created_at ?? 'N/A',
                    'finalizado' => $seguimiento->where('estado', 1)->first()?->created_at ?? 'N/A',
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            ['funcion' => "ShowDetail(this, '$incidencia->cod_incidencia')", 'texto' => '<i class="fas fa-info text-info me-2"></i> Detalle Incidencia'],
                            ['funcion' => "OrdenDisplay(this, '$orden->cod_ordens')", 'texto' => '<i class="far fa-file-lines text-primary me-2"></i> Ver Orden'],
                            ['funcion' => "OrdenPdf('$orden->cod_ordens')", 'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF'],
                            ['funcion' => "OrdenTicket('$orden->cod_ordens')", 'texto' => '<i class="fas fa-ticket text-warning me-2"></i> Ver Ticket'],
                            ['funcion' => "AddSignature('$orden->cod_ordens')", 'texto' => '<i class="fas fa-signature text-secondary me-2"></i> Añadir Firma'],
                        ]
                    ])
                ];
            })->values();
    
        return ['data' => $resultado];
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Consultamos la incidencia por ID
            $incidencia = DB::table('tb_incidencias')->where('id_incidencia', $id)->first();
            $usuarios = db::table('usuarios')->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos'])->where('estatus', 1)->get()->keyBy('id_usuario');

            // Verificamos si se encontró la incidencia
            if (!$incidencia)
                return response()->json(['success' => false, 'message' => 'Incidencia no encontrada']);
            $cod = $incidencia->cod_incidencia;

            // Consultamos los contactos asociados a la incidencia y los añadimos como propiedades del objeto incidencia
            $contacto = DB::table('contactos_empresas')->where('id_contact', $incidencia->id_contacto)->first();
            if ($contacto) {
                foreach ((array) $contacto as $key => $value) {
                    $incidencia->$key = $value;
                }
            }

            $incidencia->personal_asig = DB::table('tb_inc_asignadas')->where('cod_incidencia', $cod)->get()->map(function ($u) use ($usuarios) {
                $nombre = ucwords(strtolower("{$usuarios[$u->id_usuario]->nombres} {$usuarios[$u->id_usuario]->apellidos}"));
                return [
                    'id' => $u->id_usuario,
                    'dni' => $usuarios[$u->id_usuario]->ndoc_usuario,
                    'tecnicos' => $nombre
                ];
            });

            // Retornamos la incidencia con la información de contacto y personal asignado
            return response()->json(['success' => true, 'message' => '', 'data' => $incidencia]);
        } catch (Exception $e) {
            // Manejamos errores y retornamos un mensaje de error claro
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function detail(string $cod)
    {
        try {
            $orden = DB::table('tb_orden_servicio')->where('cod_ordens', $cod)->first();
            if ($orden) {

                $incidencia = DB::table('tb_incidencias')->where(['estatus' => 1, 'cod_incidencia' => $orden->cod_incidencia])->first();
                $usuarios = db::table('usuarios')->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos', 'firma_digital'])->where('estatus', 1)->get()->keyBy('id_usuario');
                $contacto = db::table('tb_contac_ordens')->select(['nro_doc', 'nombre_cliente', 'firma_digital'])->where(['estatus' => 1, 'id' => $orden->id_contacto])->first();

                $orden->contacto = $contacto ?: null;
                $orden->observasion = $incidencia->observasion;
                $orden->personal = DB::table('tb_inc_asignadas')->where('cod_incidencia', $orden->cod_incidencia)->get()->map(function ($u) use ($usuarios) {
                    $nombre = ucwords(strtolower("{$usuarios[$u->id_usuario]->nombres} {$usuarios[$u->id_usuario]->apellidos}"));
                    return [
                        'id' => $u->id_usuario,
                        'dni' => $usuarios[$u->id_usuario]->ndoc_usuario,
                        'tecnicos' => $nombre
                    ];
                });
                $orden->creador = $usuarios[$incidencia->id_usuario];

                return response()->json(['success' => true, 'data' => $orden]);
            } else {
                throw new Exception('La orden consultada, no existe.');
            }
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
