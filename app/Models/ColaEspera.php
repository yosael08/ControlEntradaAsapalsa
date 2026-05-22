<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColaEspera extends Model
{
    protected $table = 'COLA_ESPERA';

    protected $fillable = [
        'fecha_registro',
        'Placa',
        'Estado',
        'ID_TipoVehiculo',
        'ID_NombreConductor',
        'ID_NombreProductor',
        'ID_Origen',
        'Usuario_Registro'
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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'Usuario_Registro', 'id');
    }
}
