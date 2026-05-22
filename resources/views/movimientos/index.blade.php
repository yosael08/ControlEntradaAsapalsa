@extends('layouts.app')

@section('contenido')
<div class="space-y-10">

    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
        <div class="border-b pb-4 mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <span class="w-3 h-3 bg-green-500 rounded-full animate-ping"></span>
                Vehículos Actualmente Dentro del Plantel
            </h1>
            <p class="text-sm text-gray-500">Control operativo en tiempo real de unidades en proceso de descarga o espera interna</p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-700 tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Hora Entrada</th>
                        <th class="px-6 py-3">Placa</th>
                        <th class="px-6 py-3">Tipo Vehículo</th>
                        <th class="px-6 py-3">Conductor</th>
                        <th class="px-6 py-3">Productor</th>
                        <th class="px-6 py-3">Origen</th>
                        <th class="px-6 py-3 text-center">Estado en Rampa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($vehiculosDentro as $vehiculo)
                        <tr class="hover:bg-gray-50 transition duration-100">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                {{ \Carbon\Carbon::parse($vehiculo->HoraEntrada)->format('d/m/Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700 tracking-wide text-base">
                                {{ $vehiculo->Placa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $vehiculo->tipoVehiculo->NombreVehiculos ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ ($vehiculo->conductor->NombreConductor ?? '') . ' ' . ($vehiculo->conductor->ApellidoConductor ?? '') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $vehiculo->productor->NombreProductores ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $vehiculo->origen->NombreOrigenes ?? 'N/A' }}</td>

                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($vehiculo->Estado == 'Descargando')
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-500 text-white shadow-sm uppercase tracking-wider">
                                        Descargando
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                        En Plantel
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                No hay vehículos dentro de las instalaciones en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <div class="bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200">
        <div class="border-b pb-4 mb-6">
            <h2 class="text-xl font-bold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Vehículos Despachados (Salidas del Día)
            </h2>
            <p class="text-xs text-gray-500">Historial de unidades que completaron exitosamente su ciclo de descarga</p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-500">
                <thead class="bg-gray-100 text-xs uppercase font-semibold text-gray-600">
                    <tr>
                        <th class="px-6 py-3">Hora Entrada</th>
                        <th class="px-6 py-3">Placa</th>
                        <th class="px-6 py-3">Tipo Vehículo</th>
                        <th class="px-6 py-3">Conductor</th>
                        <th class="px-6 py-3 text-center">Estado Final</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse($vehiculosDespachados as $despachado)
                        <tr class="bg-gray-50/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($despachado->HoraEntrada)->format('d/m/Y h:i A') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                {{ $despachado->Placa }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $despachado->tipoVehiculo->NombreVehiculos ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ ($despachado->conductor->NombreConductor ?? '') . ' ' . ($despachado->conductor->ApellidoConductor ?? '') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2.5 py-0.5 text-xs font-medium rounded bg-blue-100 text-blue-800 border border-blue-200">
                                    Despachado
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-xs">
                                Ningún vehículo ha sido despachado hoy.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
