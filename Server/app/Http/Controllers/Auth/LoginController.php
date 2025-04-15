<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Maneja el intento de inicio de sesión
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirigir según el rol del usuario
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'recolector':
                    return redirect()->intended('/recolector/dashboard');
                case 'cliente':
                    return redirect()->intended('/cliente/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión del usuario
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
} 