<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\TipoSoporte;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TipoSoporteController extends Controller
{
    public function __construct()
    {
        JsonDB::schema('tipo_soporte', [
            'id' => 'int|primary_key|auto_increment',
            'descripcion' => 'string|unique:"Descripcion"',
            'estatus' => 'int|default:1',
            'selected' => 'int|default:0',
            'eliminado' => 'int|default:0',
            'updated_at' => 'string|default:""',
            'created_at' => 'string|default:""'
        ]);
    }

    public function view()
    {
        // $this->validarPermisos(7, 9);
        try {
            return view('soporte.mantenimientos.tiposoporte.tiposoporte');
        } catch (Exception $e) {
            Log::error('Vista: ' . $e->getMessage());
            return $this->message(data: ['error' => 'Vista: ' . $e->getMessage()], status: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $tipo_soportes = JsonDB::table('tipo_soporte')->where('eliminado', 0)->get()->map(function ($val) {
                // Generar acciones
                return [
                    'id' => $val->id,
                    'descripcion' => $val->descripcion,
                    'estado' => $this->formatEstado($val->estatus),
                    'updated_at' => $val->updated_at,
                    'created_at' => $val->created_at,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            ['funcion' => "Editar({$val->id})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                            ['funcion' => "CambiarEstado({$val->id}, {$val->estatus})", 'texto' => $this->formatEstado($val->estatus, 'change')],
                            ['funcion' => "Eliminar({$val->id})", 'texto' => '<i class="far fa-trash-can me-2 text-danger"></i>Eliminar'],
                        ],
                    ])
                ];
            });
            return $tipo_soportes;
        } catch (Exception $e) {
            Log::error('Listado: ' . $e->getMessage());
            return $this->message(data: ['error' => 'Listado: ' . $e->getMessage()], status: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function create(Request $request)
    {
        try {
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'descripcion' => 'required|string|max:50',
                'estado'      => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }

            JsonDB::table('tipo_soporte')->insert([
                'descripcion' => $request->descripcion,
                'estatus'     => $request->estado,
                'created_at'  => now()->format('Y-m-d H:i:s')
            ]);
            return $this->message(message: "Operación realizada con éxito.");
        } catch (Exception $e) {
            if ($e->getCode() == 409) {
                return $this->message(message: $e->getMessage(), status: 409);
            }
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $tipo_soporte = JsonDB::table('tipo_soporte')->where('id', $id)->first();
    
            if (!$tipo_soporte) {
                return response()->json(["success" => false, "message" => "No se encontró el problema solicitado. Verifica el código e intenta nuevamente."], 404);
            }

            return $this->message(message: "Operación realizada con éxito.", data: ['data' => $tipo_soporte]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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
                'id'          => 'required|integer',
                'descripcion' => 'required|string|max:50',
                'estado'      => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }

            JsonDB::table('tipo_soporte')->where('id', $request->id)->update([
                'descripcion' => $request->descripcion,
                'estatus'     => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            return $this->message(message: "Edición realizada con éxito.");
        } catch (Exception $e) {
            if ($e->getCode() == 409) {
                return $this->message(message: $e->getMessage(), status: 409);
            }
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Cambia el estado del menú.
     */
    public function changeStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'     => 'required|integer',
                'estado' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }

            JsonDB::table('tipo_soporte')->where('id', $request->id)->update([
                'estatus' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);

            return $this->message(message: "Cambio de estado realizado con éxito.");
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
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

            JsonDB::table('tipo_soporte')->where('id', $request->id)->update([
                'eliminado' => 1
            ]);

            return $this->message(message: "El regitro de eliminó con éxito.");
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
