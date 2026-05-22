<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ColaEspera extends Model
{
    protected $table = 'COLA_ESPERA';

    // BLINDAJE CRÍTICO: Elimina los milisegundos y guiones para que SQL Server acepte los updates de la cola
    protected $dateFormat = 'Ymd H:i:s';

    protected $fillable = [
        'Placa',
        'Estado',
        'ID_TipoVehiculo',
        'ID_NombreConductor',
        'ID_NombreProductor',
        'ID_Origen',
        'ISCC',
        'Usuario_Registra'
    ];

    // --- RELACIONES ---

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

    public function usuarioRegistra(): BelongsTo
    {
        return $this->belongsTo(User::class, 'Usuario_Registra', 'id');
    }
}
