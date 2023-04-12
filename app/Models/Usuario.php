<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//ESTA LIBERIA NOS SERVIRA PARA PODER AUTENTICAR A LOS USUARIOS
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    public function role()
    {

        return $this->belongsTo(Role::class, 'id');
    }
}
