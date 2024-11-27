<?php

namespace App\Http\Controllers\Orden;

use App\Http\Controllers\Controller;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrdenController extends Controller
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
            // Validación inicial
            $validator = Validator::make($request->all(), [
                'n_orden' => 'required|string',
                'codInc' => 'required|string',
                'check_cod' => 'required|boolean',
                'observacion' => 'required|string',
                'recomendacion' => 'required|string',
                'fecha_f' => 'required|date',
                'hora_f' => 'required|date_format:H:i:s',
                'materiales' => 'nullable|array',
                'firma_digital' => 'nullable|string',
                'n_doc' => 'nullable|integer',
                'nom_cliente' => 'nullable|string',
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            // Preparar datos de materiales
            $materiales = $request->materiales ?? [];
            foreach ($materiales as &$material) {
                $material['cod_ordens'] = $request->n_orden;
                $material['created_at'] = now()->format('Y-m-d H:i:s');
            }

            DB::beginTransaction();

            // Insertar correlativo si se seleccionó
            if ($request->check_cod) {
                DB::table('tb_orden_correlativo')->insert([
                    'num_orden' => $request->n_orden,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }

            // Crear contacto (si aplica)
            $idContacto = null;
            if ($request->n_doc || $request->nom_cliente) {
                $idContacto = $this->createContact($request);
            }

            // Insertar orden de servicio
            DB::table('tb_orden_servicio')->insert([
                'cod_ordens' => $request->n_orden,
                'cod_incidencia' => $request->codInc,
                'observaciones' => $request->observacion,
                'recomendaciones' => $request->recomendacion,
                'id_contacto' => $idContacto,
                'fecha_f' => $request->fecha_f,
                'hora_f' => $request->hora_f,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            // Insertar materiales (si hay)
            if (!empty($materiales)) {
                DB::table('tb_materiales_usados')->insert($materiales);
            }

            // Actualizar estado de incidencia
            DB::table('tb_incidencias')
                ->where('cod_incidencia', $request->codInc)
                ->update(['estado_informe' => 3]);

            // Insertar seguimiento de incidencia
            DB::table('tb_inc_seguimiento')->insert([
                'id_usuario' => Auth::id(),
                'cod_incidencia' => $request->codInc,
                'estado' => 1,
                'fecha' => $request->fecha_f,
                'hora' => $request->hora_f,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);

            // Obtener número de orden generado
            $codOrdenS = DB::select("CALL GetCodeOrds(24)")[0]->num_orden;

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Orden de servicio generada exitosamente.',
                'data' => ['num_orden' => $codOrdenS]
            ], 200);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al generar la orden de servicio: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un contacto basado en los datos del cliente.
     *
     * @param Request $request
     * @return int|null
     */
    private function createContact(Request $request)
    {
        $dataContact = [
            'nro_doc' => $request->n_doc,
            'nombre_cliente' => $request->nom_cliente,
            'created_at' => now()->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];

        // Guardar firma digital si existe
        if ($request->firma_digital) {
            $result = $this->parseCreateFile("fdc_{$request->n_doc}", 'client', $request->firma_digital);
            if (!$result['success']) {
                throw new Exception('Error al intentar crear la firma digital.');
            }
            $dataContact['firma_digital'] = $result['filename'];
        }

        // Insertar contacto y devolver su ID
        return DB::table('tb_contac_ordens')->insertGetId($dataContact);
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

    public function DataForFile(string $cod)
    {
        try {
            // Datos iniciales
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
                'NomFirma' => '',
            ];

            // Validación de orden
            $orden = DB::table('tb_orden_servicio')->where('cod_ordens', $cod)->first();
            if (!$orden) {
                return response()->json(['success' => false, 'message' => 'No se encontró el documento solicitado', 'data' => []]);
            }

            $incidencia = DB::table('tb_incidencias')->where('cod_incidencia', $orden->cod_incidencia)->first();
            $contactoEmpresa = DB::table('contactos_empresas')->where('id_contact', $incidencia->id_contacto)->first();
            $contactoOrden = DB::table('tb_contac_ordens')->where('id', $orden->id_contacto)->first();
            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $orden->cod_incidencia)->get();
            $seguimiento = DB::table('tb_inc_seguimiento')->where('cod_incidencia', $orden->cod_incidencia)->get();
            $materialesUsados = DB::table('tb_materiales_usados')->where('cod_ordens', $cod)->get();

            $datos['tipoSoporte'] = $incidencia->id_tipo_incidencia == 1 ? 'REMOTO' : 'PRESENCIAL';

            // Procesar contactos
            if ($contactoEmpresa) {
                $datos['contacto'] = "{$contactoEmpresa->nro_doc} {$contactoEmpresa->nombres}";
                $datos['telefono'] = $contactoEmpresa->telefono ?: '';
                $datos['correo'] = $contactoEmpresa->correo ?: '';
            }

            if ($contactoOrden) {
                $datos['contacOrden'] = "{$contactoOrden->nro_doc} - {$contactoOrden->nombre_cliente}";
                $datos['firmaC'] = $contactoOrden->firma_digital
                    ? public_path("/front/images/client/{$contactoOrden->firma_digital}")
                    : false;
            }

            // Procesar usuarios asignados
            $usuarios = DB::table('usuarios')
                ->where('estatus', 1)
                ->get()
                ->mapWithKeys(function ($usuario) {
                    return [
                        $usuario->id_usuario => [
                            'nombre' => "{$usuario->ndoc_usuario} - {$usuario->nombres} {$usuario->apellidos}",
                            'firma' => $usuario->firma_digital,
                        ]
                    ];
                });

            foreach ($asignados as $key => $asignado) {
                $usuario = $usuarios[$asignado->id_usuario] ?? null;
                if ($key == 0 && $usuario) {
                    $datos['firmaA'] = $usuario['firma']
                        ? public_path("/front/images/firms/{$usuario['firma']}")
                        : false;
                    $datos['NomFirma'] = $usuario['nombre'];
                }

                if ($usuario) {
                    $datos['asignados'][] = ['personal' => $usuario['nombre']];
                }
            }

            foreach ($seguimiento as $key => $val) {
                if ($val->estado) {
                    $datos['horaFin'] = $val->hora;
                }
                else {
                    $datos['horaIni'] = $val->hora;
                }
            }

            // Procesar datos relacionados a la empresa

            $empresa = DB::table('tb_empresas')->where('id', $incidencia->id_empresa)->first();
            $datos['empresa'] = "{$empresa->ruc} - {$empresa->razon_social}";

            // Procesar sucursales
            $sucursal = DB::table('tb_sucursales')->where('id', $incidencia->id_sucursal)->first();
            $datos['sucursal'] = [
                'sucursal' => $sucursal->nombre,
                'direccion' => $sucursal->direccion,
            ];

            // Procesar problemas
            $problemas = DB::table('tb_problema')
                ->select(['id_problema as id', DB::raw("CONCAT(codigo, ' - ', descripcion) AS text")])
                ->get();

            foreach ($problemas as $problema) {
                if ($problema->id == $incidencia->id_problema) {
                    $datos['problema'] = $problema->text;
                    break;
                }
            }

            // Observaciones y recomendaciones
            $datos['observacion'] = $orden->observaciones;
            $datos['recomendacion'] = $orden->recomendaciones;
            $datos['fecha'] = $incidencia->created_at;

            // Procesar materiales
            $materialesDisponibles = DB::table('tb_materiales')->where('estatus', 1)->pluck('producto', 'id_materiales')->toArray();

            // return $materialesUsados;

            foreach ($materialesUsados as $key => $material) {
                $datos['materiales'][] = [
                    'i' => $key + 1,
                    'p' => $materialesDisponibles[$material->id] ?? 'Desconocido',
                    'c' => $material->cantidad,
                ];
            }

            return $datos;

        } catch (Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }

    public function CreateTicket(string $cod)
    {
        $data = $this->DataForFile($cod);

        // Renderizar la vista HTML
        $pdf = PDF::loadView('orden.viewticket', $data);

        // Definir el tamaño de la hoja en mm (80mm de ancho)
        $pdf->setPaper([0, 0, 226.77, 800], 'portrait'); // 80mm ancho y 600 de alto (se puede ajustar)
        return $pdf->stream("ORDEN - {$cod}.pdf");
    }

    public function CreatePdf(string $cod)
    {
        try {
            // Datos iniciales
            $data = $this->DataForFile($cod);

            // Generar PDF
            $pdf = Pdf::loadView('orden.viewpdf', $data);
            return $pdf->stream("ORDEN - {$cod}.pdf");

        } catch (Exception $e) {
            Log::error('Error al generar el PDF: ' . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }
}
