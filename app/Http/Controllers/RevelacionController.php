<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class RevelacionController extends Controller
{
    public function confirmar(Request $request)
    {
        try {
            // Validación
            $request->validate([
                'nombres' => 'required|string|max:255',
                'apellidos' => 'required|string|max:255',
                'team' => 'required|boolean',
                'fecha_confirmacion' => 'required|date_format:Y-m-d H:i:s',
            ]);

            if (DB::table('tb_revelacion')->where('nombres', $request->nombres)->where('apellidos', $request->apellidos)->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ya has confirmado tu asistencia'
                ], 400);
            }

            // Insertar en la tabla con Facade DB
            $invitado = DB::table('tb_revelacion')->insertGetId([
                'nombres' => $request->nombres,
                'apellidos' => $request->apellidos,
                'team' => $request->team,
                'fecha_confirmacion' => $request->fecha_confirmacion,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => '¡Gracias por confirmar tu asistencia!, Nos vemos pronto.',
                'data' => [
                    'id' => $invitado,
                    'nombres' => $request->nombres,
                    'apellidos' => $request->apellidos,
                    'team' => $request->team,
                    'fecha_confirmacion' => $request->fecha_confirmacion,
                ]
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error, intenta más tarde'
            ], 500);
        }
    }

    public function listarInvitados()
    {
        try {
            $invitados = DB::table('tb_revelacion')->get();

            return response()->json([
                'status' => 'success',
                'data' => $invitados
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error, intenta más tarde'
            ], 500);
        }
    }
}
