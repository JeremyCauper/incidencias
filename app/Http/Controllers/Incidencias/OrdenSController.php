<?php

namespace App\Http\Controllers\Incidencias;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdenSController extends Controller
{
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
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'n_orden' => 'required|string',
            'obs' => 'required|string',
            'rec' => 'required|string',
            'fecha_f' => 'required|date',
            'hora_f' => 'required|date_format:H:i:s',
            'materiales' => 'required|array',
            'firma_digital' => 'required|string',
            'n_doc' => 'required|integer',
            'nom_cliente' => 'required|string',
        ]);

        if ($validator->fails())
            return response()->json(['errors' => $validator->errors()], 400);

        $materiales = $request->materiales;
        $materiales[0]['updated_at'] = now();
        $materiales[0]['created_at'] = now();
        $estado_info = count($materiales) ? 1 : 0;

        DB::beginTransaction();
        DB::table('tb_order_servicio')->insert([
            'cod_ordens' => $request->n_orden,
            'cod_incidencia' => $request->codInc,
            'observaciones' => $request->obs,
            'recomendaciones' => $request->rec,
            'fecha_f' => $request->fecha_f,
            'hora_f' => $request->hora_f,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if (count($materiales))
        DB::table('tb_inc_asignadas')->insert($materiales);

        return $request;
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
