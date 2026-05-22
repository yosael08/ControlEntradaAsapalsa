@extends('layouts.app')

@section('contenido')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200">

    <div class="border-b pb-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Registrar Nuevo Empleado</h1>
        <p class="text-sm text-gray-500">Cree las credenciales de acceso y asigne el rol correspondiente en el plantel</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 mb-4 rounded text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Ej: Juan Pérez"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
        </div>
<div>
    <label for="username" class="block text-sm font-semibold text-gray-700 mb-1">Nombre de Usuario (Para loguearse)</label>
    <input type="text" name="username" id="username" required placeholder="Ej: rampa_turno1"
           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
</div>

       <div>
    <label class="block text-sm font-medium text-gray-700 mb-1">Rol del Sistema</label>
    <select name="rol" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500" required>
        <option value="" disabled selected>-- Selecciona un Rol --</option>
        <option value="admin">Administrador (Acceso Total)</option>
        <option value="vigilante">Vigilante (Portón / Cola Espera)</option>
        <option value="rampa">Operador de Rampa (Descargas)</option>
    </select>
</div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Contraseña Temporal</label>
            <input type="password" name="password" id="password" required placeholder="Mínimo 6 caracteres"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Repita la contraseña"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition">
        </div>

        <div class="flex items-center justify-end space-x-4 pt-4 border-t">
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded shadow transition">
                Registrar Empleado
            </button>
        </div>
    </form>
</div>
@endsection
