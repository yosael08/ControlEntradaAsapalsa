@extends('layouts.app')

@section('contenido')
<div class="space-y-6">

    @if(session('exito'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm flex items-center justify-between animate-fade-in">
            <div class="flex items-center space-x-3">
                <span class="text-green-500 text-xl">✅</span>
                <p class="text-sm font-semibold text-green-800">{{ session('exito') }}</p>
            </div>
        </div>
    @endif

    <div class="flex flex-col md:flex-row md:items-center md:justify-between bg-white p-6 rounded-xl shadow-sm border border-gray-100 gap-4">
        <div>
            <h1 class="text-2xl font-black text-gray-800 tracking-tight flex items-center space-x-2">
                <span>🚧</span> <span>Módulo de Vigilancia: Fila de Espera</span>
            </h1>
            <p class="text-sm text-gray-500 mt-0.5">Control vehicular externo y monitoreo de capacidades - ASAPALSA</p>
        </div>
        <div>
            <a href="{{ route('cola-espera.create') }}" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider px-5 py-3 rounded-xl shadow transition duration-200">
                <span>➕</span> <span>Registrar Nuevo Vehículo</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($maximos as $tipo => $max)
            @php
                $actual = $conteos[$tipo] ?? 0;
                $porcentaje = ($actual / $max) * 100;
                $colorBarra = $porcentaje >= 100 ? 'bg-red-500' : ($porcentaje >= 75 ? 'bg-amber-500' : 'bg-green-500');
                $colorTexto = $porcentaje >= 100 ? 'text-red-700' : ($porcentaje >= 75 ? 'text-amber-700' : 'text-green-700');
                $colorBg = $porcentaje >= 100 ? 'bg-red-50/50' : ($porcentaje >= 75 ? 'bg-amber-50/50' : 'bg-green-50/50');
            @endphp
            <div class="{{ $colorBg }} border border-gray-100 p-4 rounded-xl shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ $tipo }}</span>
                    <span class="text-xs font-black {{ $colorTexto }} bg-white px-2 py-0.5 rounded-full border border-gray-100 shadow-xs">
                        {{ $actual }} / {{ $max }}
                    </span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2 mt-1 overflow-hidden">
                    <div class="{{ $colorBarra }} h-2 rounded-full transition-all duration-500" style="width: {{ min($porcentaje, 100) }}%"></div>
                </div>
                <p class="text-[10px] text-gray-400 mt-2">
                    {{ $porcentaje >= 100 ? '🔴 CAPACIDAD MÁXIMA ALCANZADA' : '🟢 Espacio disponible en patio' }}
                </p>
            </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
            <h2 class="text-xs font-bold uppercase tracking-wider text-gray-500 flex items-center space-x-2">
                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Unidades en Espera Externa (Fila en Carretera)</span>
            </h2>
            <span class="text-xs font-bold text-gray-500 bg-gray-200/60 px-2.5 py-1 rounded-md">{{ $cola->count() }} Vehículos</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/70 text-[11px] font-bold uppercase text-gray-400 tracking-wider">
                        <th class="py-3 px-6">Posición</th>
                        <th class="py-3 px-6">Placa</th>
                        <th class="py-3 px-6">Tipo</th>
                        <th class="py-3 px-6">Sostenibilidad</th>
                        <th class="py-3 px-6">Estado</th>
                        <th class="py-3 px-6 text-center">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                    @forelse($cola as $index => $item)
                        @php
                            $tipoNombre = $item->TipoNombre ?? 'Camion';
                            $actualDentro = $conteos[$tipoNombre] ?? 0;
                            $maximoPermitido = $maximos[$tipoNombre] ?? 5;
                            $estaLleno = $actualDentro >= $maximoPermitido;
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-4 px-6 font-bold text-gray-400 text-xs">
                                #{{ $index + 1 }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="bg-gray-100 text-gray-800 font-mono font-bold px-2.5 py-1 rounded border border-gray-200 shadow-xs uppercase tracking-wide">
                                    {{ $item->Placa }}
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-700">
                                {{ $tipoNombre }}
                            </td>
                            <td class="py-4 px-6">
                                @if(isset($item->ISCC) && $item->ISCC == 1)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                        🌱 ISCC Certificado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-400">
                                        Estándar
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                    {{ $item->Estado ?? 'En Espera' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($estaLleno)
                                    <button disabled class="bg-gray-100 text-gray-400 font-bold text-[11px] uppercase tracking-wider px-3 py-1.5 rounded-lg border cursor-not-allowed" title="Plantel lleno para este tipo de vehículo">
                                        🚫 Esperar Cupo
                                    </button>
                                @else
                                    <form action="#" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="id_cola" value="{{ $item->ID_Cola ?? $item->id ?? '' }}">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] uppercase tracking-wider px-3 py-1.5 rounded-lg shadow-sm transition">
                                            📥 Dar Ingreso
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-400">
                                <p class="text-base mb-1">📭 No hay vehículos en la fila exterior</p>
                                <p class="text-xs text-gray-400">Todas las unidades ingresaron o el plantel está despejado.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
