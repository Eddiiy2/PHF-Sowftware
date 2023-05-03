<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Models\Cip01Secuencia;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCip01SecuenciaRequest;
use App\Http\Requests\UpdateCip01SecuenciaRequest;
use Illuminate\Http\Request;


class Cip01SecuenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('cip01_secuencia.listar_cip01'); //,['cip01_secuencia_area1' => $secuencias]);
    }

    public function vistadato(){
        //ESTA FUNCION NOS SERVIRA PARA MANDAR A LISTAR TODOS LOS DATOS DE LA BD A LA VISTA
        //CREADOS UNA VARIABEL PARA GUARDAR ESTPS DATOS Y DESPUES MANDARLO A MOSTRAR
        //$secuencias = DB::table('cip01_secuencia_area1')->orderBy('valor', 'ASC')->get();
        //CREAMOS UNA VARIABLE A LA CUAL LE MANDAMOS AL MODELO
        $secuencias = DB::table('cip01_secuencia_area1')->orderBy('valor', 'ASC')->get();
        return response()->json(array('secuencias' => $secuencias));
    }

    public function edit($valor)
    {
        //EN ESTA FUNCION CACHAMOS EL ID PARA PODER EDITARLO
      $secuencias = Cip01Secuencia::find($valor);
      if($secuencias){
        return response()->json([
            'status'=>200,
            'secuencias'=>$secuencias,
        ]);
      }
      else{
        return response()->json([
            'status'=>404,
            'message'=>'fallo proceso de actualizacion',
        ]);
      }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $valor)
    {
        //EN ESTE METODO OCUPAREMOS DESPUES DE CACHAR EL ID=VALOR NOS SERVIRA PARA PODER EDITARLO Y GUARDARLO

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:191',
        ]);

        if($validator->fails()) {

            return response()->json([
             'status' =>400,
             'errors' =>$validator->messages(),
            ]);
        }
        else
        {
            $secuencias = Cip01Secuencia::find($valor);

            if($secuencias){
                $secuencias->secuencia = $request->input('name');
                $secuencias->update();

                return response()->json([
                    'status'=>200,
                    'message'=>'Datos Actualizado con exito',
                ]);
              }
              else{
                return response()->json([
                    'status'=>404,
                    'message'=>'fallo proceso de actualizacion',
                ]);
              }

        }



    }
}
