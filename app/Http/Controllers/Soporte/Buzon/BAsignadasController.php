<?php

namespace App\Http\Controllers\Soporte\Buzon;

use App\Helpers\TipoEstacion;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BAsignadasController extends Controller
{
    public function view()
    {
        $this->validarPermisos(8, 11);
        try {
            $data = [];
            $data['empresas'] = DB::table('tb_empresas')->select('id', 'ruc', 'razon_social', 'contrato', 'direccion', 'codigo_aviso', 'status')->get()->keyBy('ruc');;
            $data['sucursales'] = DB::table('tb_sucursales')->select('id', 'ruc', 'nombre', 'direccion', 'status')->get()->keyBy('id');
            $data['tipo_estacion'] = collect((new TipoEstacion())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problemas'] = DB::table('tb_problema')->get()->keyBy('id_problema');
            $data['subproblemas'] = DB::table('tb_subproblema')->get()->keyBy('id_subproblema');

            $data['materiales'] = db::table('tb_materiales')->where('estatus', 1)->get()->map(function ($m) {
                return [
                    'value' => $m->id_materiales,
                    'dValue' => base64_encode(json_encode(['id_material' => $m->id_materiales, 'producto' => $m->producto, 'cantidad' => 0])),
                    'text' => $m->producto
                ];
            });
            $data['cod_orden'] = DB::select('CALL GetCodeOrds(?)', [date('y')])[0]->num_orden;
            $data['cod_ordenv'] = DB::select('CALL GetCodeOrdVis(?)', [date('y')])[0]->cod_orden;
            
            return view('soporte.buzon.asignadas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
