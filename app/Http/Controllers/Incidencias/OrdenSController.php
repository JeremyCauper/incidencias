<?php

namespace App\Http\Controllers\Incidencias;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrdenSController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'n_orden' => 'required|string',
                'check_cod' => 'required|boolean',
                'obs' => 'required|string',
                'rec' => 'required|string',
                'fecha_f' => 'required|date',
                'hora_f' => 'required|date_format:H:i:s',
                'materiales' => 'nullable|array',
                'firma_digital' => 'nullable|string',
                'n_doc' => 'nullable|integer',
                'nom_cliente' => 'nullable|string',
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            $materiales = $request->materiales;
            foreach ($materiales as $k => $val) {
                $materiales[$k]['cod_ordens'] = $request->n_orden;
                $materiales[$k]['updated_at'] = now();
                $materiales[$k]['created_at'] = now();
            }
            DB::beginTransaction();
            if ($request->check_cod) {
                DB::table('tb_orden_correlativo')->insert(['num_orden' => $request->n_orden, 'updated_at' => now()]);
            }
            $idContacto = null;
            if ($request->n_doc || $request->nom_cliente) {
                $dataContact = [
                    'nro_doc' => $request->n_doc,
                    'nombre_cliente' => $request->nom_cliente,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                if ($request->firma_digital) {
                    $result = GlobalHelper::parseCreateFile("fdc_{$request->n_doc}", 'client', $request->firma_digital);
                    if (!$result['success']) {
                        return response()->json(['success' => false, 'message' => 'Error al intentar crear la imagen del perfil'], 500);
                    }
                    $dataContact['firma_digital'] = $result['filename'];
                }
                $idContacto = DB::table('tb_contac_ordens')->insertGetId($dataContact);
            }

            DB::table('tb_orden_servicio')->insert([
                'cod_ordens' => $request->n_orden,
                'cod_incidencia' => $request->codInc,
                'observaciones' => $request->obs,
                'recomendaciones' => $request->rec,
                'id_contacto' => $idContacto,
                'fecha_f' => $request->fecha_f,
                'hora_f' => $request->hora_f,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (count($materiales))
                DB::table('tb_materiales_usados')->insert($materiales);

            DB::table('tb_incidencias')->where('cod_incidencia', $request->codInc)->update(['estado_informe' => 3]);

            DB::table('tb_inc_seguimiento')->insert([
                'id_usuario' => Auth::user()->id_usuario,
                'cod_incidencia' => $request->codInc,
                'estado' => 1,
                'updated_at' => now(),
                'created_at' => now()
            ]);

            $cod_ordenS = DB::select("CALL GetCodeOrds(24)")[0]->num_orden;
            DB::commit();
            GlobalHelper::getIncDataTable(true);

            return response()->json([
                'success' => true,
                'message' => 'Orden de servicio generado',
                'data' => ['num_orden' => $cod_ordenS]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al generar el orden de servio: ' . $e->getMessage()
            ], 500);
        }
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
        //
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

    public function generarPDF(string $cod)
    {
        try {
            $datos = [
                'titulo' => "{$cod}.pdf",
                'cod_ordens' => $cod,
                'asignados' => [],
                'materiales' => [],
                'contacto' => '',
                'telefono' => '',
                'correo' => '',
                'cantidad' => '',
                'contacOrden' => '',
                'firmaC' => false,
                'NomFirma' => ''
            ];

            $usuarios = [];
            $users = GlobalHelper::getUsuarios();
            foreach ($users as $val) {
                $usuarios[$val->id_usuario] = ['nombre' => "{$val->ndoc_usuario} - {$val->nombres} {$val->apellidos}", 'firma' => $val->firma_digital];
            }

            $materiales = [];
            $material = GlobalHelper::getMateriales();
            foreach ($material as $val) {
                $materiales[$val->id] = $val->producto;
            }

            $orden = DB::table('tb_orden_servicio')->where('cod_ordens', $cod)->first();
            if (!$orden) {
                return response()->json(['success' => false, 'message' => 'No se encontró el documento que solicitaste', 'data' => []]);
            }
            $inc = DB::table('tb_incidencias')->where('cod_incidencia', $orden->cod_incidencia)->first();
            $contactos = DB::table('contactos_empresas')->where('id_contact', $inc->id_contacto)->first();
            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $orden->cod_incidencia)->get();
            $contacOrden = DB::table('tb_contac_ordens')->where('id', $orden->id_contacto)->first();

            if ($contactos) {
                $datos['contacto'] = "{$contactos->nro_doc} {$contactos->nombres}";
                $datos['telefono'] = $contactos->telefono ?: '';
                $datos['correo'] = $contactos->correo ?: '';
            }

            if ($contacOrden) {
                $datos['contacOrden'] = "{$contacOrden->nro_doc} - {$contacOrden->nombre_cliente}";
                $datos['firmaC'] = $contacOrden->firma_digital ? public_path() . "/front/images/client/{$contacOrden->firma_digital}" : false;
            }
            
            foreach ($asignados as $key => $val) {
                if ($key == 0) {
                    $datos['firmaA'] = $usuarios[$val->id_usuario]['firma'] ? public_path() . "/front/images/firms/{$usuarios[$val->id_usuario]['firma']}" : false;
                    $datos['NomFirma'] = $usuarios[$val->id_usuario]['nombre'];
                }
                $datos['asignados'][] = ['personal' => $usuarios[$val->id_usuario]['nombre']];
            }

            $empresas = GlobalHelper::getCompany();
            foreach ($empresas as $val) {
                if ($val->id == $inc->id_empresa) {
                    $datos['empresa'] = "{$val->Ruc} - {$val->RazonSocial}";
                    break;
                }
            }

            $sucursales = GlobalHelper::getBranchOffice();
            foreach ($sucursales as $val) {
                if ($val->id == $inc->id_sucursal) {
                    $datos['sucursal'] = ['sucursal' => $val->Nombre, 'direccion' => $val->Direccion];
                    break;
                }
            }

            $problem = GlobalHelper::getProblema();
            foreach ($problem as $val) {
                if ($val->id == $inc->id_problema) {
                    $datos['problema'] = $val->text;
                    break;
                }
            }

            $datos['observacion'] = $orden->observaciones;
            $datos['recomendacion'] = $orden->recomendaciones;
            $datos['fecha'] = $inc->created_at;

            $materialesu = DB::table('tb_materiales_usados')->where('cod_ordens', $cod)->get();
            foreach ($materialesu as $key => $val) {
                $datos['materiales'][] = ['i' => $key + 1, 'p' => $materiales[$val->id], 'c' => $val->cantidad];
            }

            $pdf = Pdf::loadView('pdf.orden_servicio', $datos);
            return $pdf->stream("ORDEN - {$cod}.pdf");
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrio un error inesperado: ' . $e->getMessage()], 500);
        }
    }
}
