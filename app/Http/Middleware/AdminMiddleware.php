<?php

namespace App\Http\Middleware;
use App\Models\Usuario;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //CODIGO PARA VALIDAR SI EL USUARIO SE MESTA AUTENTICANDO
       if(Auth::user()->nombre_rol!='administrador'){
            return redirect()->route('login');
       }
            return $next($request);

       //verificamos si es administrador


    }
}
