<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\TipoUsuario;
use App\Http\Controllers\Controller;
use App\Models\User;
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

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
        ];

        if (!User::where('usuario', $credentials['usuario'])->exists())
            return response()->json(['success' => false, 'message' => 'El usuario no existe'], 200);

        if (!Auth::attempt($credentials))
            return response()->json(['success' => false, 'message' => 'La contraseña es incorrecta'], 200);

        $menu_usuario = Auth::user()->menu_usuario;
        $turno_fin = $this->validarTurno(Auth::user()->id_usuario);
        if (Auth::user()->tipo_acceso == 3 && !empty($turno_fin)) {
            $menu_usuario = 'eyIxIjpbXSwiMiI6W10sIjMiOlsiMSIsIjIiXSwiOCI6WyIxMSIsIjEyIl19';
        }

        $modulos = $this->obtenerModulos($menu_usuario, Auth::user()->tipo_acceso);
        $nomPerfil = $this->formatearNombre(Auth::user()->nombres, Auth::user()->apellidos);
        $text_acceso = (new TipoUsuario())->show(Auth::user()->tipo_acceso)['descripcion'];

        $foto_perfil = empty(Auth::user()->foto_perfil) ? Auth::user()->foto_perfil : 'user_auth.jpg';

        session([
            'customModulos' => $modulos->menus,
            'rutaRedirect' => $modulos->ruta,
            'id_usuario' => Auth::user()->id_usuario,
            'tipo_acceso' => Auth::user()->tipo_acceso,
            'menu_usuario' => $menu_usuario,
            'turno_fin' => $turno_fin,
            'config_layout' => (object)[
                'text_acceso' => $text_acceso ?? null,
                'nombre_perfil' => $nomPerfil ?? null,
                'foto_perfil' => secure_asset('front/images/auth/' . $foto_perfil),
            ]
        ]);
        $request->session()->regenerate();

        // Autenticación exitosa
        return response()->json(['success' => true, 'message' => '', 'data' => $modulos->ruta], 200);
    }

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

    public function logout(Request $request)
    {  
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        session()->forget(['customModulos', 'rutaRedirect', 'id_usuario', 'tipo_acceso', 'menu_usuario', 'turno_fin', 'config_layout']);
        return redirect('/soporte');
    }
}
