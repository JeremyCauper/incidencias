<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\TipoUsuario;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SettingsEmpresaController;
use App\Models\User;
use App\Models\UsuarioEmpresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function view()
    {
        try {
            return view('auth.login');
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    /* =====================================
    |             Login Soporte             |
    ====================================== */
    public function loginSoporte(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials))
            return response()->json(['success' => false, 'message' => 'Las credenciales ingresadas son incorrectas.'], 200);

        $menu_usuario = Auth::user()->menu_usuario;
        $turno_fin = $this->validarTurno(Auth::user()->id_usuario);
        if (Auth::user()->tipo_acceso == 3 && !empty($turno_fin)) {
            $menu_usuario = 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiOCI6WyIxMSIsIjEyIl19';
        }

        $modulos = $this->obtenerModulos($menu_usuario, Auth::user()->tipo_acceso);
        $nomPerfil = $this->formatearNombre(Auth::user()->nombres, Auth::user()->apellidos);
        $text_acceso = (new TipoUsuario())->show(Auth::user()->tipo_acceso)['descripcion'];

        $foto_perfil = empty(Auth::user()->foto_perfil) ? 'user_auth.jpg' : Auth::user()->foto_perfil;

        $request->session()->regenerate();
        $data = [
            'customModulos' => $modulos->menus,
            'rutaRedirect' => $modulos->ruta,
            'id_usuario' => Auth::user()->id_usuario,
            'tipo_acceso' => Auth::user()->tipo_acceso,
            'menu_usuario' => $menu_usuario,
            'turno_fin' => $turno_fin,
            'config' => (object)[
                'acceso' => $text_acceso ?? null,
                'nombre_perfil' => $nomPerfil ?? null,
                'foto_perfil' => secure_asset("front/images/auth/$foto_perfil"),
            ]
        ];
        SettingsController::set($data);

        // Autenticación exitosa
        return response()->json(['success' => true, 'message' => '', 'data' => $modulos->ruta], 200);
    }

    public function logoutSoporte(Request $request)
    {  
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    /* =====================================
    |             Login Cliente             |
    ====================================== */
    public function loginCliente(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
        ];

        // Utilizar el guard 'client' para la autenticación
        if (!Auth::guard('client')->attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'Las credenciales ingresadas son incorrectas.'], 200);
        }

        // Recuperar al cliente autenticado
        $client = Auth::guard('client')->user();
        $empresa = DB::table('tb_empresas')->select(['id', 'ruc', 'razon_social', 'direccion', 'contrato', 'codigo_aviso', 'status'])->where('ruc', $client->ruc_empresa)->first();

        $request->session()->regenerate();
        // Aquí puedes establecer la sesión o cualquier otra lógica que necesites para el cliente
        $data = [
            'rutaRedirect' => '/empresa/incidencias',
            'id_cliente' => $client->id,
            'empresa' => $empresa,
            'config' => (object) [
                'acceso' => "Cliente" ?? null,
                'nombre_perfil' => "$empresa->ruc - $empresa->razon_social" ?? null,
                'foto_perfil' => secure_asset('front/images/auth/user_auth.jpg'),
            ]
        ];
        SettingsEmpresaController::set($data);

        // Autenticación exitosa
        return response()->json(['success' => true, 'message' => '', 'data' => '/empresa/incidencias'], 200);
    }

    public function logoutCliente(Request $request)
    {
        Auth::guard('client')->logout(); // O el nombre del guard que uses para UsuarioEmpresa
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    /* =====================================
    |             Validar Turno             |
    ====================================== */
    public function validarTurno(string $id)
    {
        $fechaActual = now()->format('Y-m-d H:i:s');
        $tapoyo = DB::table('tb_cronograma_turno')
            ->where(['eliminado' => 0, 'personal_a' => $id])
            ->whereRaw('? BETWEEN CONCAT(fecha_ini_a, " ", hora_ini_a) AND CONCAT(fecha_fin_a, " ", hora_fin_a)', [$fechaActual])
            ->first();
        $respuesta = "";
        if (!empty($tapoyo)) {
            $fechaInicio = "$tapoyo->fecha_ini_a $tapoyo->hora_ini_a";
            $fechaFin = "$tapoyo->fecha_fin_a $tapoyo->hora_fin_a";
            $fechaActual = now()->format('Y-m-d H:i:s');

            if (strtotime($fechaActual) >= strtotime($fechaInicio) && strtotime($fechaActual) <= strtotime($fechaFin)) {
                $respuesta = $fechaFin;
            }
        }
        return $respuesta;
    }
}
