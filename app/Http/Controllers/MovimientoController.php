<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\ColaEspera;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;

class MovimientoController extends Controller
{
    /**
     * Muestra el panel general de movimientos
     */
    public function index()
    {
        $vehiculosDentro = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        $vehiculosDespachados = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->where('Estado', 'Despachado')
            ->orderBy('HoraEntrada', 'desc')
            ->get();

        return view('movimientos.index', compact('vehiculosDentro', 'vehiculosDespachados'));
    }

    /**
     * ACCIÓN CRÍTICA: Dar acceso físico al vehículo al plantel desde la cola de espera
     */
    public function darAcceso($id)
    {
        // 1. Obtener los datos del vehículo que está afuera en la cola
        $itemCola = ColaEspera::findOrFail($id);
        $tipoVehiculo = $itemCola->tipoVehiculo;
        $nombreTipo = $tipoVehiculo->Nombre ?? 'Camion';

        // 2. Definir límites estrictos del plantel
        $maximos = [
            'Carritos'  => 10,
            'Camion'    => 5,
            'De Volteo' => 6,
            'NPR'       => 4,
        ];

        $limiteMaximo = $maximos[$nombreTipo] ?? 5;

        // 3. Contar cuántos de ese mismo tipo ya están adentro
        $actualesDentro = Movimiento::where('ID_TipoVehiculo', $itemCola->ID_TipoVehiculo)
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->count();

        // 4. Protección del Backend por si intentan saltarse el bloqueo del botón
        if ($actualesDentro >= $limiteMaximo) {
            return redirect()->route('cola-espera.index')
                ->withErrors(['cupo' => 'No se puede dar acceso. El cupo para la categoría ' . $nombreTipo . ' está lleno.']);
        }

        // 5. Trasladar oficialmente al plantel (Crear Movimiento)
        Movimiento::create([
            'Placa' => $itemCola->Placa,
            'Estado' => 'En Plantel',
            'ID_TipoVehiculo' => $itemCola->ID_TipoVehiculo,
            'ID_NombreConductor' => $itemCola->ID_NombreConductor,
            'ID_NombreProductor' => $itemCola->ID_NombreProductor,
            'ID_Origen' => $itemCola->ID_Origen,
            'ISCC' => $itemCola->ISCC,
            'HoraEntrada' => now(),
            'Usuario_Autoriza' => auth()->id() ?? 1
        ]);

        // 6. Sacar de la cola exterior cambiándole el estado
        $itemCola->Estado = 'Ingresado';
        $itemCola->save();

        return redirect()->route('cola-espera.index')->with('exito', '¡Vehículo autorizado! Ha ingresado formalmente al plantel.');
    }

    /**
     * Muestra el panel operativo de Rampa
     */
    public function rampaIndex()
    {
        $vehiculosEnRampa = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'asc')
            ->get();

        return view('movimientos.rampa', compact('vehiculosEnRampa'));
    }

    /**
     * Cambia el estado a "Descargando"
     */
    public function iniciarDescarga($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->Estado = 'Descargando';
        $movimiento->save();

        return redirect()->route('movimientos.rampa')->with('exito', 'Descarga iniciada para la placa ' . $movimiento->Placa);
    }

    /**
     * Despacha el vehículo (Libera cupo inmediatamente en el plantel)
     */
    public function despacharVehiculo($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->Estado = 'Despachado';
        $movimiento->save();

        return redirect()->route('movimientos.rampa')->with('exito', 'Vehículo despachado y liberado con éxito.');
    }
}
