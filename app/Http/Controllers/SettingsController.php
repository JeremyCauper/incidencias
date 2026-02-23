<?php

namespace App\Http\Controllers;

use App\Services\JsonDB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    protected static function bootSchema()
    {
        JsonDB::schema('ajustes', [
            'id' => 'int|primary_key|auto_increment',
            'id_usuario' => 'int|default:0',
            'settings' => 'array',
        ]);
    }

    public static function set($settings)
    {
        self::bootSchema();
        $id_usuario = Auth::user()->id_usuario;

        JsonDB::table('ajustes')->where('id_usuario', $id_usuario)->delete();

        JsonDB::table('ajustes')->insert([
            'id_usuario' => $id_usuario,
            'settings' => $settings,
        ]);
    }

    public static function get()
    {
        if (!Auth::check()) {
            return;
        }
        self::bootSchema();
        $response = JsonDB::table('ajustes')->where('id_usuario', Auth::user()->id_usuario)->first();

        if ($response) {
            return $response->settings;
        }

        return null;
    }
}
