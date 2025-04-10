<?php

namespace App\Http\Controllers\Soporte\Empresas;

use App\Helpers\CargoEstacion;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmpresasController extends Controller
{
    public function view()
    {
        $this->validarPermisos(4, 3);
        try {
            $data = [];
            $data['grupos'] = (new GruposController())->index();
            $data['cargos'] = collect((new CargoEstacion())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            
            return view('soporte.empresas.empresas', ['data' => $data]);
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
            $empresas = DB::table('tb_empresas')->get();
            $grupos = DB::table('tb_grupos')->get()->keyBy('id');
            // Procesar incidencias
            $empresas = $empresas->map(function ($val) use ($grupos) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                return [
                    'id' => $val->id,
                    'grupo' => $grupos[$val->id_grupo]->nombre,
                    'ruc' => $val->ruc,
                    'razonSocial' => $val->razon_social,
                    'contrato' => $val->contrato ? 'Sí' : 'No',
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

            return $empresas;
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
                'idGrupo' => 'required|integer',
                'ruc' => 'required|integer',
                'razonSocial' => 'required|string',
                'direccion' => 'required|string',
                'ubigeo' => 'required|string',
                'contrato' => 'required|integer',
                'facturacion' => 'required|integer',
                'prico' => 'required|integer',
                'eds' => 'required|integer',
                'idNube' => 'nullable|integer',
                'visitas' => 'nullable|integer',
                'mantenimientos' => 'nullable|integer',
                'diasVisita' => 'nullable|integer',
                'codVisita' => 'nullable|string',
                'estado' => 'required|integer',
                'cargo' => 'nullable|string',
                'encargado' => 'nullable|string',
                'telefono' => 'nullable|string',
                'correo' => 'nullable|string'
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();
            DB::table('tb_empresas')->insert([
                'ruc' => $request->ruc,
                'razon_social' => $request->razonSocial,
                'contrato' => $request->contrato,
                'id_nube' => $request->idNube,
                'id_grupo' => $request->idGrupo,
                'direccion' => $request->direccion,
                'ubigeo' => $request->ubigeo,
                'facturacion' => $request->facturacion,
                'prico' => $request->prico,
                'encargado' => $request->encargado,
                'cargo' => $request->cargo,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'eds' => $request->eds,
                'visitas' => $request->visitas,
                'mantenimientos' => $request->mantenimientos,
                'dias_visita' => $request->diasVisita,
                'codigo_aviso' => $request->codVisita,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $empresa = DB::table('tb_empresas')->where('id', $id)->first();
            return response()->json(["success" => true, "message" => "", "data" => $empresa], 200);
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'idGrupo' => 'required|integer',
                'ruc' => 'required|integer',
                'razonSocial' => 'required|string',
                'direccion' => 'required|string',
                'ubigeo' => 'required|string',
                'contrato' => 'required|integer',
                'facturacion' => 'required|integer',
                'prico' => 'required|integer',
                'eds' => 'required|integer',
                'idNube' => 'nullable|integer',
                'visitas' => 'nullable|integer',
                'mantenimientos' => 'nullable|integer',
                'diasVisita' => 'nullable|integer',
                'codVisita' => 'nullable|string',
                'estado' => 'required|integer',
                'cargo' => 'nullable|string',
                'encargado' => 'nullable|string',
                'telefono' => 'nullable|string',
                'correo' => 'nullable|string'
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            DB::beginTransaction();
            DB::table('tb_empresas')->where('id', $request->id)->update([
                'ruc' => $request->ruc,
                'razon_social' => $request->razonSocial,
                'contrato' => $request->contrato,
                'id_nube' => $request->idNube,
                'id_grupo' => $request->idGrupo,
                'direccion' => $request->direccion,
                'ubigeo' => $request->ubigeo,
                'facturacion' => $request->facturacion,
                'prico' => $request->prico,
                'encargado' => $request->encargado,
                'cargo' => $request->cargo,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'eds' => $request->eds,
                'visitas' => $request->visitas,
                'mantenimientos' => $request->mantenimientos,
                'dias_visita' => $request->diasVisita,
                'codigo_aviso' => $request->codVisita,
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
            DB::table('tb_empresas')->where('id', $request->id)->update([
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
