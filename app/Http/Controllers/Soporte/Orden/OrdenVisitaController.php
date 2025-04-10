<?php

namespace App\Http\Controllers\Soporte\Orden;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrdenVisitaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            // Validación inicial
            $validator = Validator::make($request->all(), [
                'cod_ordenv' => 'required|string',
                'id_visita_orden' => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return $this->message(data: ['required' => $validator->errors()], status: 422);
            }

            $new_codigo = DB::select('CALL GetCodeOrdVis(?)', [date('y')])[0]->cod_orden;
            $orden = DB::table('tb_orden_visita')->select('cod_orden_visita')->where('id_visita', $request->id_visita_orden)->first();
            if ($orden) {
                return $this->message(message: "El orden de visita para la visita en proceso, ya fue emitida.", data: ['data' => ['new_cod_ordenv' => $new_codigo]], status: 202);
            }

            $validar_codigo = "";
            if ($new_codigo != $request->cod_ordenv) {
                $validar_codigo = "<b class='text-danger'>Importante:</b> El código de orden <b>$request->cod_ordenv</b> ya estaba en uso. Se asignó el nuevo código <b>$new_codigo</b>";
            } else {
                $new_codigo = $request->cod_ordenv;
            }
    
            // Filtrar los datos de las filas de visitas excluyendo las claves específicas
            $request_filas_visitas = array_diff_key($request->all(), array_flip(['cod_ordenv', 'id_visita_orden', 'islas']));
    
            // Construcción del array para insertar en tb_orden_visita_filas
            $indice = 0;
            $filas_visitas = collect($request_filas_visitas)->map(function ($item) use ($request, &$indice) {
                $indice++;
                return [
                    'cod_orden_visita' => $request->cod_ordenv,
                    'posicion' => $indice,
                    'checked' => empty($item) ? false : true,
                    'descripcion' => $item,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];
            })->values()->toArray();
    
            // Construcción del array para insertar en tb_orden_visita_islas
            $islas_visitas = collect($request->islas)->map(function ($item) use ($request) {
                $item = (object)$item;
                return [
                    'cod_orden_visita' => $request->cod_ordenv,
                    'isla' => $item->isla,
                    'pos' => $item->pos,
                    'impresoras' => empty($item->impresoras) ? false : true,
                    'des_impresoras' => $item->impresoras,
                    'lectores' => empty($item->red_lectores) ? false : true,
                    'des_lector' => $item->red_lectores,
                    'jack' => empty($item->jack_tools) ? false : true,
                    'des_jack' => $item->jack_tools,
                    'voltaje' => empty($item->voltaje) ? false : true,
                    'des_voltaje' => $item->voltaje,
                    'caucho' => empty($item->caucho_protector) ? false : true,
                    'des_caucho' => $item->caucho_protector,
                    'mueblepos' => empty($item->mueble_pos) ? false : true,
                    'des_mueblepos' => $item->mueble_pos,
                    'mr350' => empty($item->terminales) ? false : true,
                    'des_mr350' => $item->terminales,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ];
            })->values()->toArray();
    
            DB::beginTransaction();
    
            // Insertar en la tabla tb_orden_visita_correlativo
            DB::table('tb_orden_visita_correlativo')->insert([
                'cod_orden_visita' => $request->cod_ordenv,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
    
            // Insertar en la tabla tb_orden_visita
            DB::table('tb_orden_visita')->insert([
                'cod_orden_visita' => $request->cod_ordenv,
                'id_visita' => $request->id_visita_orden,
                'fecha_visita' => now()->format('Y-m-d'),
                'hora_inicio' => now()->format('H:i:s'),
                'hora_fin' => now()->format('H:i:s'),
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
    
            // Insertar en la tabla tb_orden_visita_filas
            if (!empty($filas_visitas)) {
                DB::table('tb_orden_visita_filas')->insert($filas_visitas);
            }
    
            // Insertar en la tabla tb_orden_visita_islas
            if (!empty($islas_visitas)) {
                DB::table('tb_orden_visita_islas')->insert($islas_visitas);
            }
    
            // Actualizar el estado de la visita en tb_visitas
            DB::table('tb_visitas')->where('id', $request->id_visita_orden)->update(['estado' => 2]);

            DB::table('tb_vis_seguimiento')->insert([
                'id_visitas' => $request->id_visita_orden,
                'id_usuario' => Auth::user()->id_usuario,
                'fecha' => now()->format('Y-m-d'),
                'hora' => now()->format('H:i:s'),
                'estado' => 1,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
    
            // Obtener código de orden de visita
            $codOrdenV = DB::select('CALL GetCodeOrdVis(?)', [date('y')])[0]->cod_orden;
    
            DB::commit();
    
            return $this->message(
                message: "<p>Orden de visita generada exitosamente.</p><p style='font-size: small;'>$validar_codigo</p>",
                data: [ 'data' => [
                    'new_cod_ordenv' => $codOrdenV,
                    'old_cod_ordenv' => $new_codigo,
                ]]);
    
        } catch (QueryException $e) {
            DB::rollBack();
            return $this->message(
                message: "Error en la base de datos. Inténtelo más tarde.",
                data: ['error' => $e->getMessage()],
                status: 400
            );
    
        } catch (Exception $e) {
            DB::rollBack();
            return $this->message(
                data: ['error' => $e->getMessage()],
                status: 500
            );
        }
    }

    public function DataForFile(string $cod)
    {
        try {
            // Datos iniciales
            $datos = [
                'titulo' => "{$cod}.pdf",
                'cod_ordenv' => $cod,
                'asignados' => [],
                'fecha_visita' => '',
            ];

            $config_filas = [
                1 => (object)[ "text" => "UPS", "child" => false],
                2 => (object)[ "text" => "BATERIAS UPS", "child" => true],
                3 => (object)[ "text" => "SALIDA DE ENERGIA", "child" => true],
                4 => (object)[ "text" => "ESTABILIZADOR", "child" => false],
                5 => (object)[ "text" => "INGRESO DE ENERGIA", "child" => true],
                6 => (object)[ "text" => "SALIDA DE ENERGIA", "child" => true],
                7 => (object)[ "text" => "INTERFACE", "child" => false],
                8 => (object)[ "text" => "MONITOR", "child" => false],
                9 => (object)[ "text" => "TARJETA MULTIPUERTOS", "child" => false],
                10 => (object)[ "text" => "SWITCH", "child" => false],
                11 => (object)[ "text" => "SISTEMA OPERATIVO", "child" => false],
                12 => (object)[ "text" => "VENCIMIENTO DE ANTIVIRUS", "child" => false],
                13 => (object)[ "text" => "DISCO DURO", "child" => false],
                14 => (object)[ "text" => "REALIZAR BACKUP", "child" => false],
            ];

            $config_islas = [
                (object)[ "text" => "IMPRESORAS", "checked" => "impresoras", "descripcion" => "des_impresoras", "child" => false],
                (object)[ "text" => "RED DE LECTORES", "checked" => "lectores", "descripcion" => "des_lector", "child" => false],
                (object)[ "text" => "JACK TOOLS", "checked" => "jack", "descripcion" => "des_jack", "child" => false],
                (object)[ "text" => "VOLTAJE DE MANGUERAS", "checked" => "voltaje", "descripcion" => "des_voltaje", "child" => true],
                (object)[ "text" => "CAUCHO PROTECTOR DE", "checked" => "caucho", "descripcion" => "des_caucho", "child" => false],
                (object)[ "text" => "LECTORES", "checked" => "mueblepos", "descripcion" => "des_mueblepos", "child" => false],
                (object)[ "text" => "MUEBLE DE POS", "checked" => "mr350", "descripcion" => "des_mr350", "child" => false],
                (object)[ "text" => "MR 350 / DTI / TERMINAL", "checked" => "switch", "descripcion" => "des_switch", "child" => false],
            ];

            // Validación de orden
            $orden = DB::table('tb_orden_visita')->where('cod_orden_visita', $cod)->first();
            if (!$orden) {
                throw new Exception("EL orden de vista que buscas no existe", 404);
            }
            $datos['fecha_visita'] = $orden->created_at;

            $visita = DB::table('tb_visitas')->where('id', $orden->id_visita)->first();
            $asignados = DB::table('tb_vis_asignadas')->where('id_visitas', $orden->id_visita)->orderBy('created_at', 'asc')->get();
            $seguimiento = DB::table('tb_vis_seguimiento')->where('id_visitas', $orden->id_visita)->get();
            $datos['ordenv_filas'] = DB::table('tb_orden_visita_filas')->select('posicion', 'checked', 'descripcion')->where('cod_orden_visita', $cod)->orderBy('posicion', 'asc')->get()->map(function ($fila) use($config_filas) {
                $fila->config = $config_filas[$fila->posicion];
                return $fila;
            });

            $datos['ordenv_islas'] = DB::table('tb_orden_visita_islas')->where('cod_orden_visita', $cod)->get();
            $datos['config_islas'] = $config_islas;

            $sucursal = DB::table('tb_sucursales')->where('id', $visita->id_sucursal)->first();
            $datos['sucursal'] = [
                'sucursal' => $sucursal->nombre,
                'direccion' => $sucursal->direccion,
            ];

            // Procesar usuarios asignados
            $personal = DB::table('tb_personal')
                ->get()
                ->mapWithKeys(function ($usuario) {
                    $nombre = $this->formatearNombre($usuario->nombres, $usuario->apellidos);
                    return [
                        $usuario->id_usuario => [
                            'nombre' => "{$usuario->ndoc_usuario} - {$nombre}"
                        ]
                    ];
                });

            foreach ($asignados as $key => $asignado) {
                $usuario = $personal[$asignado->id_usuario] ?? null;
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
            $empresa = DB::table('tb_empresas')->where('ruc', $sucursal->ruc)->first();
            $datos['empresa'] = "{$empresa->ruc} - {$empresa->razon_social}";
            $datos['eCodAviso'] = $empresa->codigo_aviso;
            $datos['contacto'] = $empresa->encargado;
            $datos['telefono'] = $empresa->telefono;
            $datos['correo'] = $empresa->correo;

            return $datos;
        } catch (Throwable $th) {
            throw new Exception($th->getMessage(), $th->getCode());
        }
    }

    public function CreatePdf(string $cod)
    {
        try {
            // Datos iniciales
            $data = $this->DataForFile($cod);

            // Generar PDF
            $pdf = Pdf::loadView('soporte.orden.visita.viewpdf', $data);
            return $pdf->stream("ORDEN VISITA - {$cod}.pdf");
        } catch (QueryException $e) {
            return $this->message(message: "Error al generar el PDF de la orden de visita $cod", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                return $this->message(message: $e->getMessage(), status: $e->getCode());
            }
            return $this->message(data: ['error' => $e->getMessage()], status: $e->getCode());
        }
    }
}
