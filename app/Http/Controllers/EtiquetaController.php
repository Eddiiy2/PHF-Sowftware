<?php

namespace App\Http\Controllers;
use App\Models\Etiqueta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EtiquetaController extends Controller
{
    public function index($grafica)
    {
        //VISTA DONDE SE ENCUENTRA EL HTML
        return view("etiquetas.etiquetas_graficas", ['grafica' => $grafica]);
    }

    public function etiquetas($grafica){
        //CAPTURAMOS EL DATO ENVIADO PARA PODER HACER LA CONSULTA GENERICA
        //$tabla = DB::select("select * from etiquetas_$grafica");
        //CONSULTA A LA TABLA DE LA DB PARA PODER SER ENVIADO DE MANERA ORDENADA
        $tabla = DB::table("etiquetas_$grafica")->orderBy('id', 'ASC')->get();
         //ENVIAMOS LOS DATOS EN FORMA DE JSON, POR MEDIO DE UNA VARIABLE PARA PODER SER MANIPULADA POR MEDIO DE AJAX
        return response()->json(array('tabla'=>$tabla));

    }

    public function store(Request $request, $grafica){
        //UTILIZAMOS LA FUNCION PARA PODER VALIDAR LOS CAMPOS
        $validator = Validator::make($request->all(), [
            'd1'=>'required',
            'd2'=>'required',
            'd3'=>'required',
            'area'=>'required',
            'idcip'=>'required',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);

        }else{
            //INGRESAMOS EN EL CAMPO LOS VALORES DE LA DB
            $datos =[
                'd1' => $request->d1,
                'd2' => $request->d2,
                'd3' => $request->d3,
                'area' => $request->area,
                'idcip' => $request->idcip,

            ];
            //USAMOS DE NUEVO UNA VARIABLE PARA PODER INGRESAR LOS DATOS
            //POR MEDIO DEL INSERT INGRESAMOS DATOS NUEVO A LA DB Y MANDAMOS UN STATUS 200 CON UN MENSAJE
            DB::table("etiquetas_$grafica")->insert($datos);
            return response()->json([
                'status'=>200,
                'message'=>'Etiqueta agregado con exito',
            ]);
        }
        //$tabla = DB::table("etiquetas_$grafica")->insert($datos);

       //dd($datos);
        /*$tabla ->d1 =$request->input('d1');
        $tabla->d2 =$request->input('d2');
        $tabla->d3 =$request->input('d3');
        $tabla->area =$request->input('area');
        $tabla->idcip =$request->input('cip');
        dd($tabla);*/
    }

    public function edit ($grafica , $id)
    {
        //POR MEDIO DE ESTO BUSCAMOS EL VALOR ESPECIFICO QUE QUEREMOS, EN ESTE CAS O ES EL ID
        $datos = DB::table("etiquetas_$grafica")->where('id', $id)->first();
//POR MEDIO DE UN IF VERIFICAMOS SI LOS DATOS EDITADOS SON CORRECTOS
        if($datos){
            return response()->json([
                'status'=>200,
                'etiquetas1'=>$datos,
            ]);
          }
          else{
            return response()->json([
                'status'=>404,
                'message'=>'fallo proceso de actualizacion',
            ]);
          }
        //return json_encode($datos);
    }

    public function update(Request $request, $grafica, $id)
    {
        $validator = Validator::make($request->all(), [
            'd1'=>'required',
            'd2'=>'required',
            'd3'=>'required',
            'area'=>'required',
            'idcip'=>'required',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'status' =>400,
                'errors' =>$validator->messages(),
               ]);
        }
        else{
            $datos =[
                'd1' => $request->d1,
                'd2' => $request->d2,
                'd3' => $request->d3,
                'area' => $request->area,
                'idcip' => $request->idcip,

            ];
            //POR MEDIO DE UNA CONSULTAR ACTUALIZAMOS EL REGISTRO DEL DATO
            DB::table("etiquetas_$grafica")->where('id', $id)->update($datos);
            return response()->json([
                'status'=>200,
                'message'=>'Etiqueta agregado con exito',
            ]);
        }
    }

    public function destroy($grafica, $id)
    {
         //HACEMOS UNA CONSULTA A LA DB POR MEDIO DEL ID PARA PODER ELIMINAR EL DATO
        DB::table("etiquetas_$grafica")->where('id', $id)->delete();
         //MANDAMOS UN MENSAJE POR MEDIO DE JSON PARA PODER SER MOSTRADO
        return response()->json([
            'status'=>200,
            'message'=>'Etiqueta eliminada',
        ]);

    }


}
