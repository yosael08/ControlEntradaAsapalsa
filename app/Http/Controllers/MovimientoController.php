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
     * 1. Muestra el historial de movimientos (Entradas a Planta).
     */
    public function index()
    {
        $movimientos = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        return view('movimientos.index', compact('movimientos'));
    }

    /**
     * 2. Muestra el formulario para autorizar la entrada formal desde la cola.
     */
    public function create()
    {
        $tiposVehiculos = TipoVehiculo::all();
        $conductores = Conductor::all();
        $productores = Productor::all();
        $origenes = Origen::all();

        return view('movimientos.create', compact('tiposVehiculos', 'conductores', 'productores', 'origenes'));
    }

    /**
     * 3. Guarda la entrada oficial en la tabla MOVIMIENTOS.
     */
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
            'ISCC' => $request->has('ISCC'), // Guarda true si marcaron la casilla, false si no
            'ID_TipoVehiculo' => $request->ID_TipoVehiculo,
            'ID_NombreConductor' => $request->ID_NombreConductor,
            'ID_NombreProductor' => $request->ID_NombreProductor,
            'ID_Origen' => $request->ID_Origen,
            'Usuario_Autoriza' => 1 // Temporal hasta el Login
        ]);

        return redirect()->route('movimientos.index')->with('exito', 'Entrada a planta registrada con éxito.');
    }
}
