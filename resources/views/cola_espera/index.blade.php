@extends('layouts.app')

@section('contenido')
<div class="bg-white p-6 rounded-lg shadow-md">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between border-b pb-4 mb-6">
        <div>
            <div class="flex items-center space-x-2">
                <svg class="w-7 h-7 text-yellow-600 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6M4.5 3.75h15M4.5 20.25h15M6 3.75a6 6 0 0112 0v2.25a3 3 0 01-3 3h-6a3 3 0 01-3-3V3.75zm12 16.5a6 6 0 01-12 0v-2.25a3 3 0 013-3h6a3 3 0 013 3v2.25z"></path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-800">Control de Cola de Espera</h1>
            </div>
            <p class="text-sm text-gray-500 mt-1">Listado de vehículos formados en el portón exterior pendientes de ingreso</p>
        </div>
        <div class="mt-4 md:mt-0">
            <a href="{{ route('cola-espera.create') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded shadow transition duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Registrar Vehículo
            </a>
        </div>
    </div>
    @if(session('exito'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
            <p class="font-bold">¡Operación Exitosa!</p>
            <p class="text-sm">{{ session('exito') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600">
            <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-700 tracking-wider">
                <tr>
                    <th class="px-6 py-3">Fecha / Hora</th>
                    <th class="px-6 py-3">Placa</th>
                    <th class="px-6 py-3">Tipo Vehículo</th>
                    <th class="px-6 py-3">Conductor</th>
                    <th class="px-6 py-3">Productor</th>
                    <th class="px-6 py-3">Origen</th>
                    <th class="px-6 py-3 text-center">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($vehiculosEnEspera as $vehiculo)
                    <tr class="hover:bg-gray-50 transition duration-100">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                            {{ \Carbon\Carbon::parse($vehiculo->fecha_registro)->format('d/m/Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700 tracking-wide">
                            {{ $vehiculo->Placa }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $vehiculo->tipoVehiculo->NombreVehiculos ?? 'No asignado' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ ($vehiculo->conductor->NombreConductor ?? '') . ' ' . ($vehiculo->conductor->ApellidoConductor ?? 'N/A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $vehiculo->productor->NombreProductores ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $vehiculo->origen->NombreOrigenes ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                                {{ $vehiculo->Estado }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            <p class="text-base font-medium">No hay vehículos registrados en la cola actualmente</p>
                            <p class="text-xs mt-1">Los registros nuevos que agregues desde el portón aparecerán aquí.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
