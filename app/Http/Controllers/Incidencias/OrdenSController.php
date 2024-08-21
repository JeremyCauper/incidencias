<?php

namespace App\Http\Controllers\Incidencias;

use App\Helpers\GlobalHelper;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
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
        try {
            $validator = Validator::make($request->all(), [
                'n_orden' => 'required|string',
                'obs' => 'required|string',
                'rec' => 'required|string',
                'fecha_f' => 'required|date',
                'hora_f' => 'required|date_format:H:i:s',
                'materiales' => 'required|array',
                'firma_digital' => 'nullable|string',
                'n_doc' => 'nullable|integer',
                'nom_cliente' => 'nullable|string',
            ]);

            if ($validator->fails())
                return response()->json(['errors' => $validator->errors()], 400);

            $materiales = $request->materiales;
            foreach ($materiales as $k => $val) {
                $materiales[$k]['updated_at'] = now();
                $materiales[$k]['created_at'] = now();
            }
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
                DB::table('tb_materiales_usados')->insert($materiales);

            if ($request->n_doc || $request->nom_cliente) {
                $dataContact = [
                    'nro_doc' => $request->n_doc,
                    'nombre_cliente' => $request->nom_cliente,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                if ($request->firma_digital) {
                    $result = GlobalHelper::parseCreateFile("fdc_{$request->n_doc}", 'client', $request->firma_digital); //$this->parseFile('fp_' . $request->usuario, 'auth', $request->foto_perfil);
                    if (!$result['success']) {
                        return response()->json(['success' => false, 'message' => 'Error al intentar crear la imagen del perfil'], 500);
                    }
                    $dataContact['firma_digital'] = $result['filename'];
                }
                DB::table('tb_contac_ordens')->insert($dataContact);
            }

            DB::table('tb_incidencias')->where('cod_incidencia', $request->codInc)->update(['estado_informe' => 3]);

            DB::commit();
            GlobalHelper::getIncDataTable(true);

            return response()->json([
                'success' => true,
                'message' => 'Orden de servicio generado'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al generar el orden de servio: ' . $e->getMessage()
            ], 500);
        }
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

    public function generarPDF()
    {
        $data = [
            'title' => 'ST24-00000001',
            'cod_orden' => 'ST24-00000001',
        ];
        $pdf = Pdf::loadView('pdf.orden_servicio', $data);
        return $pdf->stream('archivo.pdf');
    }
}
