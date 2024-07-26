<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
    }

    /*public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return redirect('/soporte');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);

        $password = '123456';
        $hashedPassword = bcrypt($password);
        echo $hashedPassword;
    }*/

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

        // Validar si el usuario existe
        if (!User::where('usuario', $credentials['usuario'])->exists()) {
            return response()->json(['success' => false, 'message' => 'El usuario no existe'], 200);
        }

        // Intentar autenticar con las credenciales proporcionadas
        if (!Auth::attempt($credentials)) {
            return response()->json(['success' => false, 'message' => 'La contraseña es incorrecta'], 200);
        }

        // Regenerar la sesión para proteger contra fijación de sesión
        $request->session()->regenerate();

        // Autenticación exitosa
        return response()->json(['success' => true, 'message' => ''], 200);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/inicio');
    }
}
