@extends('layouts.app')

@section('contenido')
<div class="space-y-6">

    <div class="bg-white p-6 rounded-lg shadow border border-gray-200 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight">Módulo Operativo de Rampa</h1>
            <p class="text-sm text-gray-500 mt-1">Control de descarga física y despacho final de camiones pesados</p>
        </div>
        <div class="bg-green-50 text-green-700 px-4 py-2 rounded-md border border-green-200 text-xs font-semibold uppercase tracking-wider animate-pulse">
            ● Monitoreo en Tiempo Real
        </div>
    </div>

    @if(session('exito'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm text-sm font-medium">
            {{ session('exito') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h2 class="font-bold text-gray-700">Unidades en Zona de Descarga</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-xs uppercase font-bold tracking-wider border-b">
                        <th class="px-6 py-3">Placa</th>
                        <th class="px-6 py-3">Tipo</th>
                        <th class="px-6 py-3">Conductor / Productor</th>
                        <th class="px-6 py-3 text-center">Estado Actual</th>
                        <th class="px-6 py-3 text-right">Acciones Operativas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                    @forelse($vehiculosEnRampa as $v)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 font-black text-gray-900 tracking-wider">
                                {{ $v->Placa }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-gray-500">
                                {{ $v->tipoVehiculo->Nombre ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-800">{{ $v->conductor->Nombre ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400">Productor: {{ $v->productor->Nombre ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($v->Estado === 'En Plantel')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        Esperando Descarga
                                    </span>
                                @elseif($v->Estado === 'Descargando')
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200 animate-pulse">
                                        Descargando Fruta...
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end space-x-2">

                                    @if($v->Estado === 'En Plantel')
                                        <form action="{{ route('movimientos.descargar', $v->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs py-2 px-4 rounded shadow transition flex items-center">
                                                ⬇️ Iniciar Descarga
                                            </button>
                                        </form>
                                    @endif

                                    @if($v->Estado === 'Descargando')
                                        <form action="{{ route('movimientos.despachar', $v->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Confirma que desea despachar y dar salida formal a este vehículo?')">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs py-2 px-4 rounded shadow transition flex items-center">
                                                🚚 Despachar Unidad
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                <div class="text-3xl mb-2">🚛</div>
                                <p class="text-sm">No hay unidades asignadas a la rampa en este momento.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
