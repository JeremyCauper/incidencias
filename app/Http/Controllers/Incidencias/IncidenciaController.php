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

            $data['cargo_contaco'] = DB::table('cargo_contacto')->select('id_cargo as id', 'descripcion')->where('estatus', 1)->get();
            $data['tipo_estacion'] = DB::table('tb_tipo_estacion')->select('id_tipo_estacion as id', 'descripcion')->where('estatus', 1)->get();
            $data['tipo_soporte'] = DB::table('tb_tipo_soporte')->select('id_tipo_soporte as id', 'descripcion')->where('estatus', 1)->get();
            $data['tipo_incidencia'] = DB::table('tb_tipo_incidencia')->select('id_tipo_incidencia as id', 'descripcion')->where('estatus', 1)->get();
            $data['cod_inc'] = DB::select('CALL GetCodeInc()')[0]->cod_incidencia;

            $data['problema'] = DB::table('tb_problema')->select('id_problema as id', 'tipo_incidencia', DB::raw("CONCAT(codigo, ' - ', descripcion) AS text"))->where('estatus', 1)->get();
            $data['subproblema'] = DB::table('tb_subproblema')->select('id_subproblema as id', 'id_problema', DB::raw("CONCAT(codigo_sub, ' - ', descripcion) AS text"))->where('estatus', 1)->get();

            return $data;
        } catch (\Throwable $th) {
            Log::error('Error retrieving data: ' . $th->getMessage());
            return ['error' => 'Service Unavailable', 'message' => $th->getMessage()];
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function datatable()
    {
        $empresas = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=empresas'));
        $company = [];
        foreach ($empresas as $val) {
            $company[$val->id] = $val->Ruc . " - " .$val->RazonSocial;
        }

        $sucursales = json_decode(file_get_contents('https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=sucursales'));
        $subcompany = [];
        foreach ($sucursales as $val) {
            $subcompany[$val->id] = $val->Nombre;
        }

        $tipo_estacion = DB::table('tb_tipo_estacion')->select('id_tipo_estacion as id', 'descripcion')->get();
        $__estacion = $this->getparsedata($tipo_estacion);
        $tipo_incidencia = DB::table('tb_tipo_incidencia')->select('id_tipo_incidencia as id', 'descripcion')->get();
        $__incidencia = $this->getparsedata($tipo_incidencia);
        $problema = DB::table('tb_problema')->select('id_problema as id', 'tipo_incidencia', 'descripcion')->get();
        $__problema = $this->getparsedata($problema);
        $subproblema = DB::table('tb_subproblema')->select('id_subproblema as id', 'id_problema', 'descripcion')->get();
        $__subproblema = $this->getparsedata($subproblema);

        $incidencias = DB::table('tb_incidencias')
            ->select('cod_incidencia', 'id_empresa', 'id_sucursal', 'created_at', 'id_tipo_estacion', 'id_tipo_incidencia', 'id_problema', 'id_subproblema', 'estado_informe', 'id_incidencia as acciones')
            ->get();

        foreach ($incidencias as $val) {
            $val->id_empresa = $company[$val->id_empresa];
            $val->id_sucursal = $subcompany[$val->id_sucursal];
            $val->id_tipo_estacion = $__estacion[$val->id_tipo_estacion];
            $val->id_tipo_incidencia = $__incidencia[$val->id_tipo_incidencia];
            $val->id_problema = $__problema[$val->id_problema];
            $val->id_subproblema = $__subproblema[$val->id_subproblema];
            $val->estado_informe = '<label class="badge badge-' . ($val->estado_informe ? 'primary' : 'warning') . '" style="font-size: .7rem;">' . ($val->estado_informe ? 'Asignado' : 'Sin Asignar') . '</label>';

            $val->acciones = '
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
                    <button class="dropdown-item py-2" onclick=""><i class="fas fa-user-pen text-info me-2"></i> Ver Detalle</button>
                    <button class="dropdown-item py-2" onclick=""><i class="fas fa-rotate text-danger me-2"></i> Asignar</button>
                </div>
            </div>';
        }

        return $incidencias;
    }

    /**
     * Display a view of the resource.
     */
    public function view()
    {
        try {
            $dataInd = $this->dataInd();

            if (isset($dataInd['error']))
                return response()->json(['error' => $dataInd['message']], 400);

            return view('dashboard.soporte.panel', ['dataInd' => $dataInd]);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
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
                'tip_estacion' => 'required|integer',
                'priori_inc' => 'required|string',
                'tip_soport' => 'required|integer',
                'tip_incidencia' => 'required|integer',
                'inc_problem' => 'required|integer',
                'inc_subproblem' => 'required|integer',
                'observasion' => 'nullable|string',
                'fecha_imforme' => 'required|date',
                'hora_informe' => 'required|date_format:H:i',
                'tel_contac' => 'nullable|string',
                'nro_doc' => 'nullable|integer',
                'nom_contac' => 'nullable|string',
                'car_contac' => 'nullable|string',
                'cor_contac' => 'nullable|string'
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
                'id_tipo_estacion' => $request->tip_estacion,
                'prioridad' => $request->priori_inc,
                'id_tipo_soporte' => $request->tip_soport,
                'id_tipo_incidencia' => $request->tip_incidencia,
                'id_problema' => $request->inc_problem,
                'id_subproblema' => $request->inc_subproblem,
                'observasion' => $request->observasion,
                'fecha_informe' => $request->fecha_imforme,
                'hora_informe' => $request->hora_informe,
                'estado_informe' => $estado_info,
                'id_usuario' => Auth::user()->id_usuario,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            if (
                $request->tel_contac ||
                $request->nro_doc ||
                $request->nom_contac ||
                $request->car_contac
            ) {
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
            }

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

    function getparsedata($data)
    {
        foreach ($data as $val) {
            $data[$val->id] = $val->descripcion;
        }
        return $data;
    }
}
