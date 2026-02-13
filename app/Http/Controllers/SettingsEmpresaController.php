<?php

namespace App\Http\Controllers;

use App\Services\JsonDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsEmpresaController extends Controller
{
    protected static function bootSchema()
    {
        JsonDB::schema('ajustes_empresa', [
            'id' => 'int|primary_key|auto_increment',
            'id_cliente' => 'int|default:0',
            'settings' => 'array',
        ]);
    }

    public static function set($settings)
    {
        self::bootSchema();
        $id_cliente = Auth::guard('client')->user()->id;

        JsonDB::table('ajustes_empresa')->where('id_cliente', $id_cliente)->delete();

        JsonDB::table('ajustes_empresa')->insert([
            'id_cliente' => $id_cliente,
            'settings' => $settings,
        ]);
    }

    public static function get()
    {
        if (!Auth::guard('client')->check()) {
            return;
        }
        self::bootSchema();
        $response = JsonDB::table('ajustes_empresa')->where('id_cliente', Auth::guard('client')->user()->id)->first();

        if ($response) {
            return $response->settings;
        }

        return null;
    }
}