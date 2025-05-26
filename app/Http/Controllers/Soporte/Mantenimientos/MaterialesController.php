<?php

namespace App\Http\Controllers\Soporte\Mantenimientos;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialesController extends Controller
{
    public function MaterialesUsados()
    {
        try {
            $materiales = DB::table('tb_materiales')->get()->keyBy('id_materiales');
            $ordenes = DB::table('tb_orden_servicio')->select('cod_ordens', 'cod_incidencia', 'fecha_f', 'hora_f')->get()->keyBy('cod_ordens');
            $indicencias = DB::table('tb_incidencias')->select('cod_incidencia', 'ruc_empresa', 'id_sucursal')->get()->keyBy('cod_incidencia');
            $sucursales = DB::table('tb_sucursales')->select('id', 'nombre')->get()->keyBy('id');
            $empresas = DB::table('tb_empresas')->select('ruc', 'razon_social')->get()->keyBy('ruc');

            $materiales_usuados = DB::table('tb_materiales_usados')->get()->map(function ($val) use ($ordenes, $indicencias, $sucursales, $empresas, $materiales) {
                $orden = $ordenes[$val->cod_ordens];
                $indicencia = $indicencias[$orden->cod_incidencia];
                $sucursal = $sucursales[$indicencia->id_sucursal];
                $empresa = $empresas[$indicencia->ruc_empresa];
                return [
                    'ruc_empresa' => $indicencia->ruc_empresa,
                    'razonSocial' => $empresa->razon_social,
                    'sucursal' => $sucursal->nombre,
                    'des_material' => $materiales[$val->id_material]->producto,
                    'cantidad' => $val->cantidad,
                    'fecha_orden' => "$orden->fecha_f $orden->hora_f",
                    'cod_orden' => $val->cod_ordens,
                    'cod_incidencia' => $orden->cod_incidencia,
                ];
            });

            return $materiales_usuados;
        } catch (Exception $e) {
            return $this->message(message: "Ocurrió un error interno en el servidor.", data: ['error' => $e->getCode(), 'linea' => $e->getLine()], status: 500);
        }
    }
}
