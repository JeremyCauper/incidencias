<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SettingsEmpresaController;
use App\Models\UsuarioEmpresa;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginEmpresaController extends Controller
{
    public function view()
    {
        try {
            return view('auth.loginEmpresa');
        } catch (Exception $e) {
            return $this->mesageError(exception: $e, codigo: 500);
        }
    }

    public function loginClient(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
        ];

        // Verificar si el usuario existe en la tabla tb_clientes
        if (!UsuarioEmpresa::where('usuario', $credentials['usuario'])->exists()) {
            return response()->json(['success' => false, 'message' => 'El cliente no existe'], 200);
        }

        // Utilizar el guard 'client' para la autenticación
        if (!Auth::guard('client')->attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'La contraseña es incorrecta'], 200);
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

    public function logout(Request $request)
    {
        Auth::guard('client')->logout(); // O el nombre del guard que uses para UsuarioEmpresa
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/empresa');
    }

}
