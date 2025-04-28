<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Menu;

use App\Http\Controllers\Controller;
use App\Services\JsonDB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    private $checks;
    public function __construct()
    {
        JsonDB::schema('menu', [
            'id' => 'int',
            'descripcion' => 'string',
            'icon' => 'string',
            'ruta' => 'string',
            'submenu' => 'int|default:0',
            'sistema' => 'int|default:0',
            'orden' => 'int',
            'estatus' => 'int|default:1',
            'selected' => 'int|default:0',
            'eliminado' => 'int|default:0',
            'updated_at' => 'string|default:""',
            'created_at' => 'string|default:""'
        ]);

        $this->checks = [
            'descripcion' => [
                'value'   => 'descripcion',
                'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.',
            ],
            'icon' => [
                'value'   => 'icono',
                'message' => 'El icono ingresado ya está registrado. Por favor, use otro.',
            ],
            'ruta' => [
                'value'   => 'ruta',
                'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.',
            ],
        ];
    }

    public function view()
    {
        $this->validarPermisos(7, 9);
        try {
            return view('soporte.mantenimientos.menu.menu');
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
            $menus = JsonDB::table('menu')->where('eliminado', 0)->orderBy('orden', 'asc')->get()->map(function ($val) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ][$val->estatus];
                // Generar acciones
                return [
                    'id' => $val->id,
                    'orden' => $val->orden,
                    'descripcion' => $val->descripcion,
                    'icono' => $val->icon,
                    'iconText' => '<i class="' . ($val->icon ?? '') . '"></i> ' . ($val->icon ?? ''),
                    'ruta' => $val->ruta,
                    'submenu' => ($val->submenu ?? 0) ? 'Sí' : 'No',
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
            return $menus;
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
                'descripcion' => 'required|string|max:50',
                'icono'       => 'required|string|max:255',
                'ruta'        => 'required|string|max:255',
                'submenu'     => 'required|integer',
                'desarrollo'  => 'required|integer',
                'estado'      => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }

            // Verificar duplicados por descripción, icono y ruta usando el operador null coalescing
            foreach ($this->checks as $field => $data) {
                if (JsonDB::table('menu')->select($field)->where($field, $request[$data['value']])->first()) {
                    return response()->json(['success' => false, 'message' => $data['message']], 409);
                }
            }

            $nuevoOrden = count(JsonDB::table('menu')->get()) + 1;

            JsonDB::table('menu')->insert([
                'descripcion' => $request->descripcion,
                'icon'        => $request->icono,
                'ruta'        => $request->ruta,
                'submenu'     => $request->submenu,
                'sistema'     => $request->desarrollo,
                'orden'       => $nuevoOrden,
                'estatus'     => $request->estado,
                'created_at'  => now()->format('Y-m-d H:i:s')
            ]);

            return response()->json([
                'success' => true,
                'message' => "Operación realizada con éxito."
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error inesperado. Intenta nuevamente más tarde.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $menu = JsonDB::table('menu')->where('id', $id)->first();
    
            if (!$menu) {
                return response()->json(["success" => false, "message" => "No se encontró el problema solicitado. Verifica el código e intenta nuevamente."], 404);
            }

            return response()->json(["success" => true, "message" => "", "data" => $menu], 200);
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
                'id'          => 'required|integer',
                'descripcion' => 'required|string|max:50',
                'icono'       => 'required|string|max:255',
                'ruta'        => 'required|string|max:255',
                'submenu'     => 'required|integer',
                'desarrollo'  => 'required|integer',
                'estado'      => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors'  => $validator->errors()
                ], 400);
            }

            // Verificar duplicados (excluyendo el menú que se está editando)
            foreach ($this->checks as $field => $data) {
                if ((JsonDB::table('menu')->where($field, $request[$data['value']])->first())->id != $request->id) {
                    return response()->json(['success' => false, 'message' => $data['message']], 409);
                }
            }

            JsonDB::table('menu')->where('id', $request->id)->update([
                'descripcion' => $request->descripcion,
                'icon'        => $request->icono,
                'ruta'        => $request->ruta,
                'submenu'     => $request->submenu,
                'sistema'     => $request->desarrollo,
                'estatus'     => $request->estado,
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
                'error'   => $e->getMessage()
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

            JsonDB::table('menu')->where('id', $request->id)->update([
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

            JsonDB::table('menu')->where('id', $request->id)->update([
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
     * Cambia el orden de los menús.
     */
    public function changeOrdenMenu(Request $request)
    {
        try {
            // Se espera que $request->data sea un arreglo con 'id' y 'orden'
            foreach ($request->data as $item) {
                JsonDB::table('menu')->where('id', $item['id'])->update([
                    'orden' => $item['orden'],
                    'updated_at' => now()->format('Y-m-d H:i:s')
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Orden actualizado con éxito.'
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