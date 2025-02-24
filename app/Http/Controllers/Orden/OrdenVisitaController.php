<?php

namespace App\Http\Controllers\Orden;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
            $codOrdenV = DB::select("CALL GetCodeOrdVis(25)")[0]->cod_orden;
    
            DB::commit();
    
            return $this->message(
                message: 'Orden de visita generada exitosamente.',
                data: [ 'data' => ['cod_ordenv' => $codOrdenV] ]
            );
    
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
                'cod_ordens' => $cod,
                'asignados' => []
            ];

            // Validación de orden
            $orden = DB::table('tb_orden_visita')->where('cod_orden_visita', $cod)->first();
            if (!$orden) {
                return $this->message(message: 'EL orden de vista que buscas no existe', status: 404);
            }

            $visita = DB::table('tb_visitas')->where('id', $orden->id_visita)->first();
            $asignados = DB::table('tb_vis_asignadas')->where('id_visitas', $orden->id_visita)->orderBy('created_at', 'asc')->get();
            $seguimiento = DB::table('tb_vis_seguimiento')->where('id_visitas', $orden->id_visita)->get();
            $datos['ordenv_filas'] = DB::table('tb_orden_visita_filas')->where('cod_orden_visita', $cod)->orderBy('posicion', 'asc')->get();
            $datos['ordenv_islas'] = DB::table('tb_orden_visita_islas')->where('cod_orden_visita', $cod)->get();

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

            $sucursal = DB::table('tb_sucursales')->where('id', $visita->id_sucursal)->first();
            $datos['sucursal'] = [
                'sucursal' => $sucursal->nombre,
                'direccion' => $sucursal->direccion,
            ];

            // Procesar usuarios asignados
            $usuarios = DB::table('usuarios')
                ->where('estatus', 1)
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
                $usuario = $usuarios[$asignado->id_usuario] ?? null;
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

            return $datos;
        } catch (QueryException $e) {
            return $this->message(message: "Error en la base de datos. Inténtelo más tarde.", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    public function CreatePdf(string $cod)
    {
        try {
            // Datos iniciales
            $data = $this->DataForFile($cod);

            // Generar PDF
            // $pdf = Pdf::loadView('orden.viewpdf', $data);
            // return $pdf->stream("ORDEN - {$cod}.pdf");
            return $data;
        } catch (QueryException $e) {
            return $this->message(message: "Error al generar el PDF de la orden de visita $cod", data: ['error' => $e->getMessage()], status: 400);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
