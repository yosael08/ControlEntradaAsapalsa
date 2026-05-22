<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'USUARIOS';
    protected $fillable = ['Nombre', 'Usuario', 'Contrasena', 'Rol'];

    // Ocultar la contraseña cuando se hagan consultas por seguridad
    protected $hidden = ['Contrasena'];
}
