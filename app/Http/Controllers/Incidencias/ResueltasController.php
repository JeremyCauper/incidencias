<?php

namespace App\Http\Controllers\Incidencias;

use App\Helpers\CargoEstacion;
use App\Helpers\TipoEstacion;
use App\Helpers\TipoIncidencia;
use App\Helpers\TipoSoporte;
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
        $this->validarPermisos(2);
        try {
            $data = [];
            // Obtener información externa de la API
            $data['company'] = DB::table('tb_empresas')->select(['id', 'ruc', 'razon_social', 'direccion', 'contrato', 'codigo_aviso', 'status'])->get()->keyBy('ruc'); //$this->fetchAndParseApiData('empresas');
            $data['scompany'] = DB::table('tb_sucursales')->select(['id', 'ruc', 'nombre', 'direccion', 'status'])->get()->keyBy('id'); //$this->fetchAndParseApiData('sucursales');

            // Obtener información de base de datos local
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problema'] = $this->fetchAndParseDbData('tb_problema', ["id_problema as id", 'tipo_incidencia', 'estatus'], "CONCAT(codigo, ' - ', descripcion) AS text");
            $data['sproblema'] = $this->fetchAndParseDbData('tb_subproblema', ["id_subproblema as id", 'id_problema', 'estatus'], "CONCAT(codigo_sub, ' - ', descripcion) AS text");

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
        if ($ruc) {
            $whereInc['ruc_empresa'] = $ruc;
        }
        if (intval($sucursal)) {
            $whereInc['id_sucursal'] = intval($sucursal);
        }

        $incidencias = DB::table('tb_incidencias')->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])->where($whereInc)->get();
        $cod_incidencias = $incidencias->pluck('cod_incidencia')->toArray();

        $seguimientos = DB::table('tb_inc_seguimiento')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
        $inc_asig = DB::table('tb_inc_asignadas')->whereIn('cod_incidencia', $cod_incidencias)->get()->groupBy('cod_incidencia');
        $usuarios = DB::table('usuarios')->get()->map(function ($user) {
            $nombre = $this->formatearNombre($user->nombres, $user->apellidos);
            return (object)[
                'id' => $user->id_usuario,
                'nombre' => $nombre,
            ];
        });

        $ordenes = DB::table('tb_orden_servicio')
            ->whereIn('cod_incidencia', $cod_incidencias)->get();
        $id_contac_ordens = $ordenes->pluck('id_contacto')->toArray();
        $contac_ordens = DB::table('tb_contac_ordens')->whereIn('id', $id_contac_ordens)->get();

        $incidencias = $incidencias->map(function ($incidencia) use ($ordenes, $seguimientos, $inc_asig, $usuarios, $contac_ordens) {
            $orden = $ordenes->where('cod_incidencia', $incidencia->cod_incidencia)->first();
            $cod_ordens = $orden->cod_ordens;
            $id_asignados = collect($inc_asig[$incidencia->cod_incidencia])->pluck('id_usuario')->toArray();
            $asignados = collect($usuarios)->whereIn('id', $id_asignados)->pluck('nombre')->toArray();
            $seguimiento = $seguimientos[$incidencia->cod_incidencia] ?? collect();

            $contac = false;
            if (!empty($orden->id_contacto)) {
                $contac_ordens = $contac_ordens->firstWhere('id', $orden->id_contacto);
                $contac = !empty($contac_ordens->firma_digital) && !empty($contac_ordens->nro_doc) && !empty($contac_ordens->nombre_cliente);
            }

            return [
                'cod_incidencia' => $incidencia->cod_incidencia,
                'cod_orden' => '<label class="badge badge-info" style="font-size: .7rem;">' . $orden->cod_ordens . '</label>' ?? null,
                'fecha_inc' => $incidencia->created_at ?? null,
                'asignados' => implode(", ", $asignados) ?? null,
                'empresa' => $incidencia->ruc_empresa,
                'sucursal' => $incidencia->id_sucursal,
                'tipo_incidencia' => $incidencia->id_tipo_incidencia,
                'problema' => $incidencia->id_problema,
                'subproblema' => $incidencia->id_subproblema,
                'iniciado' => $seguimiento->where('estado', 0)->first()?->created_at ?? 'N/A',
                'finalizado' => $seguimiento->where('estado', 1)->first()?->created_at ?? 'N/A',
                'acciones' => $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "ShowDetail(this, '$incidencia->cod_incidencia')", 'texto' => '<i class="fas fa-info text-info me-2"></i> Detalle Incidencia'],
                        // ['funcion' => "OrdenDisplay(this, '$incidencia->cod_incidencia')", 'texto' => '<i class="far fa-file-lines text-primary me-2"></i> Ver Orden'],
                        ['funcion' => "OrdenPdf('$cod_ordens')", 'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF'],
                        ['funcion' => "OrdenTicket('$cod_ordens')", 'texto' => '<i class="fas fa-ticket text-warning me-2"></i> Ver Ticket'],
                        $contac ? null : ['funcion' => "AddSignature(this, '$cod_ordens')", 'texto' => '<i class="fas fa-signature text-secondary me-2"></i> Añadir Firma'],
                    ]
                ])
            ];
        });

        return ['data' => $incidencias];
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            // Consultamos la incidencia por ID
            $incidencia = DB::table('tb_incidencias')->where('id_incidencia', $id)->first();
            $usuarios = db::table('usuarios')->select(['id_usuario', 'ndoc_usuario', 'nombres', 'apellidos'])->get()->keyBy('id_usuario');

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

    /**
     * Show the form for editing the specified resource.
     */
    public function showSignature(string $cod)
    {
        try {
            $orden = DB::table('tb_orden_servicio')->where('cod_ordens', $cod)->first();
            $contacto = null;
            if ($orden && !empty($orden->id_contacto)) {
                $contacto = db::table('tb_contac_ordens')->select(['id', 'nro_doc', 'nombre_cliente', 'firma_digital'])->where(['id' => $orden->id_contacto])->first();
            }
            return response()->json(['success' => true, 'data' => $contacto]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
