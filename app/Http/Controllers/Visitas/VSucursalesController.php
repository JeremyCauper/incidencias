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
            $empresas = DB::table('tb_empresas')->where('contrato', 1)->get()->keyBy('ruc');
            $visitas = DB::table('tb_visitas')->whereMonth('fecha', now()->format('m'))->get()->groupBy('id_sucursal')
                ->map(function ($items) {
                    return $items->mapWithKeys(function ($item) {
                        return [
                            $item->id => $item
                        ];
                    });
                });

            $sucursales = DB::table('tb_sucursales')->where('v_visitas', 1)->get()->filter(fn($val) => isset($empresas[$val->ruc])) // Filtra solo las sucursales con empresas activas
                ->map(function ($val) use ($empresas, $visitas) {
                    $vRealizadas = count($visitas[$val->id] ?? []);
                    $totalVisitas = $empresas[$val->ruc]->visitas;
                    $badgeKey = $vRealizadas ? ($vRealizadas == $totalVisitas ? 'completado' : $vRealizadas) : 0;
            
                    return [
                        'id' => $val->id,
                        'ruc' => $val->ruc,
                        'sucursal' => $val->nombre,
                        'visita' => $badgeKey
                    ];
                })->values(); // Resetea las claves (opcional si no necesitas una colección indexada por ID)

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
