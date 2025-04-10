<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Problema;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\select;

class ProblemaController extends Controller
{
    public function view()
    {
        $this->validarPermisos(6, 7);
        try {
            return view('soporte.mantenimientos.problemas.problemas');
        } catch (Exception $e) {
            Log::error('Error inesperado: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $problemas = DB::table('tb_problema')->where('eliminado', 0)->get()->map(function ($val) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $val->tipo_incidencia = $val->tipo_incidencia == 1 ? "REMOTO" : "PRESENCIAL";
                $val->estado = '<label class="badge badge-' . $estado[$val->estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$val->estatus]['text'] . '</label>';
                // Generar acciones
                $val->acciones = $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "Editar({$val->id_problema})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                        ['funcion' => "CambiarEstado({$val->id_problema}, {$val->estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$val->estatus]['color'] . '"></i>Cambiar Estado']
                    ],
                ]);
                return $val;
            });
            return $problemas;
        } catch (Exception $e) {
            Log::error('Error inesperado: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'codigo' => 'required|string|max:50',
                'descripcion' => 'required|string|max:255',
                'tipo' => 'required|integer',
                'estado' => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors' => $validator->errors()
                ], 400);
            }
    
            // Validar si ya existe un problema con el mismo código o descripción
            $existeCodigo = DB::table('tb_problema')->where('codigo', $request->codigo)->exists();
            $existeDescripcion = DB::table('tb_problema')->where('descripcion', $request->descripcion)->exists();
    
            if ($existeCodigo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código ingresado ya está registrado. Por favor, usa otro.'
                ], 409);
            }
    
            if ($existeDescripcion) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            DB::beginTransaction();
            DB::table('tb_problema')->insert([
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo_incidencia' => $request->tipo,
                'estatus' => $request->estado,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => "Operación realizada con éxito."
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $problema = DB::table('tb_problema')->where('id_problema', $id)->first();
    
            if (!$problema) {
                return response()->json(["success" => false, "message" => "No se encontró el problema solicitado. Verifica el código e intenta nuevamente."], 404);
            }
    
            return response()->json(["success" => true, "message" => "", "data" => $problema], 200);
        } catch (Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Ocurrió un error inesperado."
            ], 500);
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'codigo' => 'required|string|max:50',
                'descripcion' => 'required|string|max:255',
                'tipo' => 'required|integer',
                'estado' => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors' => $validator->errors()
                ], 400);
            }
    
            // Validar si ya existe un problema con el mismo código o descripción
            $existeCodigo = DB::table('tb_problema')->select('id_problema')->where('codigo', $request->codigo)->get()->first();
            $existeDescripcion = DB::table('tb_problema')->select('id_problema')->where('descripcion', $request->descripcion)->get()->first();
    
            if ($existeCodigo && $existeCodigo->id_problema != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código ingresado ya está registrado. Por favor, usa otro.'
                ], 409);
            }
    
            if ($existeDescripcion && $existeDescripcion->id_problema != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            DB::beginTransaction();
            DB::table('tb_problema')->where('id_problema', $request->id)->update([
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo_incidencia' => $request->tipo,
                'estatus' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => "Edición realizada con éxito."
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function changeStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'estado' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors' => $validator->errors()
                ], 400);
            }

            DB::beginTransaction();
            DB::table('tb_problema')->where('id_problema', $request->id)->update([
                'estatus' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cambio de estado realizado con éxito.']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
