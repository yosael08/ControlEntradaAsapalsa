<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASAPALSA - Control de Entradas y Salidas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

<nav class="bg-green-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <span class="font-bold text-xl tracking-wider mr-8">ASAPALSA</span>

                    <div class="hidden md:flex items-baseline space-x-4">
                        @auth
                            @if(auth()->user()->rol === 'vigilante' || auth()->user()->rol === 'admin')
                                <a href="{{ route('cola-espera.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-green-600 transition">Cola de Espera</a>
                                <a href="{{ route('movimientos.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-green-600 transition">Entrada a Plantel</a>
                            @endif

                            @if(auth()->user()->rol === 'rampa' || auth()->user()->rol === 'admin')
                                <a href="{{ route('movimientos.rampa') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-green-600 transition">Módulo de Rampa</a>
                            @endif

                            @if(auth()->user()->rol === 'admin')
                                <a href="{{ route('usuarios.create') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-green-800 hover:bg-green-900 transition border border-green-600 shadow-sm">+ Registrar Empleado</a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="flex items-center space-x-4 text-sm font-medium">
                    @auth
                        <div>
                            Usuario: <span class="underline font-bold">{{ auth()->user()->name }}</span>
                            <span class="text-xs bg-green-900 px-2 py-0.5 rounded-full ml-1 capitalize text-green-300">({{ auth()->user()->rol }})</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs shadow transition">
                                Salir
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-white text-green-700 font-bold py-1 px-4 rounded text-xs hover:bg-gray-100 transition">Iniciar Sesión</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            @yield('contenido')
        </div>
    </main>

</body>
</html>
