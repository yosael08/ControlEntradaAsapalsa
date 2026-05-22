<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\ColaEspera;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Importamos la fachada de Base de Datos

class MovimientoController extends Controller
{
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

    public function darAcceso($id)
    {
        $itemCola = ColaEspera::findOrFail($id);

        // PROTECCIÓN ADICIONAL: Si por alguna razón el vehículo ya fue ingresado, evitar re-procesar
        if ($itemCola->Estado === 'Ingresado') {
            return redirect()->route('cola-espera.index')
                ->withErrors(['duplicado' => 'Este vehículo ya ha sido ingresado al plantel.']);
        }

        $tipoVehiculo = $itemCola->tipoVehiculo;
        $nombreTipo = $tipoVehiculo->Nombre ?? 'Camion';

        $maximos = [
            'Carritos'  => 10,
            'Camion'    => 5,
            'De Volteo' => 6,
            'NPR'       => 4,
        ];

        $limiteMaximo = $maximos[$nombreTipo] ?? 5;

        $actualesDentro = Movimiento::where('ID_TipoVehiculo', $itemCola->ID_TipoVehiculo)
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->count();

        if ($actualesDentro >= $limiteMaximo) {
            return redirect()->route('cola-espera.index')
                ->withErrors(['cupo' => 'No se puede dar acceso. El cupo para la categoría ' . $nombreTipo . ' está lleno.']);
        }

        $fechaUniversal = now()->format('Ymd H:i:s');

        // BLINDAJE CON TRANSACCIÓN: Se ejecuta todo o no se ejecuta nada
        DB::beginTransaction();

        try {
            // 1. Crear Movimiento
            Movimiento::create([
                'Placa' => $itemCola->Placa,
                'Estado' => 'En Plantel',
                'ID_TipoVehiculo' => $itemCola->ID_TipoVehiculo,
                'ID_NombreConductor' => $itemCola->ID_NombreConductor,
                'ID_NombreProductor' => $itemCola->ID_NombreProductor,
                'ID_Origen' => $itemCola->ID_Origen,
                'ISCC' => $itemCola->ISCC ?? 0,
                'HoraEntrada' => $fechaUniversal,
                'Usuario_Autoriza' => auth()->id() ?? 1
            ]);

            // 2. Sacar de la cola exterior
            $itemCola->Estado = 'Ingresado';
            $itemCola->save();

            // Si todo salió bien, guardamos los cambios definitivamente
            DB::commit();

            return redirect()->route('cola-espera.index')->with('exito', '¡Vehículo autorizado! Ha ingresado formalmente al plantel.');

        } catch (\Exception $e) {
            // Si algo falla en cualquiera de los dos pasos, deshacemos todo lo que se hizo en la BD
            DB::rollBack();

            return redirect()->route('cola-espera.index')
                ->withErrors(['error' => 'Ocurrió un error al procesar el ingreso: ' . $e->getMessage()]);
        }
    }

    public function rampaIndex()
    {
        $vehiculosEnRampa = Movimiento::with(['tipoVehiculo', 'conductor', 'productor', 'origen'])
            ->whereIn('Estado', ['En Plantel', 'Descargando'])
            ->orderBy('HoraEntrada', 'asc')
            ->get();

        return view('movimientos.rampa', compact('vehiculosEnRampa'));
    }

    public function iniciarDescarga($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->Estado = 'Descargando';
        $movimiento->save();

        return redirect()->route('movimientos.rampa')->with('exito', 'Descarga iniciada para la placa ' . $movimiento->Placa);
    }

    public function despacharVehiculo($id)
    {
        $movimiento = Movimiento::findOrFail($id);
        $movimiento->Estado = 'Despachado';
        $movimiento->save();

        return redirect()->route('movimientos.rampa')->with('exito', 'Vehículo despachado y liberado con éxito.');
    }
}
