<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function viewLogin()
    {
        return view('auth.login');
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

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();
            return redirect('/soporte');
        }

        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ]);

        /*$password = '123456';
        $hashedPassword = bcrypt($password);
        echo $hashedPassword;*/
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/inicio');
    }
}
