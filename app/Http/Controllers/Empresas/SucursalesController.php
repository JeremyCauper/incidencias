<?php

namespace App\Http\Controllers\Empresas;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SucursalesController extends Controller
{
    public function view()
    {
        $this->validarPermisos(4, 5);
        try {
            $data = [];
            $data['empresas'] = (new EmpresasController())->index();
            return view('empresas.sucursales', ['data' => $data]);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sucursales = DB::table('tb_sucursales')->get();
            $empresas = DB::table('tb_empresas')->get()->keyBy('ruc');
            $grupos = DB::table('tb_grupos')->get()->keyBy('id');
            // Procesar sucursales
            $sucursales = $sucursales->map(function ($val) use ($grupos, $empresas) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $id_grupo = $empresas[$val->ruc]->id_grupo;
                return [
                    'id' => $val->id,
                    'grupo' => $grupos[$id_grupo]->nombre,
                    'ruc' => $val->ruc,
                    'cofide' => $val->cofide,
                    'sucursal' => $val->nombre,
                    'direccion' => $val->direccion,
                    'ubigeo' => $val->ubigeo,
                    'status' => $val->status,
                    'estado' => '<label class="badge badge-' . $estado[$val->status]['color'] . '" style="font-size: .7rem;">' . $estado[$val->status]['text'] . '</label>',
                    'created_at' => $val->created_at,
                    'updated_at' => $val->updated_at,
                    'acciones' => $this->DropdownAcciones([
                        'tittle' => 'Acciones',
                        'button' => [
                            ['funcion' => "Editar({$val->id})", 'texto' => '<i class="fas fa-pen me-2 text-info"></i>Editar'],
                            ['funcion' => "CambiarEstado({$val->id}, {$val->status})", 'texto' => '<i class="fas fa-rotate me-2 text-' . $estado[$val->status]['color'] . '"></i>Cambiar Estado']
                        ],
                    ])
                ];
            });

            return $sucursales;
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'empresa' => 'required|integer',
                'sucursal' => 'required|string',
                'cofide' => 'nullable|string',
                'direccion' => 'required|string',
                'ubigeo' => 'required|string',
                'telefonoS' => 'nullable|string',
                'correoS' => 'nullable|string',
                'vVisitas' => 'nullable|integer',
                'vMantenimientos' => 'nullable|integer',
                'urlMapa' => 'nullable|string',
                'estado' => 'nullable|integer',
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();
            DB::table('tb_sucursales')->insert([
                'ruc' => $request->empresa,
                'nombre' => $request->sucursal,
                'cofide' => $request->cofide,
                'direccion' => $request->direccion,
                'ubigeo' => $request->ubigeo,
                'telefono' => $request->telefonoS,
                'correo' => $request->correoS,
                'v_visitas' => $request->vVisitas,
                'v_mantenimientos' => $request->vMantenimientos,
                'url_mapa' => $request->urlMapa,
                'status' => $request->estado,
                'created_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Registro Exitoso.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $sucursal = DB::table('tb_sucursales')->where('id', $id)->first();
            return response()->json(["success" => true, "message" => "", "data" => $sucursal], 200);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'empresa' => 'required|integer',
                'sucursal' => 'required|string',
                'cofide' => 'nullable|string',
                'direccion' => 'required|string',
                'ubigeo' => 'required|string',
                'telefonoS' => 'nullable|string',
                'correoS' => 'nullable|string',
                'vVisitas' => 'nullable|integer',
                'vMantenimientos' => 'nullable|integer',
                'urlMapa' => 'nullable|string',
                'estado' => 'nullable|integer',
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();
            DB::table('tb_sucursales')->where('id', $request->id)->update([
                'ruc' => $request->empresa,
                'nombre' => $request->sucursal,
                'cofide' => $request->cofide,
                'direccion' => $request->direccion,
                'ubigeo' => $request->ubigeo,
                'telefono' => $request->telefonoS,
                'correo' => $request->correoS,
                'v_visitas' => $request->vVisitas,
                'v_mantenimientos' => $request->vMantenimientos,
                'url_mapa' => $request->urlMapa,
                'status' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Edición Exitosa.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    public function changeStatus(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'estado' => 'required|integer'
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();
            DB::table('tb_sucursales')->where('id', $request->id)->update([
                'status' => $request->estado,
                'updated_at' => now()->format('Y-m-d H:i:s')
            ]);
            DB::commit();

            return response()->json(['success' => true, 'message' => 'Cambio de estado exitoso.']);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
