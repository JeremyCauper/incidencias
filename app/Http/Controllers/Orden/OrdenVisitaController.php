<?php

namespace App\Http\Controllers\Orden;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrdenVisitaController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Validación inicial
        $validator = Validator::make($request->all(), [
            'cod_ordenv' => 'required|string',
            'id_visita_orden' => 'required|integer'
        ]);

        if ($validator->fails())
            return $this->message(data: ['required' => $validator->errors()], status: 422);

        $request_filas_visitas = $request->all();
        unset($request_filas_visitas['cod_ordenv']);
        unset($request_filas_visitas['id_visita_orden']);

        $filas_visitas = collect($request_filas_visitas)->map(function ($item, $key) use ($request) {
            return [
                'cod_orden_visita' => $request->cod_ordenv,
                'posicion' => $key,
                'checked' => empty($item) ? false : true,
                'descripcion' => $item,
                'created_at' => now()->format('Y-m-d H:i:s')
            ];
        })->values(); // Esto reinicia los índices del array

        $islas_visitas = collect($request->islas)->map(function ($item, $key) use ($request) {
            return [
                'cod_orden_visita' => $key,
                'isla' => $item,
                /*'pos' => $item->pos,
                'impresoras' => empty($item->impresoras) ? false : true,
                'des_impresoras' => $item->impresoras,
                'lectores' => empty($item->red_lectores) ? false : true,
                'des_lector' => $item->red_lectores,
                'jack' => empty($item->jack_tools) ? false : true,
                'des_jack' => $item->jack_tools,
                'voltaje' => empty($item->voltaje) ? false : true,
                'des_voltaje' => $item->voltaje,
                'caucho' => empty($item->caucho_protector) ? false : true,
                'des_caucho' => $item->caucho_protector,
                'mueblepos' => empty($item->mueble_pos) ? false : true,
                'des_mueblepos' => $item->mueble_pos,
                'mr350' => empty($item->terminales) ? false : true,
                'des_mr350' => $item->terminales,
                //'switch' => $item,
                //'des_switch' => $item->,
                'created_at' => now()->format('Y-m-d H:i:s')*/
            ];
        })->values(); 

        return $islas_visitas;
        DB::beginTransaction();
        DB::table('tb_orden_visita_correlativo')->insert([
            'cod_orden_visita' => $request->cod_ordenv,
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        DB::table('tb_orden_visita')->insert([
            'cod_orden_visita' => $request->cod_ordenv,
            'id_visita' => $request->id_visita_orden,
            'fecha_visita' => now()->format('Y-m-d'),
            'hora_inicio' => now()->format('H:i:s'),
            'hora_fin' => now()->format('H:i:s'),
            'created_at' => now()->format('Y-m-d H:i:s')
        ]);

        DB::table('tb_orden_visita_filas')->insert($filas_visitas);
        DB::table('tb_orden_visita_islas')->insert($islas_visitas);




        $codOrdenS = DB::select("CALL GetCodeOrdVis(25)")[0]->cod_orden;
        DB::commit();

        return $request->all();
    }
}
