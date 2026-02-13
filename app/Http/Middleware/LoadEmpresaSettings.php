<?php

namespace App\Http\Middleware;

use Closure;
use App\Http\Controllers\SettingsEmpresaController;

class LoadEmpresaSettings
{
    public function handle($request, Closure $next)
    {
        $settings = SettingsEmpresaController::get();

        if ($settings) {
            config([
                'ajustes.id_cliente' => $settings['id_cliente'] ?? 0,
                'ajustes.empresa' => $settings['empresa'] ?? '',
                'ajustes.config' => (object) $settings['config'] ?? [],
                'ajustes.rutaRedirect' => $settings['rutaRedirect'] ?? '/',
            ]);
        }

        return $next($request);
    }
}
