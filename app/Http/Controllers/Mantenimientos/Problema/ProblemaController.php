<?php

namespace App\Http\Controllers\Mantenimientos\Problema;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProblemaController extends Controller
{
    public function view()
    {
        try {
            return view('mantenimientos.problemas.problemas');
        } catch (Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Terrible lo que pasará, ocurrió un error inesperado: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $problemas = DB::table('tb_problema')->where('estatus', 1)->get()->map(function ($p) {
                $p->acciones = $this->DropdownAcciones([
                    'tittle' => '',
                    'button' => [
                        ['funcion' => "Edit(this, '$p->cod_incidencia')", 'texto' => '<i class="fas fa-eye text-success me-2"></i> Ver Detalle'],
                        ['funcion' => "CambiarEstado($p->acciones)", 'texto' => '<i class="fas fa-pen text-info me-2"></i> Editar'],
                        ['funcion' => "ShowAssign(this, $p->acciones)", 'texto' => '<i class="fas fa-user-plus me-2"></i> Asignar'],
                    ],
                ]);
                return $p;
            });
            return $problemas;
        } catch (Exception $e) {
            Log::error('An unexpected error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Terrible lo que pasará, ocurrió un error inesperado: ' . $e->getMessage()], 500);
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
