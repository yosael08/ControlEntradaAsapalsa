<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ColaEspera;
use App\Models\TipoVehiculo;

class ColaEsperaController extends Controller
{
    /**
     * Muestra la lista de la cola de espera exterior
     */
    public function index()
    {
        // 1. Cargar camiones en estado 'En Espera' con sus relaciones de Eloquent
        $colaEspera = ColaEspera::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->where('Estado', 'En Espera')
            ->orderBy('created_at', 'asc')
            ->get();

        $tiposVehiculos = TipoVehiculo::all();

        // 2. SOLUCIÓN AL DIVISION BY ZERO:
        $maximos = (object)[
            'Carritos'   => 100,
            'Camiones'   => 100,
            'Max_Diario' => 100
        ];

        // Sincronizado enviando 'colaEspera' con el nombre exacto que espera la vista index
        return view('cola_espera.index', compact('colaEspera', 'tiposVehiculos', 'maximos'));
    }

    /**
     * Muestra el formulario para registrar un nuevo vehículo en la cola
     */
    public function create()
    {
        $tiposVehiculos = TipoVehiculo::all();

        return view('cola_espera.create', compact('tiposVehiculos'));
    }

    /**
     * ACCIÓN: Guarda el vehículo en la cola ejecutando el procedimiento almacenado
     */
    public function store(Request $request)
    {
        // Validaciones estrictas del formulario (DNI de 13 dígitos obligatorio)
        $request->validate([
            'Placa'           => 'required|string|max:10',
            'ID_TipoVehiculo' => 'required|integer',
            'NombreConductor' => 'required|string|max:100',
            'NombreProductor' => 'required|string|max:100',
            'DniConductor'    => 'required|numeric|digits:13',
            'NombreOrigen'    => 'required|string|max:100',
        ], [
            'Placa.required'           => 'La placa es obligatoria.',
            'ID_TipoVehiculo.required' => 'El tipo de vehículo es obligatorio.',
            'NombreConductor.required' => 'El nombre del conductor es obligatorio.',
            'NombreProductor.required' => 'El nombre del productor es obligatorio.',
            'DniConductor.required'    => 'El DNI del conductor es obligatorio.',
            'DniConductor.numeric'     => 'El DNI solo debe contener números.',
            'DniConductor.digits'      => 'El DNI debe tener exactamente 13 dígitos.',
            'NombreOrigen.required'    => 'El origen de carga es obligatorio.',
        ]);

        try {
            $usuarioRegistro = auth()->id() ?? 1;

            // Ejecución limpia del SP sincronizada con tu SQL Server (7 parámetros)
            DB::statement('EXEC sp_RegistrarVehiculoCola ?, ?, ?, ?, ?, ?, ?', [
                $request->Placa,
                $request->ID_TipoVehiculo,
                $request->NombreConductor,
                $request->NombreProductor,
                $request->DniConductor,
                $request->NombreOrigen,
                $usuarioRegistro
            ]);

            // Redirección usando la sesión estándar 'success' que lee la vista index
            return redirect()->route('cola-espera.index')
                ->with('success', '¡Vehículo registrado en la cola de espera exitosamente!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al registrar en la base de datos: ' . $e->getMessage()]);
        }
    }
}
