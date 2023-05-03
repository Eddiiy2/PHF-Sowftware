<?php

namespace App\Http\Controllers;

use App\Models\Cip02Secuencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCip02SecuenciaRequest;
use App\Http\Requests\UpdateCip02SecuenciaRequest;

class Cip02SecuenciaController extends Controller
{

    public function index()
    {
        //VISTA
        return view('cip02_secuencia.listar_cip02');
    }

    public function vistadatos2()
    {
        //MANDAR DATOS JSON PARA AJAX PARA MANDAR A LA TABLA
        $secuencias2 = DB::table('cip02_secuencia_area1')->orderBy('valor', 'ASC')->get();
        return response()->json(array('secuencias2'=>$secuencias2));

    }

    public function edit($valor2)
    {
        //CREANDO METODO PARA ENVIAR EL ID  PODER EDITAR
        $secuencias2 = Cip02Secuencia::find($valor2);
        if($secuencias2){
            return response()->json([
                'status'=>200,
                'secuencias'=>$secuencias2,
            ]);
          }
          else{
            return response()->json([
                'status'=>404,
                'message'=>'fallo proceso de actualizacion',
            ]);
          }

    }


    public function update(Request $request, $valor2)
    {

        $validator2 = Validator::make($request->all(),[
            'name' => 'required|max:191',
        ]);

        if($validator2->fails()) {

            return response()->json([
             'status' =>400,
             'errors' =>$validator2->messages(),
            ]);
        }
        else
        {
            $secuencias2 = Cip02Secuencia::find($valor2);

            if($secuencias2){
                $secuencias2->secuencia = $request->input('name');
                $secuencias2->update();

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
