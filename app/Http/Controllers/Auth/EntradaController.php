<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;


class EntradaController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function como_cliente(){

        return view('welcome');

    }

    public function ingresar_cliente(){
        return view('layouts.app');
    }


    public function validar(Request $request)
    {
        $usuario = $request->input('correo');
        $encontrado = Usuario::where('correo', $usuario)->first();
        if (is_null($encontrado)) {
            //  return "USUARIO NO EXISTE";
            return redirect()->back();
        } else {
            $clave_dieron = $request->input('clave');
            $clave_guardada = $encontrado->password;

            if (Hash::check($clave_dieron, $clave_guardada)) {
                Auth::login($encontrado);

                //Dirigiendo a la pagina si es admin
                $rol = $encontrado->role()->first();

                if ($rol->nombre == 'administrador')
                    //return "ES ADMINISTRADOR ";
                    return redirect('/bienvenida');
                else
                    //return "es cliente";
                    //return redirect(route('cliente'));
                    return redirect('/welcome');
            } else {
                //return "LA CLAVE ES INCORRECTA";
                return redirect()->back();
            }
        }
    }
    public function salir()
    {
        Auth::logout();
        return redirect(route('entrada'));
    }
}
