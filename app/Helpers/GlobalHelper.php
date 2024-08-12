<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GlobalHelper
{
    protected static $__url = 'https://cpe.apufact.com/portal/public/api/ListarInformacion?token=UVZCVlJrRkRWREl3TWpRPQ==&tabla=';

    public static function getCompany($vaciar = false)
    {
        $company = Cache::get('company');

        if ($vaciar || $company === null) {
            $company = json_decode(file_get_contents(self::$__url . 'empresas'));
            Cache::forever('company', $company);
        }
        return $company;
    }

    public static function getBranchOffice($vaciar = false)
    {
        $bOffice = Cache::get('bOffice');

        if ($vaciar || $bOffice === null) {
            $bOffice = json_decode(file_get_contents(self::$__url . 'sucursales'));
            Cache::forever('bOffice', $bOffice);
        }
        return $bOffice;
    }

    public static function getGroups($vaciar = false)
    {
        $groups = Cache::get('groups');

        if ($vaciar || $groups === null) {
            $groups = json_decode(file_get_contents(self::$__url . 'grupos'));
            Cache::forever('groups', $groups);
        }
        return $groups;
    }

    public static function getCargoContact($vaciar = false)
    {
        $cargo_contaco = self::fechTable($vaciar, 'cargo_contaco', 'cargo_contacto', ['id_cargo as id', 'descripcion', 'estatus']);
        return $cargo_contaco;

    }

    public static function getTipEstacion($vaciar = false)
    {
        $tipo_estacion = self::fechTable($vaciar, 'tipo_estacion', 'tb_tipo_estacion', ['id_tipo_estacion as id', 'descripcion', 'estatus']);
        return $tipo_estacion;
    }

    public static function getTipSoporte($vaciar = false)
    {
        $tipo_soporte = self::fechTable($vaciar, 'tipo_soporte', 'tb_tipo_soporte', ['id_tipo_soporte as id', 'descripcion', 'estatus']);
        return $tipo_soporte;
    }

    public static function getTipIncidencia($vaciar = false)
    {
        $tipo_incidencia = self::fechTable($vaciar, 'tipo_incidencia', 'tb_tipo_incidencia', ['id_tipo_incidencia as id', 'descripcion', 'estatus']);
        return $tipo_incidencia;
    }

    public static function getProblema($vaciar = false)
    {
        $problema = self::fechTable($vaciar, 'problema', 'tb_problema', ['id_problema as id', 'tipo_incidencia', 'codigo', 'descripcion', DB::raw("CONCAT(codigo, ' - ', descripcion) AS text"), 'estatus']);
        return $problema;
    }

    public static function getSubProblema($vaciar = false)
    {
        $subproblema = self::fechTable($vaciar, 'subproblema', 'tb_subproblema', ['id_subproblema as id', 'id_problema', 'codigo_sub', 'descripcion', DB::raw("CONCAT(codigo_sub, ' - ', descripcion) AS text"), 'estatus']);
        return $subproblema;
    }

    public static function getMateriales($vaciar = false)
    {
        $materiales = self::fechTable($vaciar, 'materiales', 'tb_materiales', ['id_materiales as id', 'producto', 'estatus']);
        return $materiales;
    }

    public static function getUsuarios($vaciar = false)
    {
        $data = Cache::get('usuarios');

        if ($vaciar || $data === null) {
            $data = DB::table('usuarios')->where('estatus', 1)->get();
            Cache::forever('usuarios', $data);
        }
        return $data;
    }

    public static function getIncDataTable($vaciar = false)
    {
        $inc_datatable = self::fechTable($vaciar, 'inc_datatable', 'tb_incidencias', ['cod_incidencia', 'id_empresa', 'id_sucursal', 'created_at', 'id_tipo_estacion', 'id_tipo_incidencia', 'id_problema', 'id_subproblema', 'estado_informe', 'id_incidencia as acciones', 'estatus']);
        return $inc_datatable;
    }





    public static function fechTable($reset, $Ncache, $Ntable, $columns) {
        $data = Cache::get($Ncache);

        if ($reset || $data === null) {
            $data = DB::table($Ntable)->select($columns)->where('estatus', 1)->get();
            Cache::forever($Ncache, $data);
        }
        return $data;
    }

    public static function gDate($date = 'Y-m-d H:i:s')
    {
        date_default_timezone_set("America/Lima");
        switch ($date) {
            case 'D':
                $date = 'Y-m-d';
                break;

            case 'T':
                $date = 'H:i:s';
                break;
        }
        return date($date);
    }
}