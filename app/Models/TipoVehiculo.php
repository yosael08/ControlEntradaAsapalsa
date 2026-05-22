<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVehiculo extends Model
{
    // Le decimos a Laravel el nombre exacto de la tabla en SQL Server
    protected $table = 'TIPOS_VEHICULOS';

    // Los campos que permitiremos llenar en los formularios
    protected $fillable = ['NombreVehiculos', 'CupoMaximo'];
}
