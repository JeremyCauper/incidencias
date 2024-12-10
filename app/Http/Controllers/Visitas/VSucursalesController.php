<?php

namespace App\Http\Controllers\Visitas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Empresas\EmpresasController;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VSucursalesController extends Controller
{
    public function view()
    {
        try {
            $data = [];
            $data['empresas'] = (new EmpresasController())->index();
            
            return view('visitas.sucursales', ['data' => $data]);
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
            $empresas = DB::table('tb_empresas')->get()->keyBy(keyBy: 'ruc');
            // Procesar sucursales
            $sucursales = $sucursales->map(function ($val) use ($empresas) {
                $estado = [
                    ['color' => 'danger', 'text' => 'Inactivo'],
                    ['color' => 'success', 'text' => 'Activo']
                ];
                $id_grupo = $empresas[$val->ruc]->id_grupo;
                return [
                    'id' => $val->id,
                    'ruc' => $val->ruc,
                    'sucursal' => $val->nombre,
                    'visita' => $val->v_visitas
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
