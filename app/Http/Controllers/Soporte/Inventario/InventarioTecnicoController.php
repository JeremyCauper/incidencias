<?php

namespace App\Http\Controllers\Soporte\Inventario;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class InventarioTecnicoController extends Controller
{
    // Obtener inventario de un técnico
    public function index(Request $request): JsonResponse
    {
        $id = $request->query('id');
        try {
            $materiales = DB::table('tb_materiales')->get()->keyBy('id_materiales');
            $inventario = DB::table('tb_inventario_tecnico')->where('id_usuario', $id)->get()->map(function ($val) use ($materiales) {
                return [
                    'material' => $materiales[$val->id_material],
                    'cantidad' => $val->cantidad,
                    'id_material' => $val->id_material
                ];
            });

            return $this->message2(data: $inventario);
        } catch (Exception $e) {
            return $this->message2(e: $e, status: 500);
        }
    }

    // Asignar materiales a un técnico (ya debe estar validado desde MaterialesController)
    public function asignarMaterial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_usuario' => 'required|integer|exists:tb_personal,id_usuario',
            'id_material' => 'required|integer|exists:tb_materiales,id_materiales',
            'cantidad' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $central = DB::table('tb_materiales')->where('id_materiales', $request->id_material)->lockForUpdate()->first();
            if (!$central) {
                return response()->json(['error' => 'Material no encontrado en almacén central'], 404);
            }

            // Buscar si ya está asignado al técnico
            $inventario = DB::table('tb_inventario_tecnico')
                ->where('id_usuario', $request->id_usuario)
                ->where('id_material', $request->id_material)
                ->lockForUpdate()
                ->first();

            $cantidadActual = $inventario ? $inventario->cantidad : 0;
            $cantidadNueva = (int) $request->cantidad;
            $diferencia = $cantidadNueva - $cantidadActual;

            // Validar stock solo si se requiere más material
            if ($diferencia > 0 && $central->cantidad < $diferencia) {
                return response()->json(['error' => 'Stock insuficiente en almacén central'], 400);
            }

            // Si se asigna 0, eliminar el registro
            if ($cantidadNueva === 0 && $inventario) {
                // Eliminar asignación
                DB::table('tb_inventario_tecnico')
                    ->where('id_usuario', $request->id_usuario)
                    ->where('id_material', $request->id_material)
                    ->delete();

                // Devolver al almacén central
                DB::table('tb_materiales')
                    ->where('id_materiales', $request->id_material)
                    ->update(['cantidad' => $central->cantidad + $cantidadActual]);

                DB::commit();
                return response()->json(['success' => 'Material eliminado del técnico y stock restaurado'], 200);
            }

            // Si ya existe la asignación, actualizar
            if ($inventario) {
                DB::table('tb_inventario_tecnico')
                    ->where('id_usuario', $request->id_usuario)
                    ->where('id_material', $request->id_material)
                    ->update([
                        'cantidad' => $cantidadNueva,
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ]);
            } else {
                DB::table('tb_inventario_tecnico')->insert([
                    'id_usuario' => $request->id_usuario,
                    'id_material' => $request->id_material,
                    'cantidad' => $cantidadNueva,
                    'created_at' => now()->format('Y-m-d H:i:s')
                ]);
            }

            // Actualizar stock del central
            $nuevoStockCentral = $central->cantidad - $diferencia;
            DB::table('tb_materiales')
                ->where('id_materiales', $request->id_material)
                ->update([
                    'cantidad' => $nuevoStockCentral,
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ]);

            DB::table('tb_movimientos_inventario')->insert([
                'tipo_movimiento' => $diferencia < 0 ? 'DEVOLUCION' : 'ASIGNACION',
                'id_material' => $request->id_material,
                'cantidad' => abs($diferencia),
                'id_usuario_origen' => $diferencia < 0 ? $request->id_usuario : null,
                'id_usuario_destino' => $diferencia > 0 ? $request->id_usuario : null,
                'motivo' => $diferencia < 0 ? 'Reducción de material asignado' : 'Asignación de nuevo material',
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);


            DB::commit();

            return response()->json(['success' => 'Material asignado correctamente'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error al asignar material: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    // Actualizar inventario manualmente (por pérdida, auditoría, etc.)
    public function actualizarInventario(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id_usuario' => 'required|integer|exists:tb_personal,id_usuario',
            'id_material' => 'required|integer|exists:tb_materiales,id_materiales',
            'nueva_cantidad' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            DB::table('tb_inventario_tecnico')
                ->updateOrInsert(
                    [
                        'id_usuario' => $request->id_usuario,
                        'id_material' => $request->id_material
                    ],
                    [
                        'cantidad' => $request->nueva_cantidad,
                        'updated_at' => now()->format('Y-m-d H:i:s')
                    ]
                );

            DB::table('tb_trazabilidad')->insert([
                'tipo' => 'ajuste_manual',
                'id_usuario_origen' => null,
                'id_usuario_destino' => $request->id_usuario,
                'id_material' => $request->id_material,
                'cantidad' => $request->nueva_cantidad,
                'created_at' => now()->format('Y-m-d H:i:s'),
            ]);

            return response()->json(['message' => 'Inventario actualizado correctamente.'], 200);
        } catch (Exception $e) {
            Log::error('Error al actualizar inventario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el inventario.'], 500);
        }
    }
}
