<?php

namespace App\Http\Controllers\Soporte\Orden;

use App\Helpers\Problema;
use App\Helpers\SubProblema;
use App\Helpers\TipoSoporte;
use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use App\Services\SqlStateHelper;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Throw_;
use Throwable;

class OrdenController extends Controller
{
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
                'cod_sistema' => 'required|boolean',
                'observaciones' => 'required|string',
                'recomendacion' => 'required|string',
                'fecha_f' => 'required|date',
                'hora_f' => 'required|date_format:H:i:s',
                'materiales' => 'nullable|array',
                'id_firmador' => 'nullable|string',
                'nomFirmaDigital' => 'nullable|string',
                'firma_digital' => 'nullable|string',
                'n_doc' => 'nullable|integer',
                'nom_cliente' => 'nullable|string',
            ]);

            if ($validator->fails())
                return $this->message(data: ['required' => $validator->errors()], status: 422);

            $codAviso = $request->has('codAviso') ? $request->codAviso : 3;
            $new_codigo = $request->n_orden;
            $validar_codigo = "";

            DB::beginTransaction();

            if ($request->cod_sistema) {
                $new_codigo = DB::select('CALL GetCodeOrds(?)', [date('y')])[0]->num_orden;
                $orden = DB::table('tb_orden_servicio')->select('cod_ordens')->where('cod_incidencia', $request->codInc)->first();
                if ($orden) {
                    return $this->message(message: "El orden de servicio para la incidencia <b>$request->codInc</b> ya fue emitida.", data: ['data' => ['new_cod_orden' => $new_codigo]], status: 202);
                }

                if ($new_codigo != $request->n_orden) {
                    $validar_codigo = "<b class='text-danger'>Importante:</b> El código de orden <b>$request->n_orden</b> ya estaba en uso. Se asignó el nuevo código <b>$new_codigo</b>";
                } else {
                    $new_codigo = $request->n_orden;
                }

                // Insertar correlativo si se seleccionó
                DB::table('tb_orden_correlativo')->insert([
                    'num_orden' => $new_codigo,
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
                'cod_ordens' => $new_codigo,
                'cod_incidencia' => $request->codInc,
                'observaciones' => $request->observaciones,
                'recomendaciones' => $request->recomendacion,
                'id_contacto' => $idContacto,
                'codigo_aviso' => $codAviso,
                'fecha_f' => $request->fecha_f,
                'hora_f' => $request->hora_f,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);


            // Preparar datos de materiales
            $materiales = $request->materiales;
            // Insertar materiales (si hay)
            if (!empty($materiales)) {
                $arr_materiales = [];
                foreach ($materiales as $k => $val) {
                    if ($val['registro']) {
                        $arr_materiales[$k]['cod_ordens'] = $new_codigo;
                        $arr_materiales[$k]['id_material'] = $val['id_material'];
                        $arr_materiales[$k]['cantidad'] = $val['cantidad'];
                        $arr_materiales[$k]['created_at'] = now()->format('Y-m-d H:i:s');
                    }
                }
                DB::table('tb_materiales_usados')->insert($arr_materiales);
            }

            // Actualizar estado de incidencia
            DB::table('tb_incidencias')
                ->where('cod_incidencia', $request->codInc)
                ->update(['estado_informe' => ($codAviso == "" && !empty($materiales) ? 4 : 3)]);

            // Insertar seguimiento de incidencia
            DB::table('tb_inc_seguimiento')->insert([
                'id_usuario' => Auth::id(),
                'cod_incidencia' => $request->codInc,
                'estado' => 1,
                'fecha' => $request->fecha_f,
                'hora' => $request->hora_f,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            $codOrdenS = DB::select('CALL GetCodeOrds(?)', [date('y')])[0]->num_orden;
            DB::commit();

            return $this->message(message: "<p>Orden de servicio generada exitosamente.</p><p style='font-size: small;'>$validar_codigo</p>", data: [
                'data' => [
                    'new_cod_orden' => $codOrdenS,
                    'old_cod_orden' => $new_codigo,
                ]
            ]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo registrar la order de servicio." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function addSignature(Request $request)
    {
        try {

            DB::beginTransaction();
            $idContacto = $this->createContact($request);

            // Actualizar estado de incidencia
            DB::table('tb_orden_servicio')
                ->where('cod_ordens', $request->cod_orden)
                ->update(['id_contacto' => $idContacto]);
            DB::commit();

            return $this->message(message: "La firma se añadió con exito.", data: ['data' => $request->all()]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo añadir la firma." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Crear un contacto basado en los datos del cliente.
     *
     * @param Request $request
     * @return int|null|\Illuminate\Http\JsonResponse
     */
    private function createContact(Request $request)
    {
        try {
            $id = null;
            DB::beginTransaction();
            // Si existe una firma digital con nombre, la asignamos directamente
            if (!empty($request->nomFirmaDigital) && empty($request->firma_digital)) {
                $firma_digital = $request->nomFirmaDigital;
            } else {
                // Si hay una firma digital en archivo, intentamos guardarla
                $result = $this->parseCreateFile("fdc_{$request->n_doc}", 'client', $request->firma_digital);
                if (!$result['success']) {
                    throw new Exception('No se pudo procesar la firma digital.');
                }
                $firma_digital = $result['filename'];
            }
            // Si ya existe un firmador, actualizamos el registro, sino creamos uno nuevo
            if (!empty($request->id_firmador)) {
                $dataContact = [
                    'firma_digital' => $firma_digital,
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ];
                if (!empty($request->n_doc)) {
                    $dataContact['nro_doc'] = $request->n_doc;
                }
                if (!empty($request->nom_cliente)) {
                    $dataContact['nombre_cliente'] = $request->nom_cliente;
                }
                DB::table('tb_contac_ordens')->where('id', $request->id_firmador)->update($dataContact);
                $id = $request->id_firmador;
            } else {
                $id = DB::table('tb_contac_ordens')->insertGetId([
                    'nro_doc' => $request->n_doc,
                    'nombre_cliente' => $request->nom_cliente,
                    'firma_digital' => $firma_digital,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }
            DB::commit();
            return $id;
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }


    /**
     * Crear un contacto basado en los datos del cliente.
     *
     * @param Request $request
     */
    public function editCodAviso(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_incidencia' => 'required|string',
                'cod_orden_ser' => 'required|string',
                'codigo_aviso' => 'required|string',
            ]);

            if ($validator->fails())
                return $this->message(data: ['required' => $validator->errors()], status: 422);

            DB::beginTransaction();
            DB::table('tb_orden_servicio')->where('cod_incidencia', $request->cod_incidencia)->update([
                'codigo_aviso' => $request->codigo_aviso,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::table('tb_incidencias')->where('cod_incidencia', $request->cod_incidencia)->update(['estado_informe' => 3]);
            DB::commit();

            return $this->message(message: 'Codigo añadido con éxito', data: ['data' => ['cod_orden' => $request->cod_orden_ser]]);
        } catch (QueryException $e) {
            DB::rollBack();
            $sqlHelper = SqlStateHelper::getUserFriendlyMsg($e->getCode());
            $message = $sqlHelper->codigo == 500 ? "No se puedo añadir el codigo." : $sqlHelper->message;

            return $this->message(message: $message, data: ['error' => $e], status: 500);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
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
                throw new Exception("No se encontró el documento solicitado", 404);
            }

            $incidencia = DB::table('tb_incidencias')->where('cod_incidencia', $orden->cod_incidencia)->first();
            $contactoOrden = DB::table('tb_contac_ordens')->where('id', $orden->id_contacto)->first();
            $asignados = DB::table('tb_inc_asignadas')->where('cod_incidencia', $orden->cod_incidencia)->orderBy('created_at', 'asc')->get();
            $seguimiento = DB::table('tb_inc_seguimiento')->where('cod_incidencia', $orden->cod_incidencia)->get();
            $materialesUsados = DB::table('tb_materiales_usados')->select('id_material', 'cantidad')->where('cod_ordens', $cod)->get();

            $tSoporte = JsonDB::table('tipo_soporte')->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->where('id', $incidencia->id_tipo_soporte)->first();
            $datos['tipoSoporte'] = $tSoporte->descripcion;

            // Procesar contactos
            $contactoEmpresa = DB::table('contactos_empresas')->where('id_contact', $incidencia->id_contacto)->first();
            if ($contactoEmpresa) {
                $datos['contacto'] = "{$contactoEmpresa->nro_doc} {$contactoEmpresa->nombres}";
                $datos['correo'] = $contactoEmpresa->correo ?: '';
                if ($incidencia->id_telefono) {
                    $contactoTelefono = DB::table('contactos_telefono_empresas')->where('id', $incidencia->id_telefono)->first();
                    $datos['telefono'] = $contactoTelefono->telefono ?: '';
                }
            }

            if ($contactoOrden) {
                $datos['contacOrden'] = "{$contactoOrden->nro_doc} - {$contactoOrden->nombre_cliente}";
                $datos['firmaC'] = $contactoOrden->firma_digital
                    ? public_path("/front/images/client/{$contactoOrden->firma_digital}")
                    : false;
            }

            // Procesar usuarios asignados
            $personal = DB::table('tb_personal')
                ->get()
                ->mapWithKeys(function ($usuario) {
                    $nombre = $this->formatearNombre($usuario->nombres, $usuario->apellidos);
                    return [
                        $usuario->id_usuario => [
                            'nombre' => "{$usuario->ndoc_usuario} - {$nombre}",
                            'firma' => $usuario->firma_digital,
                        ]
                    ];
                });

            foreach ($asignados as $key => $asignado) {
                $usuario = $personal[$asignado->id_usuario] ?? null;
                if ($key == 0 && $usuario) {
                    $datos['firmaA'] = $usuario['firma']
                        ? public_path("/front/images/firms/{$usuario['firma']}")
                        : false;
                    $datos['NomFirma'] = $usuario['nombre'];
                }

                if ($usuario) {
                    $datos['asignados'][] = $usuario['nombre'];
                }
            }

            foreach ($seguimiento as $key => $val) {
                if ($val->estado) {
                    $datos['horaFin'] = $val->hora;
                } else {
                    $datos['horaIni'] = $val->hora;
                }
            }

            // Procesar datos relacionados a la empresa

            $empresa = DB::table('tb_empresas')->where('ruc', $incidencia->ruc_empresa)->first();
            $datos['empresa'] = "{$empresa->ruc} - {$empresa->razon_social}";
            $datos['eCodAviso'] = $empresa->codigo_aviso;

            // Procesar sucursales
            $sucursal = DB::table('tb_sucursales')->where('id', $incidencia->id_sucursal)->first();
            $datos['sucursal'] = [
                'sucursal' => $sucursal->nombre,
                'direccion' => $sucursal->direccion,
            ];

            // Procesar problemas
            $problemas = JsonDB::table('problema')->select('id', 'codigo', 'descripcion', 'tipo_soporte', 'estatus')->where('id', $incidencia->id_problema)->first();
            $sproblemas = JsonDB::table('sub_problema')->select('id', 'codigo_problema', 'descripcion', 'prioridad', 'estatus')->where('id', $incidencia->id_subproblema)->first();
            $datos['problema'] = "{$problemas->codigo} - {$problemas->descripcion}";
            $datos['sproblema'] = "{$sproblemas->prioridad} - {$sproblemas->descripcion}";

            // Observaciones y recomendaciones
            $datos['observacion'] = $orden->observaciones;
            $datos['recomendacion'] = $orden->recomendaciones;
            $datos['codigo_aviso'] = $orden->codigo_aviso;
            $datos['fecha'] = $incidencia->created_at;

            // Procesar materiales
            $datos['materiales'] = DB::table('tb_materiales')->whereIn('id_materiales', $materialesUsados->pluck('id_material')->toArray())->get()
                ->map(function ($val, $key) use ($materialesUsados) {
                    $material = $materialesUsados->where('id_material', $val->id_materiales)->first();
                    return [
                        'indice' => $key + 1,
                        'descripcion' => $val->producto,
                        'cantidad' => $material->cantidad ?? '0'
                    ];
                });

            return $datos;

        } catch (Throwable $th) {
            throw new Exception($th->getCode() . ': ' . $th->getMessage(), 500);
        }
    }

    public function edata(string $cod)
    {
        return $this->DataForFile($cod);
    }

    public function ExportarDocumento(Request $request)
    {
        try {
            $documento = $request->query('documento');
            $codigo = $request->query('codigo');
            $tipo = $request->query('tipo');

            if (!$documento) {
                throw new Exception("El parametro documento no puede estar vacio.");
            } else if ($documento != 'pdf' && $documento != 'ticket') {
                throw new Exception("El parametro documento tiene un valor incorrecto.");
            }
            if (!$codigo) {
                throw new Exception("El parametro codigo no puede estar vacio.");
            }

            // Datos iniciales
            $data = $this->DataForFile($codigo);
            // Generar PDF
            $pdf = Pdf::loadView("soporte.orden.incidencia.view$documento", $data);
            if ($documento == 'ticket') {
                // Definir el tamaño de la hoja en mm (80mm de ancho)
                $pdf->setPaper([0, 0, 226.77, 800], 'portrait'); // 80mm ancho y 600 de alto (se puede ajustar)
            }
            switch ($tipo) {
                case 'descarga':
                    return $pdf->download("ORDEN - {$codigo}.pdf");

                case 'movil':
                    $urlDescarga = "soporte/orden/exportar-documento";
                    $pdfContent = $pdf->output(); // string binario del PDF
                    return view('soporte.orden.pdfjs.preview', [
                        'urlDescarga' => $urlDescarga,
                        'documento' => $documento,
                        'codigo' => $codigo,
                        'base64_pdf' => base64_encode($pdfContent)
                    ]);

                default:
                    return $pdf->stream("ORDEN - {$codigo}.pdf");
            }
        } catch (QueryException $e) {
            return $this->message(message: "Error al generar el PDF de la orden de incidencia $codigo", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                return $this->message(message: $e->getMessage(), status: 404);
            }
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
