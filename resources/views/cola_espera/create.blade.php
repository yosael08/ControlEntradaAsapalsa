@extends('layouts.app')

@section('contenido')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200">

    <div class="border-b pb-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Registrar Entrada a Cola de Espera</h1>
        <p class="text-sm text-gray-500">Complete los datos del vehículo y procedencia para asignarle un lugar en la fila</p>
    </div>

    <form action="{{ route('cola-espera.store') }}" method="POST" class="space-y-6">
        @csrf <div>
            <label for="Placa" class="block text-sm font-semibold text-gray-700 mb-1">Número de Placa del Vehículo</label>
            <input type="text" name="Placa" id="Placa" required placeholder="Ej: H AB 1234"
                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition uppercase">
        </div>

        <div>
            <label for="ID_TipoVehiculo" class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Vehículo</label>
            <select name="ID_TipoVehiculo" id="ID_TipoVehiculo" required class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-green-500 outline-none">
                <option value="">-- Seleccione un tipo --</option>
                @foreach($tiposVehiculos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->NombreVehiculos }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="ID_NombreConductor" class="block text-sm font-semibold text-gray-700 mb-1">Conductor Responsable</label>
            <select name="ID_NombreConductor" id="ID_NombreConductor" required class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-green-500 outline-none">
                <option value="">-- Seleccione el conductor --</option>
                @foreach($conductores as $conductor)
                    <option value="{{ $conductor->id }}">{{ $conductor->NombreConductor }} {{ $conductor->ApellidoConductor }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="ID_NombreProductor" class="block text-sm font-semibold text-gray-700 mb-1">Productor / Dueño de la Carga</label>
            <select name="ID_NombreProductor" id="ID_NombreProductor" required class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-green-500 outline-none">
                <option value="">-- Seleccione el productor --</option>
                @foreach($productores as $productor)
                    <option value="{{ $productor->id }}">{{ $productor->NombreProductores }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="ID_Origen" class="block text-sm font-semibold text-gray-700 mb-1">Finca / Lugar de Origen</label>
            <select name="ID_Origen" id="ID_Origen" required class="w-full px-4 py-2 border border-gray-300 rounded-md bg-white focus:ring-2 focus:ring-green-500 outline-none">
                <option value="">-- Seleccione el lugar de origen --</option>
                @foreach($origenes as $origen)
                    <option value="{{ $origen->id }}">{{ $origen->NombreOrigenes }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-end space-x-4 pt-4 border-t">
            <a href="{{ route('cola-espera.index') }}" class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-50 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2 rounded bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition">
                Guardar en Cola
            </button>
        </div>
    </form>
</div>
@endsection
