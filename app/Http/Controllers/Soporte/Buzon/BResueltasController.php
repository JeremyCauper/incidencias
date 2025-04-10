<?php

namespace App\Http\Controllers\Soporte\Buzon;

use App\Helpers\Problema;
use App\Helpers\SubProblema;
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
            $data['tIncidencia'] = collect((new TipoIncidencia())->all())->select('id', 'descripcion', 'estatus')->keyBy('id');
            $data['problema'] = collect((new Problema())->all())->select('id', 'codigo', 'descripcion', 'tipo_soporte', 'estatus')->keyBy('id');
            $data['sproblema'] = collect((new SubProblema())->all())->select('id', 'codigo_problema', 'descripcion', 'prioridad', 'estatus')->keyBy('id');
            
            return view('soporte.buzon.resueltas', ['data' => $data]);
        } catch (Exception $e) {
            return $this->message(data: ['error' => $e->getMessage()], status: 500);
        }
    }
}
