<?php

namespace App\Http\Controllers\Soporte\Mantenimientos\Menu;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubMenuController extends Controller
{
    // Ruta del archivo JSON para submenus
    private $jsonPath = 'config/jsons/sub_menu.json';
    
    // Ruta del archivo JSON para menus (se asume que ya se tiene este archivo)
    private $jsonMenusPath = 'config/jsons/menu.json';

    /**
     * Lee el archivo JSON y retorna los datos de submenus como arreglo.
     */
    private function readData()
    {
        $fullPath = storage_path($this->jsonPath);
        if (!file_exists($fullPath)) {
            file_put_contents($fullPath, json_encode([]));
        }
        $json = file_get_contents($fullPath);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    /**
     * Guarda el arreglo de submenus en el archivo JSON.
     */
    private function saveData(array $data)
    {
        $fullPath = storage_path($this->jsonPath);
        $json = json_encode($data, JSON_PRETTY_PRINT);
        return file_put_contents($fullPath, $json);
    }
    
    /**
     * Lee el archivo JSON y retorna los datos de menus como arreglo.
     */
    private function readMenus()
    {
        $fullPath = storage_path($this->jsonMenusPath);
        if (!file_exists($fullPath)) {
            file_put_contents($fullPath, json_encode([]));
        }
        $json = file_get_contents($fullPath);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    public function view()
    {
        $this->validarPermisos(7, 9);
        try {
            $data = [];
            // Se leen los menús desde su JSON para pasarlos a la vista
            $data['menus'] = $this->readMenus();
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
            $menus = $this->readMenus();
            // Convertir los menús a un arreglo indexado por su id
            $menusKeyed = [];
            foreach ($menus as $menu) {
                $menusKeyed[$menu['id'] ?? 0] = $menu;
            }
            
            $submenus = $this->readData();
            // Filtrar para incluir solo los submenus que no han sido eliminados
            $submenus = array_filter($submenus, function($submenu) {
                return ($submenu['eliminado'] ?? 0) == 0;
            });

            // Mapear cada submenu para agregar propiedades adicionales
            $submenus = array_map(function ($val) use ($menusKeyed) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                // Obtener el menú asociado, usando valores por defecto en caso de que falte
                $menu = $menusKeyed[$val['id_menu'] ?? 0] ?? null;
                $menuIcon = $menu['icon'] ?? '';
                $menuDescripcion = $menu['descripcion'] ?? '';
                $val['menu'] = '<i class="' . $menuIcon . '"></i> ' . $menuDescripcion;
                
                $estatus = $val['estatus'] ?? 0;
                $val['estado'] = '<label class="badge badge-' . $estado[$estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$estatus]['text'] . '</label>';
                // Generar acciones usando el método heredado de Controller
                $val['acciones'] = $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "Editar({$val['id']})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                        ['funcion' => "CambiarEstado({$val['id']}, {$estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$estatus]['color'] . '"></i>Cambiar Estado']
                    ],
                ]);
                return $val;
            }, $submenus);
            // Reindexamos el arreglo (por si se filtró algún elemento)
            return response()->json(array_values($submenus));
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
    
            $submenus = $this->readData();
    
            // Validar duplicados: descripción y ruta
            foreach ($submenus as $submenu) {
                if (($submenu['descripcion'] ?? '') === $request->descripcion) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                    ], 409);
                }
                if (($submenu['ruta'] ?? '') === $request->ruta) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                    ], 409);
                }
            }
    
            // Generar un nuevo ID
            $newId = count($submenus) > 0 ? max(array_column($submenus, 'id')) + 1 : 1;
    
            $nuevoSubmenu = [
                'id'          => $newId,
                'id_menu'     => $request->menu,
                'categoria'   => $request->categoria ?? '',
                'descripcion' => $request->descripcion,
                'ruta'        => $request->ruta,
                'eliminado'   => 0,
                'estatus'     => $request->estado,
                'created_at'  => now()->format('Y-m-d H:i:s'),
                'updated_at'  => ""
            ];
    
            $submenus[] = $nuevoSubmenu;
            $this->saveData($submenus);
    
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
            $submenus = $this->readData();
            $submenu = null;
            foreach ($submenus as $item) {
                if (($item['id'] ?? null) == $id) {
                    $submenu = $item;
                    break;
                }
            }
    
            if (!$submenu) {
                return response()->json([
                    "success" => false,
                    "message" => "No se encontró el submenu solicitado. Verifica el código e intenta nuevamente."
                ], 404);
            }
    
            return response()->json([
                "success" => true,
                "message" => "",
                "data"    => $submenu
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
    
            $submenus = $this->readData();
            $actualizado = false;
    
            // Validar duplicados (excluyendo el submenu que se edita)
            foreach ($submenus as $submenu) {
                if (($submenu['id'] ?? null) != $request->id) {
                    if (($submenu['descripcion'] ?? '') === $request->descripcion) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                        ], 409);
                    }
                    if (($submenu['ruta'] ?? '') === $request->ruta) {
                        return response()->json([
                            'success' => false,
                            'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                        ], 409);
                    }
                }
            }
    
            foreach ($submenus as &$submenu) {
                if (($submenu['id'] ?? null) == $request->id) {
                    $submenu['id_menu'] = $request->menu;
                    $submenu['categoria'] = $request->categoria ?? '';
                    $submenu['descripcion'] = $request->descripcion;
                    $submenu['ruta'] = $request->ruta;
                    $submenu['estatus'] = $request->estado;
                    $submenu['updated_at'] = now()->format('Y-m-d H:i:s');
                    $actualizado = true;
                    break;
                }
            }
            unset($submenu);
    
            if (!$actualizado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el submenu a actualizar.'
                ], 404);
            }
    
            $this->saveData($submenus);
    
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
     * Cambia el estado del submenu.
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
    
            $submenus = $this->readData();
            $encontrado = false;
    
            foreach ($submenus as &$submenu) {
                if (($submenu['id'] ?? null) == $request->id) {
                    $submenu['estatus'] = $request->estado;
                    $submenu['updated_at'] = now()->format('Y-m-d H:i:s');
                    $encontrado = true;
                    break;
                }
            }
            unset($submenu);
    
            if (!$encontrado) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se encontró el submenu para cambiar el estado.'
                ], 404);
            }
    
            $this->saveData($submenus);
    
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
