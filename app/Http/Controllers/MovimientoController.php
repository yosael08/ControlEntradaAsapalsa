<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\TipoVehiculo;
use App\Models\Conductor;
use App\Models\Productor;
use App\Models\Origen;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * Muestra la pantalla principal de Entrada a Plantel (Doble Tabla)
     */
    public function index()
    {
        // Vehículos actualmente dentro (En Plantel o Descargando)
        $vehiculosDentro = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        // Vehículos que ya terminaron y salieron del plantel
        $vehiculosDespachados = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->where('Estado', 'Despachado')
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        return view('movimientos.index', compact('vehiculosDentro', 'vehiculosDespachados'));
    }

    /**
     * Muestra el formulario para autorizar la entrada de un vehículo desde la cola
     */
    public function create(Request $request)
    {
        $tipos = TipoVehiculo::all();
        $conductores = Conductor::all();
        $productores = Productor::all();
        $origenes = Origen::all();

        // Si viene un ID desde la cola de espera, lo capturamos opcionalmente
        $idCola = $request->query('id_cola');

        return view('movimientos.create', compact('tipos', 'conductores', 'productores', 'origenes', 'idCola'));
    }

    /**
     * Guarda la entrada oficial del vehículo al plantel (Estado: 'En Plantel')
     */
    public function store(Request $request)
    {
        $request->validate([
            'Placa' => 'required',
            'ID_TipoVehiculo' => 'required',
            'ID_NombreConductor' => 'required',
            'ID_NombreProductor' => 'required',
            'ID_Origen' => 'required',
        ]);

        Movimiento::create([
            'Placa' => $request->Placa,
            'Estado' => 'En Plantel', // Nace listo para que rampa lo procese
            'ID_TipoVehiculo' => $request->ID_TipoVehiculo,
            'ID_NombreConductor' => $request->ID_NombreConductor,
            'ID_NombreProductor' => $request->ID_NombreProductor,
            'ID_Origen' => $request->ID_Origen,
            'Usuario_Autoriza' => 1
        ]);

        return redirect()->route('movimientos.index')->with('exito', 'Vehículo autorizado con éxito');
    }

    /**
     * 1. Muestra el panel principal operativo de Rampa
     */
    public function rampaIndex()
    {
        // Trae solo las unidades que están físicamente en espera o en proceso de descarga
        $vehiculosEnRampa = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'asc')
            ->get();

        return view('movimientos.rampa', compact('vehiculosEnRampa'));
    }

    /**
     * 2. Cambia el estado del vehículo de "En Plantel" a "Descargando"
     */
    public function iniciarDescarga($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->Estado = 'Descargando';
        $movimiento->save();

        return redirect()->route('movimientos.rampa')->with('exito', 'Descarga iniciada para la placa ' . $movimiento->Placa);
    }

    /**
     * 3. Cambia el estado de "Descargando" a "Despachado" (Salida definitiva del plantel)
     */
    public function despacharVehiculo($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->Estado = 'Despachado';
        $movimiento->save();

        return redirect()->route('movimientos.rampa')->with('exito', 'Vehículo despachado y liberado con éxito.');
    }
}
