<?php

namespace App\Http\Controllers\Visitas;

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
            
            return view('visitas.terminadas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $visitas = DB::table('tb_visitas')->select('id', 'id_sucursal')->get()->keyBy('id');
            $seguimiento = DB::table('tb_vis_seguimiento')->get()->groupBy('id_visitas')->map(fn($segui) => $segui->mapWithKeys(fn($item) => [$item->estado => $item]));
            $usuario = DB::table('usuarios')->select('id_usuario', 'nombres', 'apellidos')->get()->keyBy('id_usuario');
            $asignadas = DB::table('tb_vis_asignadas')->get()->groupBy('id_visitas')->map(function ($items) use($usuario) {
                $id_usu = [];
                foreach ($items as $item) {
                    $id_usu[] = $this->formatearNombre($usuario[$item->id_usuario]->nombres, $usuario[$item->id_usuario]->apellidos);
                }
                return $id_usu;
            });

            $orden = DB::table('tb_orden_visita')->select('id', 'cod_orden_visita', 'id_visita', 'created_at')->get()->map(function ($or) use($visitas, $asignadas) {
                
            });



            return $seguimiento;
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
