<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Menu;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubMenuController extends Controller
{
    public function __construct()
    {
        JsonDB::schema('sub_menu', [
            'id' => 'int|primary_key|auto_increment',
            'id_menu' => 'int',
            'descripcion' => 'string|unique:"Descripcion"',
            'categoria' => 'string|default:""',
            'ruta' => 'string|unique:"Ruta"',
            'estatus' => 'int|default:1',
            'selected' => 'int|default:0',
            'eliminado' => 'int|default:0',
            'updated_at' => 'string|default:""',
            'created_at' => 'string|default:""'
        ]);
    }
    public function view()
    {
        $this->validarPermisos(7, 9);
        try {
            $data = [];
            // Se leen los menús desde su JSON para pasarlos a la vista
            $data['menus'] = JsonDB::table('menu')->select('id', 'descripcion', 'icon', 'estatus', 'eliminado')->get();

            return view('soporte.mantenimientos.menu.submenu', ['data' => $data]);
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
            $submenus = JsonDB::table('sub_menu')->where('eliminado', 0)->get()->map(function ($val) {
                // Generar acciones
                return [
                    'id' => $val->id,
                    'menu' => $val->id_menu,
                    'categoria' => $val->categoria,
                    'descripcion' => $val->descripcion,
                    'ruta' => $val->ruta,
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
            return $submenus;
        } catch (Exception $e) {
            Log::error('Error inesperado: ' . $e->getMessage());
            return response()->json(['error' => 'Error inesperado: ' . $e->getMessage()], 500);
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
                'menu'        => 'required|integer',
                'categoria'   => 'nullable|string',
                'descripcion' => 'required|string|max:50',
                'ruta'        => 'required|string|max:255',
                'estado'      => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }
    
            JsonDB::table('sub_menu')->insert([
                'id_menu'     => $request->menu,
                'categoria'   => $request->categoria ?? '',
                'descripcion' => $request->descripcion,
                'ruta'        => $request->ruta,
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
            $submenu = JsonDB::table('sub_menu')->where('id', $id)->first();
    
            if (!$submenu) {
                return response()->json(["success" => false, "message" => "No se encontró el problema solicitado. Verifica el código e intenta nuevamente."], 404);
            }

            return response()->json(["success" => true, "message" => "", "data" => $submenu], 200);
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
            $validator = Validator::make($request->all(), [
                'id'          => 'required|integer',
                'menu'        => 'required|integer',
                'categoria'   => 'nullable|string',
                'descripcion' => 'required|string|max:50',
                'ruta'        => 'required|string|max:255',
                'estado'      => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }
    
            JsonDB::table('sub_menu')->where('id', $request->id)->update([
                'id_menu'     => $request->menu,
                'categoria'   => $request->categoria ?? '',
                'descripcion' => $request->descripcion,
                'ruta'        => $request->ruta,
                'estatus'     => $request->estado,
                'updated_at'  => now()->format('Y-m-d H:i:s')
            ]);
            return $this->message(message: "Edición realizada con éxito.");
        } catch (Exception $e) {
            if ($e->getCode() == 409) {
                return $this->message(message: $e->getMessage(), status: 409);
            }
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

            JsonDB::table('sub_menu')->where('id', $request->id)->update([
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

            JsonDB::table('sub_menu')->where('id', $request->id)->update([
                'estatus' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cambio de estado realizado con éxito.'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
