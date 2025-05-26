<?php

namespace App\Http\Controllers\Soporte\Mantenimientos;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\Command\WhereamiCommand;

class MaterialesController extends Controller
{
    public function MaterialesUsados(Request $request)
    {
        try {
            $fechaIni = $request->query('fechaIni') ?: now()->format('Y-m-01');
            $fechaFin = $request->query('fechaFin') ?: now()->format('Y-m-d');

            $materiales = DB::table('tb_materiales')->get()->keyBy('id_materiales');
            $ordenes = DB::table('tb_orden_servicio')->select('cod_ordens', 'cod_incidencia', 'fecha_f', 'hora_f')
                ->whereBetween('fecha_f', [$fechaIni, $fechaFin])
                ->get()->keyBy('cod_ordens');
            $indicencias = DB::table('tb_incidencias')->select('cod_incidencia', 'ruc_empresa', 'id_sucursal')->get()->keyBy('cod_incidencia');
            $sucursales = DB::table('tb_sucursales')->select('id', 'nombre')->get()->keyBy('id');
            $empresas = DB::table('tb_empresas')->select('ruc', 'razon_social')->get()->keyBy('ruc');

            $materiales_usuados = DB::table('tb_materiales_usados')->get()
                ->filter(fn($val) => isset($ordenes[$val->cod_ordens])) // Filtra solo las ordenes que existen por el rango de fecha
                ->map(function ($val) use ($ordenes, $indicencias, $sucursales, $empresas, $materiales) {
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

            return $this->message(data: ['data' => $materiales_usuados ]);
        } catch (Exception $e) {
            return $this->message(message: "Ocurrió un error interno en el servidor.", data: ['error' => $e->getCode(), 'linea' => $e->getLine()], status: 500);
        }
    }
}
