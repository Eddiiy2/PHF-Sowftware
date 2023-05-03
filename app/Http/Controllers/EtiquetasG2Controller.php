<?php

namespace App\Http\Controllers;

use App\Models\EtiquetasG2;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEtiquetasG2Request;
use App\Http\Requests\UpdateEtiquetasG2Request;
use Illuminate\Support\Facades\Validator;
class EtiquetasG2Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('etiquetas_grafica2.listar_etiquetas2');
    }

    public function etiquetasG2(){

        $etiquetas2 = DB::table('etiquetas_grafica2')->orderBy('id','ASC')->get();
        return response()->json(array('etiquetas2' => $etiquetas2));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //CREANDO METODO PARA AGREGAR

        $validator =  Validator::make($request->all(), [
            'd1'=>'required',
            'd2'=>'required',
            'area'=>'required',
            'cip'=>'required',

        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        }
        else{
            $etiquetas2 = new EtiquetasG2;
            $etiquetas2->d1 =$request->input('d1');
            $etiquetas2->d2 =$request->input('d2');
            $etiquetas2->area =$request->input('area');
            $etiquetas2->idcip =$request->input('cip');
            $etiquetas2->save();
            return response()->json([
                'status'=>200,
                'message'=>'Etiqueta agregado con exito',
            ]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(EtiquetasG2 $etiquetasG2)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id2)
    {
        //CREANDO MEOTOD PARA EDITAR
        $etiquetas2 = EtiquetasG2::find($id2);
        if($etiquetas2){
            return response()->json([
                'status'=>200,
                'etiquetas2'=>$etiquetas2,
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
    public function update(Request $request, $id2)
    {
        //CREANDO METODO PARA ACTUALIZAR
        $validator2 =  Validator::make($request->all(), [
            'd1'=>'required',
            'd2'=>'required',
            'area'=>'required',
            'cip'=>'required',


        ]);

        if($validator2->fails())
        {
            return response()->json([
                'status' =>400,
                'errors' =>$validator2->messages(),
               ]);
        }
        else{
            $etiquetas2 = EtiquetasG2::find($id2);
            if($etiquetas2){
                $etiquetas2->d1 =$request->input('d1');
                $etiquetas2->d2 =$request->input('d2');
                $etiquetas2->area =$request->input('area');
                $etiquetas2->idcip =$request->input('cip');
                $etiquetas2->update();
                return response()->json([
                    'status'=>200,
                    'message'=>'Etiqueta actualizado con exito',

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id2)
    {
        //PARA ELIIMINAR
        $etiquetas2 = EtiquetasG2::find($id2);
        $etiquetas2->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Etiqueta eliminada',
        ]);
    }
}
