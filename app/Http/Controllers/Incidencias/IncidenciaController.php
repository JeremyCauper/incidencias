<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IncidenciaController extends Controller
{
    public function dataInd()
    {
        try {
            $data = [
                'empresas' => [],
                'sucursales' => []
            ];
            
            $empresas = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas'));
            foreach ($empresas as $val) {
                array_push($data['empresas'], ['id' => $val->id, 'ruc' => $val->Ruc, 'empresa' => $val->Ruc . ' - ' . $val->RazonSocial]);
            }

            $sucursales = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=sucursales'));
            foreach ($sucursales as $val) {
                $data['sucursales'][$val->ruc][] = ['id' => $val->id, 'sucursal' => $val->Nombre];
            }

            $usuarios = DB::table('usuarios')->where('estatus', 1)->get();
            foreach ($usuarios as $val) {
                $data['usuarios'][] = ['value' => $val->id_usuario . "|" . $val->ndoc_usuario . "|" . $val->nombres . " " . $val->apellidos, 'text' => $val->ndoc_usuario . " - " . $val->nombres . " " . $val->apellidos];
            }

            $data['cargo_contaco'] = DB::table('cargo_contacto')->select('descripcion')->where('estatus', 1)->get();
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            $data['cEmpresa'] = count($empresas);
            $data['cSucursal'] = count($sucursales);

            return $data;
        } catch (\Throwable $th) {
            Log::error('Error retrieving data: ' . $th->getMessage());
            return response()->json(['error' => 'Service Unavailable', 'message' => $th->getMessage()], 503);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function datatable()
    {
        $data = [];
        $empresas = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas'));

        $sucursales = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=sucursales'));

        $usuarios = DB::table('usuarios')
            ->join('tipo_usuario', 'usuarios.tipo_acceso', '=', 'tipo_usuario.id_tipo_acceso')
            ->select('usuarios.ndoc_usuario', 'usuarios.id_usuario', 'usuarios.nombres', 'usuarios.apellidos', 'tipo_usuario.descripcion', 'usuarios.usuario', 'usuarios.pass_view', 'usuarios.estatus')
            ->where('usuarios.estatus', 1)
            ->get();

        /*foreach ($usuarios as $key => $val) {
            $val->nombres = explode(' ', $val->nombres)[0];
            $val->apellidos = explode(' ', $val->apellidos)[0];

            $val->id_usuario = '
            <div class="btn-group dropstart shadow-0">
                <button
                    type="button"
                    class="btn btn-tertiary hover-btn btn-sm px-2 shadow-0"
                    data-mdb-ripple-init
                    aria-expanded="false"
                    data-mdb-dropdown-init
                    data-mdb-ripple-color="dark"
                    data-mdb-dropdown-initialized="true">
                    <b><i class="icon-menu9"></i></b>
                </button>
                <div class="dropdown-menu shadow-6">
                    <h6 class="dropdown-header text-primary"><b>Acciones</b></h6>
                    <button class="dropdown-item py-2" onclick="showUsuario(' . $val->id_usuario . ')"><i class="fas fa-user-pen text-info me-2"></i> Editar</button>
                    <button class="dropdown-item py-2" onclick="cambiarEstado(' . $val->id_usuario . ', ' . $val->estatus . ')"><i class="fas fa-rotate text-danger me-2"></i> Cambiar Estado</button>
                </div>
            </div>';
            $val->estatus = '<label class="badge badge-' . ($val->estatus ? 'success' : 'danger') . '" style="font-size: .7rem;">' . ($val->estatus ? 'ACTIVO' : 'INACTIVO') . '</label>';
        }*/

        return $usuarios;
    }

    /**
     * Display a view of the resource.
     */
    public function view()
    {
        try {
            $dataInd = $this->dataInd();
    
            if (isset($dataInd['error']))
                return view('error_view', ['message' => $dataInd['message']]);
    
            return view('dashboard.soporte.panel', ['dataInd' => $dataInd]);
        } catch (\Exception $e) {
            return view('error_view', ['message' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cod_inc' => 'required|string',
                'id_empresa' => 'required|integer',
                'id_sucursal' => 'required|integer',
                'tel_contac' => 'required|string',
                'nro_doc' => 'nullable|integer',
                'nom_contac' => 'required|string',
                'car_contac' => 'required|string',
                'cor_contac' => 'nullable|string',
                'tip_est_inc' => 'required|string',
                'priori_inc' => 'required|string',
                'tip_soport' => 'required|string',
                'tip_inc' => 'required|string',
                'observasion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i'
            ]);
    
            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            $personal_asig = $request->personal_asig;
            $estado_info = count($personal_asig) ? 1 : 0;

            DB::beginTransaction();
            DB::table('tb_incidencias')->insert([
                'cod_incidencia' => $request->cod_inc,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->id_sucursal,
                'id_usuario' => Auth::user()->id_usuario,
                'tipo_estacion' => $request->tip_est_inc,
                'prioridad' => $request->priori_inc,
                'tipo_soporte' => $request->tip_soport,
                'tipo_incidencia' => $request->tip_inc,
                'observasion' => $request->observasion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'estado_informe' => $estado_info,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('contactos_empresas')->insert([
                'telefono' => $request->tel_contac,
                'nro_doc' => $request->nro_doc,
                'nombres' => $request->nom_contac,
                'cargo' => $request->car_contac,
                'correo' => $request->cor_contac,
                'id_empresa' => $request->id_empresa,
                'id_sucursal' => $request->id_sucursal,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (count($personal_asig))
                DB::table('tb_inc_asignadas')->insert($personal_asig);

            DB::commit();

            $data = [];
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;
            return response()->json([
                'success' => true,
                'message' => 'Datos insertados exitosamente.',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al insertar los datos: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
