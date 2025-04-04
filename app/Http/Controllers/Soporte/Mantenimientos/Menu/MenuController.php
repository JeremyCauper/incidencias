<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Menu;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    // Ruta del archivo JSON
    private $jsonPath = 'config/jsons/menu.json';

    /**
     * Lee el archivo JSON y retorna los datos como arreglo.
     */
    public function readData()
    {
        $fullPath = storage_path($this->jsonPath);
        if (!file_exists($fullPath)) {
            // Si el archivo no existe, se crea con un arreglo vacío.
            file_put_contents($fullPath, json_encode([]));
        }
        $json = file_get_contents($fullPath);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Guarda el arreglo de datos en el archivo JSON.
     */
    private function saveData(array $data)
    {
        $fullPath = storage_path($this->jsonPath);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($fullPath, $json);
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
            $menus = $this->readData();
            // Ordenar por "orden" de forma ascendente, usando 0 si no existe la clave 'orden'
            usort($menus, function($a, $b) {
                $ordenA = $a['orden'] ?? 0;
                $ordenB = $b['orden'] ?? 0;
                return $ordenA <=> $ordenB;
            });

            // Mapear cada menú para agregar propiedades adicionales
            $menus = array_map(function ($val) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $val['iconText'] = '<i class="' . ($val['icon'] ?? '') . '"></i> ' . ($val['icon'] ?? '');
                $val['submenu'] = ($val['submenu'] ?? 0) ? 'Sí' : 'No';
                // Usar valor por defecto para 'estatus'
                $estatus = $val['estatus'] ?? 0;
                $val['estado'] = '<label class="badge badge-' . $estado[$estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$estatus]['text'] . '</label>';
                // Se utiliza el método DropdownAcciones heredado de Controller
                $val['acciones'] = $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "Editar({$val['id']})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                        ['funcion' => "CambiarEstado({$val['id']}, {$estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$estatus]['color'] . '"></i>Cambiar Estado']
                    ],
                ]);
                return $val;
            }, $menus);

            return response()->json($menus);
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

            $menus = $this->readData();

            // Verificar duplicados por descripción, icono y ruta usando el operador null coalescing
            foreach ($menus as $menu) {
                if (($menu['descripcion'] ?? '') === $request->descripcion) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                    ], 409);
                }
                if (($menu['icon'] ?? '') === $request->icono) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El icono ingresado ya está registrado. Por favor, use otro.'
                    ], 409);
                }
                if (($menu['ruta'] ?? '') === $request->ruta) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                    ], 409);
                }
            }

            // Generar un nuevo ID; se puede usar el último id + 1 o buscar el máximo
            $newId = count($menus) > 0 ? max(array_column($menus, 'id')) + 1 : 1;
            // Determinar el nuevo orden
            $nuevoOrden = count($menus) + 1;

            $nuevoMenu = [
                'id'          => $newId,
                'descripcion' => $request->descripcion,
                'icon'        => $request->icono,
                'ruta'        => $request->ruta,
                'submenu'     => $request->submenu,
                'eliminado'   => 0,
                'sistema'     => $request->desarrollo,
                'orden'       => $nuevoOrden,
                'estatus'     => $request->estado,
                'created_at'  => now()->format('Y-m-d H:i:s'),
                'updated_at'  => ""
            ];

            $menus[] = $nuevoMenu;
            $this->saveData($menus);

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
            $menus = $this->readData();
            $menu = null;
            foreach ($menus as $item) {
                if (($item['id'] ?? null) == $id) {
                    $menu = $item;
                    break;
                }
            }

            if (!$menu) {
                return response()->json([
                    "success" => false,
                    "message" => "No se encontró el menú solicitado. Verifica el código e intenta nuevamente."
                ], 404);
            }

            return response()->json([
                "success" => true,
                "message" => "",
                "data"    => $menu
            ], 200);
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

            $menus = $this->readData();
            $actualizado = false;

            // Verificar duplicados (excluyendo el menú que se está editando)
            foreach ($menus as $menu) {
                if (($menu['id'] ?? null) != $request->id) {
                    if (($menu['descripcion'] ?? '') === $request->descripcion) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                        ], 409);
                    }
                    if (($menu['icon'] ?? '') === $request->icono) {
                        return response()->json([
                            'success' => false,
                            'message' => 'El icono ingresado ya está registrado. Por favor, use otro.'
                        ], 409);
                    }
                    if (($menu['ruta'] ?? '') === $request->ruta) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                        ], 409);
                    }
                }
            }

            // Actualizar el menú
            foreach ($menus as &$menu) {
                if (($menu['id'] ?? null) == $request->id) {
                    $menu['descripcion'] = $request->descripcion;
                    $menu['icon'] = $request->icono;
                    $menu['ruta'] = $request->ruta;
                    $menu['submenu'] = $request->submenu;
                    $menu['sistema'] = $request->desarrollo;
                    $menu['estatus'] = $request->estado;
                    $menu['updated_at'] = now()->format('Y-m-d H:i:s');
                    $actualizado = true;
                    break;
                }
            }
            unset($menu);

            if (!$actualizado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el menú a actualizar.'
                ], 404);
            }

            $this->saveData($menus);

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

            $menus = $this->readData();
            $encontrado = false;

            foreach ($menus as &$menu) {
                if (($menu['id'] ?? null) == $request->id) {
                    $menu['estatus'] = $request->estado;
                    $menu['updated_at'] = now()->format('Y-m-d H:i:s');
                    $encontrado = true;
                    break;
                }
            }
            unset($menu);

            if (!$encontrado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el menú para cambiar el estado.'
                ], 404);
            }

            $this->saveData($menus);

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

    /**
     * Cambia el orden de los menús.
     */
    public function changeOrdenMenu(Request $request)
    {
        try {
            $menus = $this->readData();

            // Se espera que $request->data sea un arreglo con 'id' y 'orden'
            foreach ($request->data as $item) {
                foreach ($menus as &$menu) {
                    if (($menu['id'] ?? null) == ($item['id'] ?? null)) {
                        $menu['orden'] = $item['orden'] ?? $menu['orden'] ?? 0;
                        $menu['updated_at'] = now()->format('Y-m-d H:i:s');
                        break;
                    }
                }
                unset($menu);
            }

            $this->saveData($menus);
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