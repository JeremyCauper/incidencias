<?php

namespace App\Http\Controllers\Buzon;

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
            $data['tipos_incidencia'] = DB::table('tb_tipo_incidencia')->get()->keyBy('id_tipo_incidencia');
            $data['problemas'] = DB::table('tb_problema')->get()->keyBy('id_problema');
            $data['subproblemas'] = DB::table('tb_subproblema')->get()->keyBy('id_subproblema');
            
            return view('buzon.resueltas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
