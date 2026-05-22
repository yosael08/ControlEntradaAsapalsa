<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Movimiento extends Model
{
    protected $table = 'MOVIMIENTOS';

   protected $fillable = [
        'HoraEntrada',
        'Placa',
        'ISCC',
        'Estado',
        'ID_TipoVehiculo',
        'ID_NombreConductor',
        'ID_NombreProductor',
        'ID_Origen',
        'Usuario_Autoriza'
    ];
    // Aseguramos que Laravel trate este campo como booleano verdadero/falso automáticamente
    protected $casts = [
        'ISCC' => 'boolean',
    ];

    // --- RELACIONES DEL DIAGRAMA ---

    public function tipoVehiculo(): BelongsTo
    {
        return $this->belongsTo(TipoVehiculo::class, 'ID_TipoVehiculo', 'id');
    }

    public function conductor(): BelongsTo
    {
        return $this->belongsTo(Conductor::class, 'ID_NombreConductor', 'id');
    }

    public function productor(): BelongsTo
    {
        return $this->belongsTo(Productor::class, 'ID_NombreProductor', 'id');
    }

    public function origen(): BelongsTo
    {
        return $this->belongsTo(Origen::class, 'ID_Origen', 'id');
    }

    public function usuarioAutoriza(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'Usuario_Autoriza', 'id');
    }
}
