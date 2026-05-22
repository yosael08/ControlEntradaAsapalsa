<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ColaEsperaController extends Controller
{
    /**
     * Muestra la pantalla principal de la Cola de Espera (Vista del Vigilante)
     */
    public function index()
    {
        // 1. Traemos los vehículos en fila externa protegiendo si la tabla está vacía
        try {
            $colaRegistros = DB::table('COLA_ESPERA')
                ->where('Estado', 'En Espera')
                ->orderBy('ID_Cola', 'asc')
                ->get() ?? collect();
        } catch (\Exception $e) {
            $colaRegistros = collect();
        }

        // Mapeo estático seguro para que la pantalla no dependa de registros en la tabla TIPO_VEHICULOS
        $nombresTipos = [
            1 => 'Carritos',
            2 => 'Camion',
            3 => 'De Volteo',
            4 => 'NPR'
        ];

        // Le asignamos el nombre del tipo dinámicamente en PHP
        $cola = $colaRegistros->map(function($item) use ($nombresTipos) {
            $item->TipoNombre = $nombresTipos[$item->ID_TipoVehiculo] ?? 'Camion';
            return $item;
        });

        // 2. Contar vehículos dentro del plantel (MOVIMIENTOS) protegiendo si la tabla está vacía
        try {
            $dentroQuery = DB::table('MOVIMIENTOS')
                ->whereIn('Estado', ['En Plantel', 'Descargando'])
                ->get() ?? collect();
        } catch (\Exception $e) {
            $dentroQuery = collect();
        }

        $conteos = [
            'Carritos'  => $dentroQuery->count() > 0 ? $dentroQuery->where('ID_TipoVehiculo', 1)->count() : 0,
            'Camion'    => $dentroQuery->count() > 0 ? $dentroQuery->where('ID_TipoVehiculo', 2)->count() : 0,
            'De Volteo' => $dentroQuery->count() > 0 ? $dentroQuery->where('ID_TipoVehiculo', 3)->count() : 0,
            'NPR'       => $dentroQuery->count() > 0 ? $dentroQuery->where('ID_TipoVehiculo', 4)->count() : 0,
        ];

        // Límites máximos de capacidad de ASAPALSA
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

        // Carga de catálogos protegiendo absolutamente todas las consultas contra tablas vacías
        try { $placas = DB::table('COLA_ESPERA')->select('Placa')->distinct()->get() ?? collect(); } catch (\Exception $e) { $placas = collect(); }
        try { $conductores = DB::table('CONDUCTORES')->select('ID_NombreConductor as id', 'NombreConductor as Nombre')->get() ?? collect(); } catch (\Exception $e) { $conductores = collect(); }
        try { $productores = DB::table('PRODUCTORES')->select('ID_NombreProductor as id', 'NombreProductor as Nombre')->get() ?? collect(); } catch (\Exception $e) { $productores = collect(); }
        try { $origenes = DB::table('ORIGENES')->select('ID_Origen as id', 'NombreOrigen as Nombre')->get() ?? collect(); } catch (\Exception $e) { $origenes = collect(); }

        return view('cola_espera.create', compact('tipos', 'placas', 'conductores', 'productores', 'origenes'));
    }

    /**
     * Guarda el registro calculando los IDs de forma manual (Llaves primarias no IDENTITY)
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

        $valorPlaca = strtoupper(trim($request->Placa));
        $inputCond = trim($request->NombreConductor);
        $inputProd = trim($request->NombreProductor);
        $inputOrig = trim($request->NombreOrigen);
        $iscc = $request->has('ISCC') ? 1 : 0;

        // 1. RESOLVER CONDUCTOR (Tabla: CONDUCTORES | PK: ID_NombreConductor)
        try {
            if (is_numeric($inputCond) && DB::table('CONDUCTORES')->where('ID_NombreConductor', $inputCond)->exists()) {
                $conductorId = $inputCond;
            } else {
                $conductor = DB::table('CONDUCTORES')->where('NombreConductor', $inputCond)->first();
                if (!$conductor) {
                    $maxId = DB::table('CONDUCTORES')->max('ID_NombreConductor') ?? 0;
                    $conductorId = $maxId + 1;

                    DB::table('CONDUCTORES')->insert([
                        'ID_NombreConductor' => $conductorId,
                        'NombreConductor'    => $inputCond
                    ]);
                } else {
                    $conductorId = $conductor->ID_NombreConductor;
                }
            }
        } catch (\Exception $e) {
            $conductorId = 1;
            DB::table('CONDUCTORES')->insert([
                'ID_NombreConductor' => $conductorId,
                'NombreConductor'    => $inputCond
            ]);
        }

        // 2. RESOLVER PRODUCTOR (Tabla: PRODUCTORES | PK: ID_NombreProductor)
        try {
            if (is_numeric($inputProd) && DB::table('PRODUCTORES')->where('ID_NombreProductor', $inputProd)->exists()) {
                $productorId = $inputProd;
            } else {
                $productor = DB::table('PRODUCTORES')->where('NombreProductor', $inputProd)->first();
                if (!$productor) {
                    $maxId = DB::table('PRODUCTORES')->max('ID_NombreProductor') ?? 0;
                    $productorId = $maxId + 1;

                    DB::table('PRODUCTORES')->insert([
                        'ID_NombreProductor' => $productorId,
                        'NombreProductor'    => $inputProd
                    ]);
                } else {
                    $productorId = $productor->ID_NombreProductor;
                }
            }
        } catch (\Exception $e) {
            $productorId = 1;
            DB::table('PRODUCTORES')->insert([
                'ID_NombreProductor' => $productorId,
                'NombreProductor'    => $inputProd
            ]);
        }

        // 3. RESOLVER ORIGEN (Tabla: ORIGENES | PK: ID_Origen)
        try {
            if (is_numeric($inputOrig) && DB::table('ORIGENES')->where('ID_Origen', $inputOrig)->exists()) {
                $origenId = $inputOrig;
            } else {
                $origen = DB::table('ORIGENES')->where('NombreOrigen', $inputOrig)->first();
                if (!$origen) {
                    $maxId = DB::table('ORIGENES')->max('ID_Origen') ?? 0;
                    $origenId = $maxId + 1;

                    DB::table('ORIGENES')->insert([
                        'ID_Origen'    => $origenId,
                        'NombreOrigen' => $inputOrig
                    ]);
                } else {
                    $origenId = $origen->ID_Origen;
                }
            }
        } catch (\Exception $e) {
            $origenId = 1;
            DB::table('ORIGENES')->insert([
                'ID_Origen'    => $origenId,
                'NombreOrigen' => $inputOrig
            ]);
        }

        // 4. GUARDAR EN COLA_ESPERA (Calculando ID_Cola manualmente)
        try {
            $maxCola = DB::table('COLA_ESPERA')->max('ID_Cola') ?? 0;
            $idColaNuevo = $maxCola + 1;
        } catch (\Exception $e) {
            $idColaNuevo = 1;
        }

        DB::table('COLA_ESPERA')->insert([
            'ID_Cola'            => $idColaNuevo,
            'Placa'              => $valorPlaca,
            'ID_TipoVehiculo'    => $request->ID_TipoVehiculo,
            'ID_NombreConductor' => $conductorId,
            'ID_NombreProductor' => $productorId,
            'ID_Origen'          => $origenId,
            'ISCC'               => $iscc,
            'Estado'             => 'En Espera'
        ]);

        return redirect()->route('cola-espera.index')->with('exito', '¡Vehículo registrado con éxito!');
    }
}
