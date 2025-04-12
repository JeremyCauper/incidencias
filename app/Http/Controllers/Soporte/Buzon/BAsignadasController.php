<?php

namespace App\Http\Controllers\Soporte\Buzon;

use App\Helpers\Problema;
use App\Helpers\SubProblema;
use App\Helpers\TipoEstacion;
use App\Helpers\TipoIncidencia;
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
            $data['tipo_estacion'] = collect((new TipoEstacion())->all())->select('id', 'descripcion', 'estatus', 'eliminado')->keyBy('id');
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problema'] = collect((new Problema())->all())->select('id', 'codigo', 'descripcion', 'tipo_soporte', 'estatus')->keyBy('id');
            $data['sproblema'] = collect((new SubProblema())->all())->select('id', 'codigo_problema', 'descripcion', 'prioridad', 'estatus')->keyBy('id');

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
