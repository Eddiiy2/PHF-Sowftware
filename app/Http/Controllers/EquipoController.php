<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EquipoController extends Controller
{
    public function listado($equipos)
    {
        //VISTA DONDE SE ENCUENTRA NUESTRA VISTA HTML
        return view("equipos.equipos_cips", ['listado' => $equipos]);
    }

    public function equipos ($equipos)
    {
        //CACHAMOS EL DATO QUE ENVIAMOS PARA PODER HACER LAS CONSULTAS GENERICA A LA DB
        //EL DATO QUE SE ESTA ALMACENTANDO EN LA VARIABLE ES EL NOMBRE DE LA TABLA PARA CONSULTAT
        $table = $equipos . "_equipo_area1";
        //HACEMOS LA CONSULTA A LA DB Y ORDENAMOS LOS DATOS POR MEDIO DEL ID CORRESPONDIENTE
        $enviar = DB::table("$table")->orderBy('valor', 'ASC')->get();
        //ENVIAMOS TODO LOS DATOS POR MEDIO DE JSON PARA PODER MOSTRAR LOS DATOS POR MEDIO DE AJAX
        return response()->json(array('enviar'=>$enviar));

    }

    public function store (Request $request, $equipos)
    {
        //USAMOS LA FUNCION  VALIDATOR PARA PODER VALIDAR LOS DATOS INGRESADO AL MOMENTO DE AGREGAR DATOS
        $validator = Validator::make($request->all(), [
            'valor'=>'required',
            'equipo'=>'required',

        ]);
        if($validator->fails())
        {
            return response()->json(['status'=>400, 'errors'=>$validator->messages(),]);

        }else {
            //INGRESAMOS EN EL CAMPO LOS VALORES DE LA DB
            $datos =[
                'valor'=>$request->valor,
                'equipo'=>$request->equipo,

            ];
            //USAMOS DE NUEVO UNA VARIABLE PARA PODER INGRESAR LOS DATOS
            $table = $equipos . "_equipo_area1";
            //POR MEDIO DEL INSERT INGRESAMOS DATOS NUEVO A LA DB Y MANDAMOS UN STATUS 200 CON UN MENSAJE
            DB::table("$table")->insert($datos);
            //MANDAMOS UN MENSAJE POR MEDIO DE JSON PARA PODER SER MOSTRADO
            return response()->json([
            'status'=>200,
            'message'=>'Equipo agregado con exito',]);
        }

    }

    //FUNCION DE EDITAR
    public function edit($equipos, $id){
        //HACEMOS LA CONSULTA A LA DB POR MEDIO DE SU ID
        $table = $equipos . "_equipo_area1";
        $datos = DB::table("$table")->where('valor', $id)->first();
        //POR MEDIO DE UN IF VERIFICAMOS SI LOS DATOS EDITADOS SON CORRECTOS
        if($datos){
            //ENVIAMOS EL DATO
            return response()->json(['status'=>200, 'equipo1'=>$datos]);
        }else{
            //MANDAMOS UN MENSAJE POR MEDIO DE JSON PARA PODER SER MOSTRADO
            return response()->json(['status'=>404, 'messsage'=>'Fallo proceso de actualizacion']);
        }

    }
    //FUNCION DE ACTUALIZAR
    public function update(Request $request,$equipos, $id){

        //VALIDAMOS EL CAMPO
        $validator = Validator::make($request->all(), [
            'equipo'=>'required',

        ]);
        if($validator->fails()){
            return response()->json(['status'=>400, 'errors'=>$validator->messages()]);
        }else {
            //INGRESAMOS EL VALOR DEL CAMPO A LA DB
            $datos =[
                'equipo' => $request->equipo,
            ];
            $table = $equipos ."_equipo_area1";
            //POR MEDIO DE UNA CONSULTAR ACTUALIZAMOS EL REGISTRO DEL DATO
            DB::table("$table")->where('valor', $id)->update($datos);
            //MANDAMOS UN MENSAJE POR MEDIO DE JSON PARA PODER SER MOSTRADO
            return response()->json(['status'=>200, 'message'=>'Equipo actualizado  con exito']);
        }

    }
    //FUNCION ELIMINAR
    public function destroy($equipos, $id) {
        $tabla = $equipos ."_equipo_area1";
        //HACEMOS UNA CONSULTA A LA DB POR MEDIO DEL ID PARA PODER ELIMINAR EL DATO
        DB::table("$tabla")->where('valor', $id)->delete();
        //MANDAMOS UN MENSAJE POR MEDIO DE JSON PARA PODER SER MOSTRADO
        return response()->json(['status'=>200, 'message'=>'Equipo eliminado con exito']);

    }
}
