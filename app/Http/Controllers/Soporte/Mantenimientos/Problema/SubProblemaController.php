<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Problema;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubProblemaController extends Controller
{
    public function view()
    {
        $this->validarPermisos(6, 8);
        try {
            $data = [];
            $data['problemas'] = DB::table('tb_problema')->select('id_problema', 'codigo', 'descripcion', 'eliminado')->get();
            
            return view('soporte.mantenimientos.problemas.subproblemas', ['data' => $data]);
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
            $problemas = DB::table('tb_problema')->select('id_problema', 'codigo', 'descripcion', 'eliminado')->get()->keyBy('id_problema');
            $subproblemas = DB::table('tb_subproblema')->where('eliminado', 0)->get()->map(function ($val) use($problemas) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $val->cod_problema = $problemas[$val->id_problema]->codigo;
                $val->estado = '<label class="badge badge-' . $estado[$val->estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$val->estatus]['text'] . '</label>';
                // Generar acciones
                $val->acciones = $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "Editar({$val->id_subproblema})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                        ['funcion' => "CambiarEstado({$val->id_subproblema}, {$val->estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$val->estatus]['color'] . '"></i>Cambiar Estado']
                    ],
                ]);
                return $val;
            });
            return $subproblemas;
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
                'problema' => 'required|integer',
                'codigo_sub' => 'required|string|max:50',
                'descripcion' => 'required|string|max:255',
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
            $existeSubCodigo = DB::table('tb_subproblema')->where('codigo_sub', $request->codigo_sub)->exists();
            $existeDescripcion = DB::table('tb_subproblema')->where('descripcion', $request->descripcion)->exists();
    
            if ($existeSubCodigo) {
                return response()->json([
                    'success' => false,
                    'message' => 'El sub código ingresado ya está registrado. Por favor, usa otro.'
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
            DB::table('tb_subproblema')->insert([
                'id_problema' => $request->problema,
                'codigo_sub' => $request->codigo_sub,
                'descripcion' => $request->descripcion,
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
            $subproblema = DB::table('tb_subproblema')->where('id_subproblema', $id)->first();
    
            if (!$subproblema) {
                return response()->json(["success" => false, "message" => "No se encontró el problema solicitado. Verifica el código e intenta nuevamente."], 404);
            }
    
            return response()->json(["success" => true, "message" => "", "data" => $subproblema], 200);
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
                'problema' => 'required|integer',
                'codigo_sub' => 'required|string|max:50',
                'descripcion' => 'required|string|max:255',
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
            $existeCodigo = DB::table('tb_subproblema')->select('id_subproblema')->where('codigo_sub', $request->codigo_sub)->get()->first();
            $existeDescripcion = DB::table('tb_subproblema')->select('id_subproblema')->where('descripcion', $request->descripcion)->get()->first();
    
            if ($existeCodigo && $existeCodigo->id_subproblema != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código ingresado ya está registrado. Por favor, usa otro.'
                ], 409);
            }
    
            if ($existeDescripcion && $existeDescripcion->id_subproblema != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            DB::beginTransaction();
            DB::table('tb_subproblema')->where('id_subproblema', $request->id)->update([
                'id_problema' => $request->problema,
                'codigo_sub' => $request->codigo_sub,
                'descripcion' => $request->descripcion,
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
            DB::table('tb_subproblema')->where('id_subproblema', $request->id)->update([
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
