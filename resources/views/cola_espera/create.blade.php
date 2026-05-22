<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASAPALSA - Registrar Vehículo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-green-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <span class="text-xl font-bold tracking-wider">ASAPALSA</span>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('cola-espera.index') }}" class="hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium transition">Cola de Espera</a>
                        <a href="#" class="hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium transition">Entrada a Plantel</a>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm">
                        Usuario: <strong class="underline">Vigilante Prueba</strong>
                        <span class="bg-green-800 text-xs px-2 py-1 rounded ml-1 text-green-200">(Vigilante)</span>
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md text-sm font-semibold transition shadow-sm">
                            Salir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-2xl mx-auto px-4 py-8">
        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">

            <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-3 flex items-center gap-2">
                🚧 Registrar Nuevo Vehículo en Fila de Espera
            </h2>

            @if ($errors->any())
                <div class="mb-5 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-r shadow-sm">
                    <strong class="block font-semibold mb-1">Por favor corrige los siguientes campos:</strong>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cola-espera.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="Placa" class="block text-sm font-semibold text-gray-700 mb-1">Placa del Vehículo:</label>
                    <input type="text" name="Placa" id="Placa" value="{{ old('Placa') }}" placeholder="Ej: H AA 1898" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm text-base">
                </div>

                <div>
                    <label for="ID_TipoVehiculo" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Vehículo:</label>
                    <select name="ID_TipoVehiculo" id="ID_TipoVehiculo" required
                            class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm text-base">
                        <option value="">-- Seleccione el tipo de unidad --</option>
                        @foreach($tiposVehiculos as $tipo)
                            <option value="{{ $tipo->id }}" {{ old('ID_TipoVehiculo') == $tipo->id ? 'selected' : '' }}>
                                {{ $tipo->NombreVehiculos }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="NombreConductor" class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo del Conductor:</label>
                    <input type="text" name="NombreConductor" id="NombreConductor" value="{{ old('NombreConductor') }}" placeholder="Ej: Juan Ramón Pérez" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm text-base">
                </div>

                <div>
                    <label for="DniConductor" class="block text-sm font-semibold text-gray-700 mb-1">DNI del Conductor (13 dígitos numéricos):</label>
                    <input type="text" name="DniConductor" id="DniConductor" value="{{ old('DniConductor') }}" maxlength="13" placeholder="Ej: 0501199512345" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm text-base">
                </div>

                <div>
                    <label for="NombreProductor" class="block text-sm font-semibold text-gray-700 mb-1">Nombre del Productor / Finca:</label>
                    <input type="text" name="NombreProductor" id="NombreProductor" value="{{ old('NombreProductor') }}" placeholder="Ej: Azucarera Central" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm text-base">
                </div>

                <div>
                    <label for="NombreOrigen" class="block text-sm font-semibold text-gray-700 mb-1">Origen de Carga:</label>
                    <input type="text" name="NombreOrigen" id="NombreOrigen" value="{{ old('NombreOrigen') }}" placeholder="Ej: Desvío Sabá" required
                           class="w-full rounded-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm text-base">
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-end gap-3">
                    <a href="{{ route('cola-espera.index') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 font-semibold text-sm transition text-center shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-semibold text-sm transition shadow-sm">
                        Guardar en Fila
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
