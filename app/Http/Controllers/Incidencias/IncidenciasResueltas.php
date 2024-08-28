<?php

namespace App\Http\Controllers\Incidencias;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncidenciasResueltas extends Controller
{
    public function datatable()
    {
        $empresas = GlobalHelper::getCompany();
        $company = [];
        foreach ($empresas as $val) {
            $company[$val->id] = "{$val->Ruc} - {$val->RazonSocial}";
        }

        $sucursales = GlobalHelper::getBranchOffice();
        $subcompany = [];
        foreach ($sucursales as $val) {
            $subcompany[$val->id] = [$val->Nombre, $val->Direccion];
        }

        $tipInc = GlobalHelper::getparsedata(GlobalHelper::getTipIncidencia());
        $problema = GlobalHelper::getparsedata(GlobalHelper::getProblema());
        $subproblema = GlobalHelper::getparsedata(GlobalHelper::getSubProblema());

        $orden = DB::table('tb_orden_servicio')->select(['cod_ordens', 'cod_incidencia', 'fecha_f', 'hora_f'])->where('estatus', 1)->get();
        $inc = DB::table('tb_incidencias')->select(['cod_incidencia', 'id_empresa', 'id_sucursal', 'id_tipo_incidencia', 'id_problema', 'id_subproblema'])->where(['estatus' => 1, 'estado_informe' => 3])->get();
        $inc_seguimiento = DB::table('tb_inc_seguimiento')->select(['cod_incidencia', 'fecha', 'hora', 'estado'])->get();
        $inc_asig = DB::table('tb_inc_asignadas')->get();

        $usuarios = [];
        foreach (GlobalHelper::getUsuarios() as $val) {
            $usuarios[$val->id_usuario] = "{$val->ndoc_usuario} - {$val->nombres} {$val->apellidos}";
        }

        $asignados = [];
        foreach ($inc_asig as $v) {
            $asignados[$v->cod_incidencia][] = $usuarios[$v->id_usuario];
        }

        $seguimiento = [];
        if ($inc_seguimiento) {
            foreach ($inc_seguimiento as $key => $val) {
                $date = "{$val->fecha} {$val->hora}";
                if ($val->estado) {
                    $seguimiento[$val->cod_incidencia]['final'] = $date;
                } else {
                    $seguimiento[$val->cod_incidencia]['incio'] = $date;
                }
            }
        }

        $incidencias = [];
        if ($inc) {
            foreach ($inc as $key => $val) {
                $incidencias[$val->cod_incidencia] = [
                    'empresa' => $company[$val->id_empresa],
                    'sucursal' => $subcompany[$val->id_sucursal][1],
                    'tipo_orden' => $tipInc[$val->id_tipo_incidencia],
                    'problema' => "{$problema[$val->id_problema]} => {$subproblema[$val->id_subproblema]}"
                ];
            }
        }

        if ($orden) {
            foreach ($orden as $key => $val) {
                $val->empresa = $incidencias[$val->cod_incidencia]['empresa'];
                $val->sucursal = $incidencias[$val->cod_incidencia]['sucursal'];
                $val->tipo_orden = $incidencias[$val->cod_incidencia]['tipo_orden'];
                $val->problema = $incidencias[$val->cod_incidencia]['problema'];
                $val->f_incio = $seguimiento[$val->cod_incidencia]['incio'];
                $val->f_final = $seguimiento[$val->cod_incidencia]['final'];
                $val->asignados = $asignados[$val->cod_incidencia];
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
                        <h6 class="dropdown-header text-secondary d-flex justify-content-between align-items-center"><i class="fas fa-gear"></i> Acciones</h6>
                        <button class="dropdown-item py-2" onclick="orderDetail(this, ' . ("'{$val->cod_ordens}'") . ')"><i class="fas fa-info text-info me-2"></i> Detalle de Orden</button>
                        <button class="dropdown-item py-2" onclick="displayOrder(' . ("'{$val->cod_ordens}'") . ')"><i class="far fa-file-lines text-primary me-2"></i> Visualizar Orden</button>
                        <button class="dropdown-item py-2" onclick="pdfOrder(' . ("'{$val->cod_ordens}'") . ')"><i class="far fa-file-pdf text-danger me-2"></i> Visualizar PDF</button>
                        <button class="dropdown-item py-2" onclick="orderTicket(' . ("'{$val->cod_ordens}'") . ')"><i class="fas fa-ticket text-warning me-2"></i> Visualizar Ticket</button>
                        <button class="dropdown-item py-2" onclick="addSignature(' . ("'{$val->cod_incidencia}'") . ')"><i class="fas fa-signature me-2"></i></i> Agregar Firma</button>
                    </div>
                </div>';
            }
        }

        return ['data' => $orden];
    }

    /**
     * Display a listing of the resource.
     */
    public function view()
    {
        try {
            // $dataInd = $this->dataInd();

            // if (isset($dataInd['error']))
            //     return response()->json(['error' => $dataInd['message']], 400);

            // return view('dashboard.soporte.panel', ['dataInd' => $dataInd]);
            return view('dashboard.soporte.incidencia_resulta');
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
