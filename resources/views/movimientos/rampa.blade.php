@extends('layouts.app')

@section('contenido')
<div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">

    <div class="flex items-center space-x-2 border-b pb-4 mb-6">
        <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15m0 0l3-3m-3 3l3 3m12 3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Módulo de Operaciones de Rampa</h1>
            <p class="text-sm text-gray-500">Gestión de descarga y despacho para los vehículos dentro de las instalaciones</p>
        </div>
    </div>

    @if(session('exito'))
        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded shadow-sm">
            <p class="text-sm font-semibold">{{ session('exito') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left">
            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-700">
                <tr>
                    <th class="px-6 py-3">Placa</th>
                    <th class="px-6 py-3">Tipo</th>
                    <th class="px-6 py-3">Conductor</th>
                    <th class="px-6 py-3">Procedencia (Origen)</th>
                    <th class="px-6 py-3 text-center">Estado Actual</th>
                    <th class="px-6 py-3 text-center">Acción Operativa</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($enRampa as $camion)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-lg">{{ $camion->Placa }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $camion->tipoVehiculo->NombreVehiculos ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                            {{ ($camion->conductor->NombreConductor ?? '') . ' ' . ($camion->conductor->ApellidoConductor ?? '') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $camion->origen->NombreOrigenes ?? 'N/A' }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($camion->Estado == 'En Plantel')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                    Dentro de Plantel
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-400 text-white shadow-sm">
                                    Descargando
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($camion->Estado == 'En Plantel')
                                <form action="{{ route('movimientos.descargar', $camion->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-1.5 px-4 rounded shadow-sm text-xs transition">
                                        Descargar Vehículo
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('movimientos.despachar', $camion->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-4 rounded shadow-sm text-xs transition">
                                        Despachar
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <p class="text-base font-medium">No hay vehículos dentro de las rampas o plantel en este momento.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
