<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColaEsperaController extends Controller
{
    /**
     * Muestra la pantalla principal de la Cola de Espera (Vista del Vigilante)
     */
    public function index()
    {
        try {
            // Buscamos usando 'id' que es tu columna real en minúscula
            $resultadoSQL = DB::select("SELECT * FROM COLA_ESPERA WHERE Estado = 'En Espera' ORDER BY id ASC");
            $colaRegistros = collect($resultadoSQL);
        } catch (\Exception $e) {
            $colaRegistros = collect();
        }

        $nombresTipos = [
            1 => 'Carritos',
            2 => 'Camion',
            3 => 'De Volteo',
            4 => 'NPR'
        ];

        $cola = $colaRegistros->map(function($item) use ($nombresTipos) {
            // Usamos ID_TipoVehiculo tal y como se llama en tu tabla COLA_ESPERA
            $item->TipoNombre = $nombresTipos[$item->ID_TipoVehiculo] ?? 'Camion';
            return $item;
        });

        try {
            $dentroQuery = DB::select("SELECT ID_TipoVehiculo FROM MOVIMIENTOS WHERE Estado IN ('En Plantel', 'Descargando')");
            $dentroColeccion = collect($dentroQuery);
        } catch (\Exception $e) {
            $dentroColeccion = collect();
        }

        $conteos = [
            'Carritos'  => $dentroColeccion->where('ID_TipoVehiculo', 1)->count(),
            'Camion'    => $dentroColeccion->where('ID_TipoVehiculo', 2)->count(),
            'De Volteo' => $dentroColeccion->where('ID_TipoVehiculo', 3)->count(),
            'NPR'       => $dentroColeccion->where('ID_TipoVehiculo', 4)->count(),
        ];

        $maximos = [
            'Carritos'  => 10,
            'Camion'    => 5,
            'De Volteo' => 6,
            'NPR'       => 4,
        ];

        return view('cola_espera.index', compact('cola', 'conteos', 'maximos'));
    }

    /**
     * Muestra el formulario de registro enviando los catálogos para el buscador predictivo
     */
    public function create()
    {
        $tipos = [
            (object)['id' => 1, 'Nombre' => 'Carritos'],
            (object)['id' => 2, 'Nombre' => 'Camion'],
            (object)['id' => 3, 'Nombre' => 'De Volteo'],
            (object)['id' => 4, 'Nombre' => 'NPR'],
        ];

        $placas = [];
        $conductores = [];
        $productores = [];
        $origenes = [];

        return view('cola_espera.create', compact('tipos', 'placas', 'conductores', 'productores', 'origenes'));
    }

    /**
     * Guarda el registro llamando al Procedimiento Almacenado de SQL Server
     */
    /**
     * Guarda el registro llamando al Procedimiento Almacenado de SQL Server
     */
    public function store(Request $request)
    {
        $request->validate([
            'Placa' => 'required|string',
            'ID_TipoVehiculo' => 'required',
            'NombreConductor' => 'required|string',
            'NombreProductor' => 'required|string',
            'NombreOrigen' => 'required|string',
        ]);

        $placa     = trim($request->Placa);
        $tipo      = (int)$request->ID_TipoVehiculo;
        $conductor = trim($request->NombreConductor);
        $productor = trim($request->NombreProductor);
        $origen    = trim($request->NombreOrigen);
        $iscc      = $request->has('ISCC') ? 1 : 0;

        // Capturamos el ID del usuario logueado actualmente (en tu caso, dará 5)
        $usuarioSesion = auth()->id();

        // Validamos si ese ID existe físicamente en la tabla USUARIOS para evitar errores de Foreign Key
        $existeUsuario = DB::select("SELECT id FROM USUARIOS WHERE id = ?", [$usuarioSesion]);

        // Si existe en la BD usamos su ID (5), si no existe usamos el ID 1 por defecto
        $usuarioId = !empty($existeUsuario) ? $usuarioSesion : 1;

        // Ejecución del procedimiento almacenado
        DB::statement(
            "EXEC sp_RegistrarVehiculoCola ?, ?, ?, ?, ?, ?, ?",
            [$placa, $tipo, $conductor, $productor, $origen, $iscc, $usuarioId]
        );

        return redirect()->route('cola-espera.index')->with('exito', '¡Vehículo registrado con éxito!');
    }
}
