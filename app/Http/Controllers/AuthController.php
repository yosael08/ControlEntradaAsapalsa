<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Procesa el intento de autenticación (Login por Username plano)
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Intentar autenticar con el nombre de usuario plano y contraseña
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirección inteligente según el rol asignado
            $user = Auth::user();
            if (in_array($user->rol, ['admin', 'vigilante'])) {
                return redirect()->route('cola-espera.index');
            }

            return redirect()->route('movimientos.rampa');
        }

        return back()->withErrors([
            'username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('username');
    }

    /**
     * Muestra el formulario para registrar un nuevo empleado
     * Corrección adaptativa: Busca todas las variantes de nombres posibles para evitar el error 500
     */
    /**
     * Muestra el formulario para registrar un nuevo empleado
     * Corregido apuntando al nombre exacto de tu estructura de archivos
     */
    public function showRegistrar()
    {
        // Apunta directamente a resources/views/auth/registrar-usuario.blade.php
        if (view()->exists('auth.registrar-usuario')) {
            return view('auth.registrar-usuario');
        }

        // Respaldo por seguridad si cambia en el futuro
        if (view()->exists('auth.register')) {
            return view('auth.register');
        }

        abort(404, "No se encontró la vista del formulario de registro.");
    }

    /**
     * Almacena el nuevo empleado en la base de datos SQL Server
     */
    public function registrar(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|string|in:admin,vigilante,rampa',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password), // Encriptación segura Bcrypt
            'rol' => $request->rol,
        ]);

        return redirect()->route('cola-espera.index')->with('exito', '¡Empleado registrado correctamente!');
    }

    /**
     * Cierra la sesión activa de forma segura
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
