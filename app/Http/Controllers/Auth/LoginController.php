<?php

namespace App\Http\Controllers\Auth;

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

        session([
            'customModulos' => $modulos->menus,
            'rutaRedirect' => $modulos->ruta,
            'nomPerfil' => $nomPerfil,
            'id_usuario' => Auth::user()->id_usuario,
            'tipo_acceso' => Auth::user()->tipo_acceso,
            'menu_usuario' => $menu_usuario,
            'turno_fin' => $turno_fin
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
        Auth::logout();
        session()->forget(['customModulos', 'rutaRedirect', 'nomPerfil', 'id_usuario', 'tipo_acceso', 'menu_usuario', 'turno_fin']);
        return redirect('/soporte');
    }
}
