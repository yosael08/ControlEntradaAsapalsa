<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASAPALSA - Fila de Espera</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <nav class="bg-green-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <span class="text-xl font-bold tracking-wider">ASAPALSA</span>
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('cola-espera.index') }}" class="bg-green-800 px-3 py-2 rounded-md text-sm font-medium transition">Cola de Espera</a>
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

    <main class="max-w-7xl mx-auto px-4 py-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-800 rounded-r shadow-md font-semibold">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 mb-6 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    🚧 Módulo de Vigilancia: Fila de Espera
                </h1>
                <p class="text-sm text-gray-500 mt-1">Control vehicular externo y monitoreo de capacidades - ASAPALSA</p>
            </div>

            @php
                $rutaDestino = Route::has('cola-espera.create') ? route('cola-espera.create') :
                               (Route::has('cola-espera.crear') ? route('cola-espera.crear') : url('/cola-espera/crear'));
            @endphp

            <a href="{{ $rutaDestino }}"
               class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-3 rounded-md transition shadow-md flex items-center gap-2 text-sm tracking-wide">
                ➕ REGISTRAR NUEVO VEHÍCULO
            </a>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-sm font-bold uppercase tracking-wider text-gray-600">
                    🔸 UNIDADES EN ESPERA EXTERNA (FILA EN CARRETERA)
                </h2>
                <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-bold">
                    {{ isset($colaEspera) ? count($colaEspera) : 0 }} Vehículos
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 text-xs font-semibold uppercase border-b border-gray-200">
                            <th class="px-6 py-3">Placa</th>
                            <th class="px-6 py-3">Tipo Unidad</th>
                            <th class="px-6 py-3">Conductor</th>
                            <th class="px-6 py-3">Productor / Finca</th>
                            <th class="px-6 py-3">Origen</th>
                            <th class="px-6 py-3">Fecha / Hora</th>
                            <th class="px-6 py-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 divide-y divide-gray-200">
                        @forelse($colaEspera as $fila)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-mono font-bold text-gray-900">
                                    {{ $fila->Placa ?? $fila->PLACA }}
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ $fila->tipoVehiculo->NombreVehiculos ?? $fila->NombreVehiculos ?? 'Unidad No Definida' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $fila->conductor->NombreConductor ?? $fila->NombreConductor ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $fila->productor->NombreProductores ?? $fila->NombreProductor ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $fila->origen->NombreOrigenes ?? $fila->NombreOrigenes ?? $fila->NombreOrigen ?? 'N/A' }}
                                </td>

                                <td class="px-6 py-4 text-xs font-mono font-semibold text-gray-700">
                                    @if(!empty($fila->created_at))
                                        {{ \Carbon\Carbon::parse($fila->created_at)->format('d/m/Y h:i:s A') }}
                                    @elseif(!empty($fila->fecha_registro))
                                        {{ \Carbon\Carbon::parse($fila->fecha_registro)->format('d/m/Y h:i:s A') }}
                                    @else
                                        {{ \Carbon\Carbon::now()->format('d/m/Y h:i:s A') }}
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-bold rounded shadow-sm">
                                        En Espera
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <div class="text-2xl mb-2">📬</div>
                                    No hay vehículos en la fila exterior actualmente.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

</body>
</html>
