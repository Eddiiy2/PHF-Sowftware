<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class SecuenciaController extends Controller
{
    public function principal($secuencias)
    {
        //VISTA DONDE SE ENCUENTRA EL HTML
        return view("secuencias.secuencias_cips", ['envios' => $secuencias]);

    }

    public function secuencias($secuencias)
    {
        //CAPTURAMOS EL DATO ENVIADO PARA PODER HACER LA CONSULTA GENERICA
        $nomtabla = $secuencias . "_secuencia_area1";
        //CONSULTA A LA TABLA DE LA DB PARA PODER SER ENVIADO DE MANERA ORDENADA
        $mandar = DB::table( "$nomtabla")->orderBy('valor', 'ASC')->get();
        //ENVIAMOS LOS DATOS EN FORMA DE JSON, POR MEDIO DE UNA VARIABLE PARA PODER SER MANIPULADA POR MEDIO DE AJAX
        return response()->json(array('mandar'=>$mandar));
    }


    public function store(Request $request, $secuencias)
    {
        //UTILIZAMOS LA FUNCION PARA PODER VALIDAR LOS CAMPOS
        $validator = Validator::make($request->all(), [
            'valor'=>'required',
            'secuencia'=>'required',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }else {
              //INGRESAMOS EN EL CAMPO LOS VALORES DE LA DB
            $datos =[
                'valor'=>$request->valor,
                'secuencia' => $request->secuencia,
            ];

              //USAMOS DE NUEVO UNA VARIABLE PARA PODER INGRESAR LOS DATOS
            $nomtabla = $secuencias . "_secuencia_area1";
                //POR MEDIO DEL INSERT INGRESAMOS DATOS NUEVO A LA DB Y MANDAMOS UN STATUS 200 CON UN MENSAJE
            DB::table("$nomtabla")->insert($datos);
            return response()->json([
            'status'=>200,
            'message'=>'Secuencia agregada con exito',
            ]);
        }


    }

    //FUNCION EDITAR

    public function edit($secuencias , $idse)
    {
         //HACEMOS LA CONSULTA A LA DB POR MEDIO DE SU ID
        $nometabla = $secuencias ."_secuencia_area1";
        $datos = DB::table("$nometabla")->where('valor', $idse)->first();
        //POR MEDIO DE UN IF VERIFICAMOS SI LOS DATOS EDITADOS SON CORRECTOS
        if($datos){
            return response()->json(['status'=>200, 'secuencias1'=>$datos,]);
        }
        else{
            return response()->json(['status'=>404, 'message'=>'fallo proceso de actualizacion']);

        }

    }

    public function update(Request $request, $secuencias, $idse)
    {
        $validator = Validator::make($request->all(), [
            'secuencia'=>'required',
        ]);
        if($validator->fails())
        {
            return response()->json(['status'=>400, 'errors'=>$validator->messages()]);
        }
        else {
            $datos =[

                'secuencia' => $request->secuencia,
            ];
             //POR MEDIO DE UNA CONSULTAR ACTUALIZAMOS EL REGISTRO DEL DATO
            $tablas = $secuencias ."_secuencia_area1";
            DB::table("$tablas")->where('valor', $idse)->update($datos);
            return response()->json(['status'=>200, 'message'=>'Secuencia agregada con exito']);



        }

    }

    public function destroy($secuencias, $idse)
    {
        $tablas = $secuencias . "_secuencia_area1";
         //HACEMOS UNA CONSULTA A LA DB POR MEDIO DEL ID PARA PODER ELIMINAR EL DATO
        DB::table("$tablas")->where("valor", $idse)->delete();
         //MANDAMOS UN MENSAJE POR MEDIO DE JSON PARA PODER SER MOSTRADO
        return response()->json(['status'=>200, 'message'=>'Secuencia eliminada con exito']);
    }
}
