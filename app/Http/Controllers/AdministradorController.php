<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministradorController extends Controller
{
    protected $redirectTo = '/login';
    public function configuracion(){

        return view('administrador.configuraciones');

    }
}
