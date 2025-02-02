<?php

namespace App\Http\Controllers\Mantenimientos\Menu;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubMenuController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            $data['menus'] = DB::table('tb_menu')->select('id_menu', 'descripcion', 'icon', 'eliminado')->get();
            
            return view('mantenimientos.menu.submenu', ['data' => $data]);
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
            $menus = DB::table('tb_menu')->select('id_menu', 'descripcion', 'icon')->get()->keyBy('id_menu');
            $submenus = DB::table('tb_submenu')->where('eliminado', 0)->get()->map(function ($val) use($menus) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $val->menu = '<i class="' . $menus[$val->id_menu]->icon . '"></i> ' . $menus[$val->id_menu]->descripcion;
                $val->estado = '<label class="badge badge-' . $estado[$val->estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$val->estatus]['text'] . '</label>';
                // Generar acciones
                $val->acciones = $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "Editar({$val->id_submenu})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                        ['funcion' => "CambiarEstado({$val->id_submenu}, {$val->estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$val->estatus]['color'] . '"></i>Cambiar Estado']
                    ],
                ]);
                return $val;
            });
            return $submenus;
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
                'menu' => 'required|integer',
                'categoria' => 'nullable|string',
                'descripcion' => 'required|string|max:50',
                'ruta' => 'required|string|max:255',
                'estado' => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors' => $validator->errors()
                ], 400);
            }
    
            // Validar si ya existe un menu con el mismo código o descripción
            $existeDescripcion = DB::table('tb_submenu')->where('descripcion', $request->descripcion)->exists();
            $existeRuta = DB::table('tb_submenu')->where('ruta', $request->ruta)->exists();
    
            if ($existeDescripcion) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            if ($existeRuta) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo menu en la base de datos
            DB::beginTransaction();
            DB::table('tb_submenu')->insert([
                'id_menu' => $request->menu,
                'categoria' => $request->categoria,
                'descripcion' => $request->descripcion,
                'ruta' => $request->ruta,
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
            $submenu = DB::table('tb_submenu')->where('id_submenu', $id)->first();
    
            if (!$submenu) {
                return response()->json(["success" => false, "message" => "No se encontró el menu solicitado. Verifica el código e intenta nuevamente."], 404);
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
            // Validación de los datos de entrada
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'menu' => 'required|integer',
                'categoria' => 'nullable|string',
                'descripcion' => 'required|string|max:50',
                'ruta' => 'required|string|max:255',
                'estado' => 'required|integer'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Por favor, revisa los campos e intenta nuevamente.',
                    'errors' => $validator->errors()
                ], 400);
            }

            // Validar si ya existe un menu con el mismo código o descripción
            $existeDescripcion = DB::table('tb_submenu')->select('id_submenu')->where('descripcion', $request->descripcion)->get()->first();
            $existeRuta = DB::table('tb_submenu')->select('id_submenu')->where('ruta', $request->ruta)->get()->first();
    
            if ($existeDescripcion && $existeDescripcion->id_submenu != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            if ($existeRuta && $existeRuta->id_submenu != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            DB::beginTransaction();
            DB::table('tb_submenu')->where('id_submenu', $request->id)->update([
                'id_menu' => $request->menu,
                'categoria' => $request->categoria,
                'descripcion' => $request->descripcion,
                'ruta' => $request->ruta,
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
            DB::table('tb_submenu')->where('id_submenu', $request->id)->update([
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
