<?php

namespace App\Http\Controllers\Mantenimientos\Menu;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function view()
    {
        $this->validarPermisos(7, 9);
        try {
            return view('mantenimientos.menu.menu');
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
            $menus = DB::table('tb_menu')->where('eliminado', 0)->get()->map(function ($val) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $val->icon = '<i class="' . $val->icon . '"></i> ' . $val->icon;
                $val->submenu = $val->submenu ? 'Sí' : 'No';
                $val->estado = '<label class="badge badge-' . $estado[$val->estatus]['color'] . '" style="font-size: .7rem;">' . $estado[$val->estatus]['text'] . '</label>';
                // Generar acciones
                $val->acciones = $this->DropdownAcciones([
                    'tittle' => 'Acciones',
                    'button' => [
                        ['funcion' => "Editar({$val->id_menu})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                        ['funcion' => "CambiarEstado({$val->id_menu}, {$val->estatus})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$val->estatus]['color'] . '"></i>Cambiar Estado']
                    ],
                ]);
                return $val;
            });
            return $menus;
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
                'descripcion' => 'required|string|max:50',
                'icono' => 'required|string|max:255',
                'ruta' => 'required|string|max:255',
                'submenu' => 'required|integer',
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
            $existeDescripcion = DB::table('tb_menu')->where('descripcion', $request->descripcion)->exists();
            $existeIcon = DB::table('tb_menu')->where('icon', $request->icono)->exists();
            $existeRuta = DB::table('tb_menu')->where('ruta', $request->ruta)->exists();
    
            if ($existeDescripcion) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            if ($existeIcon) {
                return response()->json([
                    'success' => false,
                    'message' => 'El icono ingresado ya está registrado. Por favor, use otro.'
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
            DB::table('tb_menu')->insert([
                'descripcion' => $request->descripcion,
                'icon' => $request->icono,
                'ruta' => $request->ruta,
                'submenu' => $request->submenu,
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
            $menu = DB::table('tb_menu')->where('id_menu', $id)->first();
    
            if (!$menu) {
                return response()->json(["success" => false, "message" => "No se encontró el menu solicitado. Verifica el código e intenta nuevamente."], 404);
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
                'id' => 'required|integer',
                'descripcion' => 'required|string|max:50',
                'icono' => 'required|string|max:255',
                'ruta' => 'required|string|max:255',
                'submenu' => 'required|integer',
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
            $existeDescripcion = DB::table('tb_menu')->select('id_menu')->where('descripcion', $request->descripcion)->get()->first();
            $existeIcon = DB::table('tb_menu')->select('id_menu')->where('icon', $request->icono)->get()->first();
            $existeRuta = DB::table('tb_menu')->select('id_menu')->where('ruta', $request->ruta)->get()->first();
    
            if ($existeDescripcion && $existeDescripcion->id_menu != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La descripción ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            if ($existeIcon && $existeIcon->id_menu != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'El icono ingresado ya está registrado. Por favor, use otro.'
                ], 409);
            }
    
            if ($existeRuta && $existeRuta->id_menu != $request->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'La ruta ingresada ya está registrada. Por favor, usa otra.'
                ], 409);
            }
    
            // Insertar el nuevo problema en la base de datos
            DB::beginTransaction();
            DB::table('tb_menu')->where('id_menu', $request->id)->update([
                'descripcion' => $request->descripcion,
                'icon' => $request->icono,
                'ruta' => $request->ruta,
                'submenu' => $request->submenu,
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
            DB::table('tb_menu')->where('id_menu', $request->id)->update([
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
