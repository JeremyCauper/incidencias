<?php

namespace App\Http\Controllers\Soporte\Buzon;

use App\Helpers\TipoIncidencia;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BResueltasController extends Controller
{
    public function view()
    {
        $this->validarPermisos(8, 12);
        try {
            $data = [];

            $data['empresas'] = DB::table('tb_empresas')->select('id', 'ruc', 'razon_social', 'contrato', 'direccion', 'status')->get()->keyBy('ruc');;
            $data['sucursales'] = DB::table('tb_sucursales')->select('id', 'ruc', 'nombre', 'direccion', 'status')->get()->keyBy('id');
            $data['tipos_incidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problemas'] = DB::table('tb_problema')->get()->keyBy('id_problema');
            $data['subproblemas'] = DB::table('tb_subproblema')->get()->keyBy('id_subproblema');
            
            return view('soporte.buzon.resueltas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
