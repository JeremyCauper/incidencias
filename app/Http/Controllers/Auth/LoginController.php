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
        DB::purge('dynamic');


        $credentials = [
            'usuario' => $request->input('usuario'),
            'password' => $request->input('password'),
        ];

        if (!User::where('usuario', $credentials['usuario'])->exists())
            return response()->json(['success' => false, 'message' => 'El usuario no existe'], 200);

        if (!Auth::attempt($credentials))
            return response()->json(['success' => false, 'message' => 'La contraseña es incorrecta'], 200);

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
