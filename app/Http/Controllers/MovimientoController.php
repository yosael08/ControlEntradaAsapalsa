<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\TipoVehiculo;
use App\Models\Conductor;
use App\Models\Productor;
use App\Models\Origen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MovimientoController extends Controller
{
    /**
     * 1. PANTALLA VIGILANCIA: Muestra dos tablas.
     * Vehículos que están dentro del plantel y vehículos ya despachados.
     */
    public function index()
    {
        // Vehículos actualmente dentro (En Plantel o Descargando)
        $vehiculosDentro = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        // Vehículos que ya terminaron y salieron
        $vehiculosDespachados = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->where('Estado', 'Despachado')
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        return view('movimientos.index', compact('vehiculosDentro', 'vehiculosDespachados'));
    }

    /**
     * 2. PANTALLA RAMPA: El operador solo ve los vehículos que están adentro
     * y listos para procesar.
     */
    public function rampa()
    {
        $enRampa = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'asc') // El primero que entra es el primero en atenderse
            ->get();

        return view('movimientos.rampa', compact('enRampa'));
    }

    /**
     * 3. ACCIÓN: Cambiar estado a "Descargando"
     */
    public function descargar($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->update(['Estado' => 'Descargando']);

        return redirect()->route('movimientos.rampa')->with('exito', 'El vehículo ahora está en proceso de descarga.');
    }

    /**
     * 4. ACCIÓN: Cambiar estado a "Despachado"
     */
    public function despachar($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->update(['Estado' => 'Despachado']);

        return redirect()->route('movimientos.rampa')->with('exito', 'Vehículo despachado exitosamente.');
    }

    // Dejamos la función store lista para cuando simulemos el ingreso desde la cola
    public function store(Request $request)
    {
        $request->validate([
            'Placa' => 'required|string|max:50',
            'ID_TipoVehiculo' => 'required|integer',
            'ID_NombreConductor' => 'required|integer',
            'ID_NombreProductor' => 'required|integer',
            'ID_Origen' => 'required|integer',
        ]);

        Movimiento::create([
            'HoraEntrada' => Carbon::now(),
            'Placa' => $request->Placa,
            'ISCC' => $request->has('ISCC'),
            'Estado' => 'En Plantel', // Nace por defecto adentro del plantel
            'ID_TipoVehiculo' => $request->ID_TipoVehiculo,
            'ID_NombreConductor' => $request->ID_NombreConductor,
            'ID_NombreProductor' => $request->ID_NombreProductor,
            'ID_Origen' => $request->ID_Origen,
            'Usuario_Autoriza' => 1
        ]);

        return redirect()->route('movimientos.index')->with('exito', 'Entrada al plantel registrada con éxito.');
    }
}
