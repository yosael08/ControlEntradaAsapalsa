<?php

namespace App\Http\Controllers;

use App\Models\ColaEspera;
use App\Models\TipoVehiculo;
use App\Models\Conductor;
use App\Models\Productor;
use App\Models\Origen;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ColaEsperaController extends Controller
{
    /**
     * 1. Muestra la pantalla principal con la lista de vehículos en espera.
     */
    public function index()
    {
        // Trae los vehículos en espera ordenados por el más reciente
        // 'with' carga de un solo golpe los nombres gracias a las relaciones que hicimos
        $vehiculosEnEspera = ColaEspera::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->orderBy('fecha_registro', 'desc')
            ->get();

        return view('cola_espera.index', compact('vehiculosEnEspera'));
    }

    /**
     * 2. Muestra el formulario para registrar un vehículo nuevo.
     * Carga las tablas maestras para llenar los menús desplegables (Selects).
     */
    public function create()
    {
        $tiposVehiculos = TipoVehiculo::all();
        $conductores = Conductor::all();
        $productores = Productor::all();
        $origenes = Origen::all();

        return view('cola_espera.create', compact('tiposVehiculos', 'conductores', 'productores', 'origenes'));
    }

    /**
     * 3. Recibe los datos del formulario y los guarda en SQL Server.
     */
    public function store(Request $request)
    {
        // Validamos que los datos obligatorios vengan bien escritos
        $request->validate([
            'Placa' => 'required|string|max:50',
            'ID_TipoVehiculo' => 'required|integer',
            'ID_NombreConductor' => 'required|integer',
            'ID_NombreProductor' => 'required|integer',
            'ID_Origen' => 'required|integer',
        ]);

        // Creamos el registro en la base de datos
        ColaEspera::create([
            'fecha_registro' => Carbon::now(), // Captura hora y fecha actual del sistema
            'Placa' => $request->Placa,
            'Estado' => 'En Espera', // Inicia por defecto en este estado
            'ID_TipoVehiculo' => $request->ID_TipoVehiculo,
            'ID_NombreConductor' => $request->ID_NombreConductor,
            'ID_NombreProductor' => $request->ID_NombreProductor,
            'ID_Origen' => $request->ID_Origen,
            'Usuario_Registro' => 1 // Temporalmente quemado en 1 hasta que hagamos el Login
        ]);

        // Redirecciona a la lista con un mensaje de éxito
        return redirect()->route('cola-espera.index')->with('exito', 'Vehículo registrado en la cola con éxito.');
    }
}
