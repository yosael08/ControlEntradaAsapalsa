<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conductor extends Model
{
    protected $table = 'NOMBRE_CONDUCTORES';
    protected $fillable = ['DNI', 'NombreConductor', 'ApellidoConductor'];
}
