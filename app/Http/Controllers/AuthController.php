<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Muestra el formulario de Login
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return $this->redireccionarPorRol();
        }
        return view('auth.login');
    }

    // Procesa el inicio de sesión
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credenciales)) {
            $request->session()->regenerate();
            return $this->redireccionarPorRol();
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Redirección inteligente según el rol del empleado
    private function redireccionarPorRol()
    {
        $rol = Auth::user()->rol;

        if ($rol === 'rampa') {
            return redirect()->route('movimientos.rampa');
        }

        // Si es vigilante o admin, van por defecto a la cola de espera
        return redirect()->route('cola-espera.index');
    }

    // Cerrar Sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // PANTALLA ADMIN: Formulario para registrar usuarios nuevos
    public function mostrarRegistro()
    {
        // Guardura de seguridad extrema: solo el admin entra aquí
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permisos de Administrador para crear usuarios.');
        }
        return view('auth.registrar-usuario');
    }

    // PANTALLA ADMIN: Guarda el nuevo usuario en la BD
    public function registrar(Request $request)
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:admin,vigilante,rampa',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return redirect()->route('cola-espera.index')->with('exito', 'Nuevo usuario creado correctamente.');
    }
}
