<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SettingsEmpresaController;
use Auth;
use Closure;
use App\Http\Controllers\SettingsController;

class LoadUserSettings
{
    public function handle($request, Closure $next)
    {
        $cliente = Auth::guard('client')->check() ? true : false;
        $settings = $cliente ? SettingsEmpresaController::get() : SettingsController::get();

        if ($settings) {
            if ($cliente) {
                config([
                    'ajustes.customModulos' => (object) [
                        [
                            "id" => 1,
                            "descripcion" => "Incidencias",
                            "icon" => "fas fa-house",
                            "ruta" => "/empresa/incidencias",
                            "submenu" => [],
                            "sistema" => 0,
                            "orden" => 1,
                            "estatus" => 1
                        ],
                        [
                            "id" => 2,
                            "descripcion" => "Análisis Incidencias",
                            "icon" => "fas fa-chart-column",
                            "ruta" => "/empresa/dashboard/dashboard-incidencias",
                            "submenu" => [],
                            "sistema" => 0,
                            "orden" => 2,
                            "estatus" => 1
                        ],
                    ],
                    'ajustes.id_cliente' => $settings['id_cliente'] ?? 0,
                    'ajustes.empresa' => $settings['empresa'] ?? '',
                    'ajustes.config' => (object) $settings['config'] ?? [],
                    'ajustes.rutaRedirect' => $settings['rutaRedirect'] ?? '/',
                ]);
            } else {
                config([
                    'ajustes.customModulos' => (object) $settings['customModulos'] ?? [],
                    'ajustes.tipo_acceso' => $settings['tipo_acceso'] ?? 0,
                    'ajustes.menu_usuario' => $settings['menu_usuario'] ?? '',
                    'ajustes.config' => (object) $settings['config'] ?? [],
                    'ajustes.rutaRedirect' => $settings['rutaRedirect'] ?? '/',
                ]);
            }
        }

        return $next($request);
    }
}
