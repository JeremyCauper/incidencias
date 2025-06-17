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
            $inventario = DB::table('tb_inventario_tecnico')->where('id_usuario', $id)->get()->map(function ($val) use($materiales) {
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
            'cantidad' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $central = DB::table('tb_materiales')->where('id_materiales', $request->id_material)->lockForUpdate()->first();
            if (!$central || $central->cantidad < $request->cantidad) {
                return response()->json(['error' => 'Stock insuficiente en almacén central'], 400);
            }

            DB::table('tb_materiales')->where('id_materiales', $request->id_material)->decrement('cantidad', $request->cantidad);

            $inventario = DB::table('tb_inventario_tecnico')
                ->where('id_usuario', $request->id_usuario)
                ->where('id_material', $request->id_material)
                ->first();

            if ($inventario) {
                DB::table('tb_inventario_tecnico')
                    ->where('id_usuario', $request->id_usuario)
                    ->where('id_material', $request->id_material)
                    ->increment('cantidad', $request->cantidad);
            } else {
                DB::table('tb_inventario_tecnico')->insert([
                    'id_usuario' => $request->id_usuario,
                    'id_material' => $request->id_material,
                    'cantidad' => $request->cantidad,
                    'created_at' => now(),
                ]);
            }

            DB::table('tb_movimientos_inventario')->insert([
                'id_usuario_origen' => null,
                'id_usuario_destino' => $request->id_usuario,
                'id_material' => $request->id_material,
                'cantidad' => $request->cantidad,
                'tipo_movimiento' => 'asignacion',
                'created_at' => now(),
            ]);

            DB::commit();

            return response()->json(['success' => 'Material asignado correctamente'], 200);
        } catch (\Exception $e) {
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
                        'updated_at' => now()
                    ]
                );

            DB::table('tb_trazabilidad')->insert([
                'tipo' => 'ajuste_manual',
                'id_usuario_origen' => null,
                'id_usuario_destino' => $request->id_usuario,
                'id_material' => $request->id_material,
                'cantidad' => $request->nueva_cantidad,
                'created_at' => now(),
            ]);

            return response()->json(['message' => 'Inventario actualizado correctamente.'], 200);
        } catch (\Exception $e) {
            Log::error('Error al actualizar inventario: ' . $e->getMessage());
            return response()->json(['error' => 'Error al actualizar el inventario.'], 500);
        }
    }
}
