@extends('layouts.app')

@section('contenido')
<div class="max-w-3xl mx-auto my-6 px-4">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <div class="bg-gradient-to-r charities bg-blue-600 p-6 text-white flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold tracking-tight">📝 Control de Accesos: ASAPALSA</h1>
                <p class="text-xs text-blue-100 mt-1">Registrar nueva unidad en la Fila de Espera Externa</p>
            </div>
            <a href="{{ route('cola-espera.index') }}" class="text-white hover:bg-blue-700 bg-blue-500/40 text-xs font-bold uppercase px-4 py-2 rounded-lg transition">
                🗙 Cancelar
            </a>
        </div>

        <form action="{{ route('cola-espera.store') }}" method="POST" class="p-6 space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">🔎 Número de Placa</label>
                    <input type="text" name="Placa" list="lista-placas" autocomplete="off" required placeholder="Escriba o seleccione..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-mono font-bold uppercase transition">
                    <datalist id="lista-placas">
                        @foreach($placas as $p)
                            <option value="{{ $p->Placa }}">
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">🚛 Tipo de Unidad</label>
                    <select name="ID_TipoVehiculo" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium transition">
                        <option value="" disabled selected>Seleccione el tipo...</option>
                        @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->Nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">🧔 Conductor Asignado</label>
                    <input type="text" name="NombreConductor" list="lista-conductores" autocomplete="off" required placeholder="Buscar o escribir nuevo..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    <datalist id="lista-conductores">
                        @foreach($conductores as $c)
                            <option data-id="{{ $c->id }}" value="{{ $c->id }} — {{ $c->Nombre }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">🌾 Empresa / Productor</label>
                    <input type="text" name="NombreProductor" list="lista-productores" autocomplete="off" required placeholder="Buscar o escribir nuevo..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    <datalist id="lista-productores">
                        @foreach($productores as $p)
                            <option data-id="{{ $p->id }}" value="{{ $p->id }} — {{ $p->Nombre }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">📍 Procedencia / Finca de Origen</label>
                    <input type="text" name="NombreOrigen" list="lista-origenes" autocomplete="off" required placeholder="Buscar o escribir origen..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition">
                    <datalist id="lista-origenes">
                        @foreach($origenes as $o)
                            <option data-id="{{ $o->id }}" value="{{ $o->id }} — {{ $o->Nombre }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="md:col-span-2 bg-green-50/60 border border-green-100 rounded-xl p-4 flex items-center space-x-3 mt-2">
                    <input type="checkbox" name="ISCC" id="iscc" value="1" class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <label Skinner for="iscc" class="text-xs font-semibold text-green-800 cursor-pointer select-none">
                        🌱 Carga con Certificación de Sostenibilidad Ambiental (ISCC)
                    </label>
                </div>

            </div>

            <div class="border-t border-gray-100 pt-4 flex justify-end space-x-3">
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider px-6 py-3.5 rounded-xl shadow transition duration-150">
                    💾 Guardar en Fila de Espera
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    // Script inteligente para interceptar el envío y mandar solo el ID si seleccionó una opción existente
    document.querySelector('form').addEventListener('submit', function(e) {
        ['lista-conductores', 'lista-productores', 'lista-origenes'].forEach(idLista => {
            const input = document.querySelector(`input[list="${idLista}"]`);
            if(input && input.value.includes(' — ')) {
                const idReal = input.value.split(' — ')[0].trim();
                input.value = idReal; // Transforma el texto largo en el ID numérico antes de viajar al servidor
            }
        });
    });
</script>
@endsection
