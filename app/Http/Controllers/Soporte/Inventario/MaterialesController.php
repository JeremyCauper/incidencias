<?php

namespace App\Http\Controllers\Soporte\Inventario;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class MaterialesController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            $data['usuarios'] = db::table('tb_personal')->where(['id_area' => 1, 'eliminado' => 0, 'estatus' => 1])->get()->keyBy('id_usuario')->map(function ($u) {
                $nombre = $this->formatearNombre($u->nombres, $u->apellidos);
                return [
                    'value' => $u->id_usuario,
                    'text' => "{$u->ndoc_usuario} - {$nombre}"
                ];
            });

            $data['materiales'] = DB::table('tb_materiales')->where('eliminado', 0)->get()->map(function ($val) {
                return [
                    'id' => $val->id_materiales,
                    'producto' => $val->producto,
                    'cantidad' => $val->cantidad
                ];
            });

            return view('soporte.inventario.material', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message2(e: $e, status: 500);
        }
    }
    /**
     * Listar materiales del almacén central
     */
    public function index(): JsonResponse
    {
        try {
            $materiales = DB::table('tb_materiales')
                ->where('eliminado', 0)
                ->get()->map(function ($val) {
                    $val->estado = $this->formatEstado($val->estatus);
                    $val->acciones = $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            ['funcion' => "Editar({$val->id_materiales})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                            ['funcion' => "CambiarEstado({$val->id_materiales}, {$val->estatus})", 'texto' => $this->formatEstado($val->estatus, 'change')],
                            ['funcion' => "Eliminar({$val->id_materiales})", 'texto' => '<i class="far fa-trash-can me-2 text-danger"></i>Eliminar'],
                        ],
                    ]);

                    return $val;
                });

            return $this->message2(data: $materiales);
        } catch (Exception $e) {
            return $this->message2(e: $e, status: 500);
        }
    }

    /**
     * Agregar nuevo material
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:20',
            'producto' => 'required|string|max:200',
            'cantidad' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::table('tb_materiales')->insert([
                'codigo' => $request->codigo,
                'producto' => $request->producto,
                'cantidad' => $request->cantidad,
                'created_at' => now(),
                'updated_at' => now(),
                'estatus' => 1,
                'eliminado' => 0
            ]);

            return response()->json(['success' => true, 'message' => 'Material registrado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al registrar material: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al registrar material.'], 500);
        }
    }

    /**
     * Actualizar un material existente
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'codigo' => 'nullable|string|max:20',
            'producto' => 'required|string|max:200',
            'cantidad' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $material = DB::table('tb_materiales')->where('id_materiales', $id)->first();

            if (!$material) {
                return response()->json(['success' => false, 'message' => 'Material no encontrado.'], 404);
            }

            DB::table('tb_materiales')
                ->where('id_materiales', $id)
                ->update([
                    'codigo' => $request->codigo,
                    'producto' => $request->producto,
                    'cantidad' => $request->cantidad,
                    'updated_at' => now()
                ]);

            return response()->json(['success' => true, 'message' => 'Material actualizado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al actualizar material: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al actualizar material.'], 500);
        }
    }

    /**
     * Eliminar lógicamente un material
     */
    public function destroy($id): JsonResponse
    {
        try {
            $material = DB::table('tb_materiales')->where('id_materiales', $id)->first();

            if (!$material) {
                return response()->json(['success' => false, 'message' => 'Material no encontrado.'], 404);
            }

            DB::table('tb_materiales')
                ->where('id_materiales', $id)
                ->update(['eliminado' => 1, 'updated_at' => now()]);

            return response()->json(['success' => true, 'message' => 'Material eliminado correctamente.']);
        } catch (\Exception $e) {
            Log::error('Error al eliminar material: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al eliminar material.'], 500);
        }
    }

    /**
     * Asignar material a un técnico (crear o sumar en inventario técnico)
     */
    public function asignarATecnico(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_material' => 'required|integer|exists:tb_materiales,id_materiales',
            'id_usuario' => 'required|integer|exists:tb_personal,id_usuario',
            'cantidad' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            // Verificar stock disponible en el almacén central
            $material = DB::table('tb_materiales')->where('id_materiales', $request->id_material)->first();
            if (!$material || $material->cantidad < $request->cantidad) {
                return response()->json(['success' => false, 'message' => 'Stock insuficiente en el almacén central.'], 400);
            }

            DB::beginTransaction();

            // Descontar del almacén central
            DB::table('tb_materiales')
                ->where('id_materiales', $request->id_material)
                ->decrement('cantidad', $request->cantidad);

            // Agregar o actualizar inventario técnico
            $registro = DB::table('tb_inventario_tecnico')
                ->where('id_material', $request->id_material)
                ->where('id_usuario', $request->id_usuario)
                ->first();

            if ($registro) {
                DB::table('tb_inventario_tecnico')
                    ->where('id', $registro->id)
                    ->increment('cantidad', $request->cantidad);
            } else {
                DB::table('tb_inventario_tecnico')->insert([
                    'id_material' => $request->id_material,
                    'id_usuario' => $request->id_usuario,
                    'cantidad' => $request->cantidad,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            // Registrar en movimientos
            DB::table('tb_movimientos_inventario')->insert([
                'id_material' => $request->id_material,
                'id_origen' => null, // almacén central
                'id_destino' => $request->id_usuario,
                'tipo_movimiento' => 'asignacion',
                'cantidad' => $request->cantidad,
                'motivo' => 'Asignación desde almacén central',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Material asignado correctamente al técnico.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al asignar material: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al asignar material al técnico.'], 500);
        }
    }

    public function MaterialesUsados(Request $request)
    {
        try {
            $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
            $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');

            $materiales = DB::table('tb_materiales')->get()->keyBy('id_materiales');
            $ordenes = DB::table('tb_orden_servicio')->select('cod_ordens', 'cod_incidencia', 'fecha_f', 'hora_f')
                ->whereBetween('fecha_f', [$fechaIni, $fechaFin])
                ->get()->keyBy('cod_ordens');
            $indicencias = DB::table('tb_incidencias')->select('cod_incidencia', 'ruc_empresa', 'id_sucursal')->get()->keyBy('cod_incidencia');
            $sucursales = DB::table('tb_sucursales')->select('id', 'nombre')->get()->keyBy('id');
            $empresas = DB::table('tb_empresas')->select('ruc', 'razon_social')->get()->keyBy('ruc');

            $materiales_usuados = DB::table('tb_materiales_usados')->get()
                ->filter(fn($val) => isset($ordenes[$val->cod_ordens])) // Filtra solo las ordenes que existen por el rango de fecha
                ->map(function ($val) use ($ordenes, $indicencias, $sucursales, $empresas, $materiales) {
                    $orden = $ordenes[$val->cod_ordens];
                    $indicencia = $indicencias[$orden->cod_incidencia];
                    $sucursal = $sucursales[$indicencia->id_sucursal];
                    $empresa = $empresas[$indicencia->ruc_empresa];
                    return [
                        'ruc_empresa' => $indicencia->ruc_empresa,
                        'razonSocial' => $empresa->razon_social,
                        'sucursal' => $sucursal->nombre,
                        'des_material' => $materiales[$val->id_material]->producto,
                        'cantidad' => $val->cantidad,
                        'fecha_orden' => "$orden->fecha_f $orden->hora_f",
                        'cod_orden' => $val->cod_ordens,
                        'cod_incidencia' => $orden->cod_incidencia,
                    ];
                });

            return $this->message(data: ['data' => $materiales_usuados]);
        } catch (Exception $e) {
            return $this->message(message: "Ocurrió un error interno en el servidor.", data: ['error' => $e->getCode(), 'linea' => $e->getLine()], status: 500);
        }
    }
}
