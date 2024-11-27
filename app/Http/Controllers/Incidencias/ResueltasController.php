<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
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
            return view('incidencias.resueltas');
        } catch (Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Terrible lo que pasará, ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener información externa y de la base de datos
        $company = DB::table('tb_empresas')->get()->keyBy('id'); //$this->fetchAndParseApiData('empresas');
        $subcompany = DB::table('tb_sucursales')->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');

        $tIncidencia = $this->fetchAndParseDbData('tb_tipo_incidencia', ["id_tipo_incidencia as id", 'descripcion', 'estatus']);
        $problema = $this->fetchAndParseDbData('tb_problema', ["id_problema as id", 'descripcion', 'estatus'], "CONCAT(codigo, ' - ', descripcion) AS text");
        $subproblema = $this->fetchAndParseDbData('tb_subproblema', ["id_subproblema as id", 'descripcion', 'estatus'], "CONCAT(codigo_sub, ' - ', descripcion) AS text");

        $usuarios = DB::table('usuarios')->where('estatus', 1)->get()->keyBy('id_usuario')->map(function ($user) {
            $nombre = ucwords(strtolower("{$user->nombres} {$user->apellidos}"));
            return [
                'id' => $user->id_usuario,
                'nombre' => $nombre,
                'documento' => $user->ndoc_usuario,
                'dValue' => base64_encode(json_encode([
                    'id' => $user->id_usuario,
                    'doc' => $user->ndoc_usuario,
                    'nombre' => $nombre
                ])),
                'text' => "{$user->ndoc_usuario} - {$nombre}"
            ];
        });

        $orden = DB::table('tb_orden_servicio')->where('estatus', 1)->get();
        $inc = DB::table('tb_incidencias')->where(['estatus' => 1, 'estado_informe' => 3])->get();
        $inc_seguimiento = DB::table('tb_inc_seguimiento')->get()->groupBy('cod_incidencia');
        $inc_asig = DB::table('tb_inc_asignadas')->get()->groupBy('cod_incidencia');

        // Procesar incidencias
        $incidencias = $inc->mapWithKeys(function ($incidencia) use ($company, $subcompany, $tIncidencia, $problema, $subproblema) {
            return [
                $incidencia->cod_incidencia => [
                    'empresa' => optional($company[$incidencia->id_empresa])->ruc . ' - ' . optional($company[$incidencia->id_empresa])->razon_social,
                    'sucursal' => optional($subcompany[$incidencia->id_sucursal])->nombre,
                    'tipo_orden' => optional($tIncidencia[$incidencia->id_tipo_incidencia])->descripcion,
                    'problema' => (optional($problema[$incidencia->id_problema])->descripcion) . ' / ' . (optional($subproblema[$incidencia->id_subproblema])->descripcion)
                ]
            ];
        });

        // Procesar órdenes
        $orden->each(function ($ord) use ($incidencias, $inc_seguimiento, $inc_asig, $usuarios) {
            $codIncidencia = $ord->cod_incidencia;
            $seguimiento = $inc_seguimiento[$codIncidencia] ?? collect();
            $asignados = $inc_asig[$codIncidencia]->map(fn($asig) => $usuarios[$asig->id_usuario]['nombre'] ?? 'N/A')->implode(', ');

            $ord->empresa = $incidencias[$codIncidencia]['empresa'] ?? 'Desconocido';
            $ord->sucursal = $incidencias[$codIncidencia]['sucursal'] ?? 'Desconocido';
            $ord->tipo_orden = $incidencias[$codIncidencia]['tipo_orden'] ?? 'Desconocido';
            $ord->problema = $incidencias[$codIncidencia]['problema'] ?? 'Sin especificar';
            $ord->f_inicio = $seguimiento->where('estado', 0)->first()?->created_at ?? 'N/A';
            $ord->f_final = $seguimiento->where('estado', 1)->first()?->created_at ?? 'N/A';
            $ord->asignados = $asignados;
            $ord->acciones = $this->DropdownAcciones([
                'tittle' => 'Acciones',
                'button' => [
                    ['funcion' => "ShowDetail(this, '$codIncidencia')", 'texto' => '<i class="fas fa-info text-info me-2"></i> Detalle Incidencia'],
                    ['funcion' => "OrdenDisplay(this, '$ord->cod_ordens')", 'texto' => '<i class="far fa-file-lines text-primary me-2"></i> Ver Orden'],
                    ['funcion' => "OrdenPdf('$ord->cod_ordens')", 'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF'],
                    ['funcion' => "OrdenTicket('$ord->cod_ordens')", 'texto' => '<i class="fas fa-ticket text-warning me-2"></i> Ver Ticket'],
                    ['funcion' => "AddSignature(this, '$ord->cod_ordens')", 'texto' => '<i class="fas fa-signature me-2"></i> Agregar Firma']
                ]
            ]);
        });

        return ['data' => $orden];
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
