<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Problema;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\select;

class ProblemaController extends Controller
{
    public function __construct()
    {
        JsonDB::schema('problema', [
            'id' => 'int',
            'codigo' => 'string',
            'descripcion' => 'string',
            'tipo_soporte' => 'int',
            'estatus' => 'int|default:1',
            'selected' => 'int|default:0',
            'eliminado' => 'int|default:0',
            'updated_at' => 'string|default:""',
            'created_at' => 'string',
        ]);
    }

    public function view()
    {
        $this->validarPermisos(6, 7);
        try {
            $data = [];
            $data['tSoporte'] = JsonDB::table('tipo_soporte')->select('id', 'descripcion', 'selected', 'estatus', 'eliminado')->keyBy('id');

            return view('soporte.mantenimientos.problemas.problemas', ['data' => $data]);
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
            $problemas = JsonDB::table('problema')->where('eliminado', 0)->get()->map(function ($val) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ][$val->estatus];
                // Generar acciones
                return [
                    'id' => $val->id,
                    'codigo' => $val->codigo,
                    'descripcion' => $val->descripcion,
                    'tipo_soporte' => $val->tipo_soporte,
                    'estado' => '<label class="badge badge-' . $estado['color'] . '" style="font-size: .7rem;">' . $estado['text'] . '</label>',
                    'updated_at' => $val->updated_at,
                    'created_at' => $val->created_at,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            ['funcion' => "Editar({$val->id})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                            ['funcion' => "CambiarEstado({$val->id}, {$val->estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado['color'] . '"></i>Cambiar Estado'],
                            ['funcion' => "Eliminar({$val->id})", 'texto' => '<i class="far fa-trash-can me-2 text-danger"></i>Eliminar'],
                        ],
                    ])
                ];
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
            $existeCodigo = JsonDB::table('problema')->where('codigo', $request->codigo)->first();
            $existeDescripcion = JsonDB::table('problema')->where('descripcion', $request->descripcion)->first();
    
            if (!empty($existeCodigo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'El código ingresado ya está registrado. Por favor, usa otro.'
                ], 409);
            }
    
            if (!empty($existeDescripcion)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            JsonDB::table('problema')->insert([
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo_soporte' => $request->tipo,
                'estatus' => $request->estado,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
    
            return response()->json([
                'success' => true,
                'message' => "Operación realizada con éxito."
            ]);
        } catch (Exception $e) {
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
            $problema = JsonDB::table('problema')->where('id', $id)->first();
    
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
            $reg_existe = JsonDB::table('problema')
                ->where('codigo', $request->codigo)
                ->where('descripcion', $request->descripcion)->first();
    
            if ($reg_existe && $reg_existe->id != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción o el codigo ingresado ya está registrado. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            JsonDB::table('problema')->where('id', $request->id)->update([
                'codigo' => $request->codigo,
                'descripcion' => $request->descripcion,
                'tipo_soporte' => $request->tipo,
                'estatus' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
    
            return response()->json([
                'success' => true,
                'message' => "Edición realizada con éxito."
            ]);
        } catch (Exception $e) {
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

            JsonDB::table('problema')->where('id', $request->id)->update([
                'estatus' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);

            return response()->json(['success' => true, 'message' => 'Cambio de estado realizado con éxito.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors' => $validator->errors()
                ], 400);
            }

            JsonDB::table('problema')->where('id', $request->id)->update([
                'eliminado' => 1
            ]);

            return response()->json(['success' => true, 'message' => 'El regitro de eliminó con éxito.']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
