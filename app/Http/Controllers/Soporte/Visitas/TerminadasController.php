<?php

namespace App\Http\Controllers\Soporte\Visitas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Empresas\EmpresasController;
use App\Http\Controllers\Empresas\GruposController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class TerminadasController extends Controller
{
    public function view()
    {
        $this->validarPermisos(3, 2);
        try {
            $data = [];
            $data['empresas'] = DB::table('tb_empresas')->select('id', 'ruc', 'razon_social', 'contrato', 'direccion', 'status')->get()->keyBy('ruc');;
            $data['sucursales'] = DB::table('tb_sucursales')->select('id', 'ruc', 'nombre', 'direccion', 'status')->get()->keyBy('id');
            
            return view('soporte.visitas.terminadas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sucursal = $request->query('sucursal');
        $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
        $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');
        try {
            $whereVis = ['estado' => 2, 'eliminado' => 0];
            if (intval($sucursal)) {
                $whereVis['id_sucursal'] = intval($sucursal);
            }

            $orden = DB::table('tb_orden_visita')->select('id', 'cod_orden_visita', 'id_visita', 'created_at')
                ->whereBetween('created_at', ["$fechaIni 00:00:00", "$fechaFin 23:59:59"])->get()->keyBy('id_visita');
            $seguimiento = DB::table('tb_vis_seguimiento')->get()->groupBy('id_visitas')->map(fn($segui) => $segui->mapWithKeys(fn($item) => [$item->estado => $item]));
            $usuario = DB::table('tb_personal')->select('id_usuario', 'nombres', 'apellidos')->get()->keyBy('id_usuario');
            $asignadas = DB::table('tb_vis_asignadas')->get()->groupBy('id_visitas')->map(function ($items) use($usuario) {
                $id_usu = [];
                foreach ($items as $item) {
                    $id_usu[] = $this->formatearNombre($usuario[$item->id_usuario]->nombres, $usuario[$item->id_usuario]->apellidos);
                }
                return $id_usu;
            });

            $visitas = DB::table('tb_visitas')->select('id', 'id_sucursal')->where($whereVis)->get()->filter(function ($vis) use ($orden) {
                return $orden->has($vis->id); // Verifica si hay una orden para esta incidencia
            })->map(function ($vis) use($orden, $seguimiento, $asignadas) {
                $id = $vis->id;
                $cod_ordenv = $orden[$id]->cod_orden_visita;
                $tiempos = $seguimiento[$id];

                $vis->cod_ordenv = $cod_ordenv;
                $vis->fecha = $orden[$id]->created_at;
                $vis->tecnicos = implode(", ", $asignadas[$id]);
                $vis->horaIni = $tiempos[0]->created_at;
                $vis->horaFin = $tiempos[1]->created_at;
                $vis->acciones = $this->DropdownAcciones([
                    'tittle' => "Acciones",
                    'button' => [
                        ['funcion' => "ShowDetail(this, $id)", 'texto' => '<i class="fas fa-eye text-info me-2"></i> Ver Detalle'],
                        ['funcion' => "OrdenPdf('$cod_ordenv')", 'texto' => '<i class="far fa-file-pdf text-danger me-2"></i> Ver PDF']
                    ],
                ]);

                return $vis;
            });


            return $visitas;
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
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
