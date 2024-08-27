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

        $seguimiento = [];
        if ($inc_seguimiento) {
            foreach ($inc_seguimiento as $key => $val) {
                $date = "{$val->fecha} {$val->hora}";
                if ($val->estado) {
                    $seguimiento[$val->cod_incidencia]['incio'] = $date;
                } else {
                    $seguimiento[$val->cod_incidencia]['final'] = $date;
                }
            }
        }

        $incidencias = [];
        if ($inc) {
            foreach ($inc as $key => $val) {
                $incidencias[$val->cod_incidencia] = [
                    'empresa' => $company[$val->id_empresa],
                    'sucursal' => $subcompany[$val->id_sucursal][1],
                    'tipo_estacion' => $tipInc[$val->id_tipo_incidencia],
                    'problema' => "{$problema[$val->id_problema]} => {$subproblema[$val->id_subproblema]}"
                ];
            }
        }

        if ($orden) {
            foreach ($orden as $key => $val) {
                $val->empresa = $incidencias[$val->cod_incidencia]['empresa'];
                $val->sucursal = $incidencias[$val->cod_incidencia]['sucursal'];
                $val->tipo_estacion = $incidencias[$val->cod_incidencia]['tipo_estacion'];
                $val->problema = $incidencias[$val->cod_incidencia]['problema'];
            }
        }

        return $orden;
        /*foreach ($incidencias as $val) {
            $val->id_empresa = $company[$val->id_empresa];
            $val->direccion = $subcompany[$val->id_sucursal][1];
            $val->id_sucursal = $subcompany[$val->id_sucursal][0];
            $val->id_tipo_estacion = $__estacion[$val->id_tipo_estacion];
            $val->id_tipo_incidencia = $__incidencia[$val->id_tipo_incidencia];
            $val->id_problema = $__problema[$val->id_problema];
            $val->id_subproblema = $__subproblema[$val->id_subproblema];
            $text_e_informe = '<label class="badge badge-' . $e_informe[$val->estado_informe]['c'] . '" style="font-size: .7rem;">' . $e_informe[$val->estado_informe]['t'] . '</label>';
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
                    <h6 class="dropdown-header text-secondary d-flex justify-content-between align-items-center">' . $text_e_informe . '<i class="fas fa-gear"></i></h6>
                    <button class="dropdown-item py-2" onclick="showDetail(this, ' . ("'{$val->cod_incidencia}'") . ')"><i class="fas fa-eye text-success me-2"></i> Ver Detalle</button>
                    <button class="dropdown-item py-2" onclick="showEdit(' . $val->acciones . ')"><i class="fas fa-pen text-info me-2"></i> Editar</button>
                    <button class="dropdown-item py-2" onclick="assign(this, ' . $val->acciones . ')"><i class="fas fa-user-plus me-2"></i> Asignar</button>'
                . ($val->estado_informe == 1 ? '<button class="dropdown-item py-2" onclick="reloadInd(' . ("'{$val->cod_incidencia}'") . ', ' . $val->estado_informe . ')"><i class="' . ($val->estado_informe != 2 ? 'far fa-clock' : 'fas fa-clock-rotate-left') . ' text-warning me-2"></i> ' . ($val->estado_informe != 2 ? 'Iniciar' : 'Reiniciar') . ' Incidencia</button>' : '')
                . ($val->estado_informe == 2 ? '<button class="dropdown-item py-2" onclick="createOrden(this, ' . ("'{$val->cod_incidencia}'") . ')"><i class="fas fa-book-medical text-primary me-2"></i> Orden de servicio</button>' : '') .
                '<button class="dropdown-item py-2" onclick="idelete(' . $val->acciones . ')"><i class="far fa-trash-can text-danger me-2"></i> Eliminar</button>
                </div>
            </div>';
            $val->estado_informe = $text_e_informe;
        }*/
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
