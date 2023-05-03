<?php

namespace App\Http\Controllers;

use App\Models\EtiquetasG1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreEtiquetasG1Request;
use App\Http\Requests\UpdateEtiquetasG1Request;
use Illuminate\Support\Facades\Validator;
class EtiquetasG1Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view("etiquetas_grafica1.listar_etiquetas1");
    }

    public function etiquetasG1(){

        $etiquetas1 = DB::table('etiquetas_grafica1')->get();
        return  response()->json(array('etiquetas1'=>$etiquetas1));

    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator1 =  Validator::make($request->all(), [
            'd1_1'=>'required',
            'd2_1'=>'required',
            'd3_1'=>'required',
            'area_1'=>'required',
            'cip_1'=>'required',

        ]);
        if($validator1->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator1->messages(),
            ]);
        }else{
            $etiquetas1 = new EtiquetasG1;
            $etiquetas1->d1 =$request->input('d1_1');
            $etiquetas1->d2 =$request->input('d2_1');
            $etiquetas1->d3 =$request->input('d3_1');
            $etiquetas1->area =$request->input('area_1');
            $etiquetas1->idcip =$request->input('cip_1');
            $etiquetas1->save();
            return response()->json([
                'status'=>200,
                'message'=>'Etiqueta agregado con exito',
            ]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(EtiquetasG1 $etiquetasG1)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id1)
    {
        $etiquetas1 = EtiquetasG1::find($id1);
        if($etiquetas1){
            return response()->json([
                'status'=>200,
                'etiquetas1'=>$etiquetas1,
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
    public function update(Request $request, $id1)
    {
        $validator1 =  Validator::make($request->all(), [
            'd1_1'=>'required',
            'd2_1'=>'required',
            'd3_1'=>'required',
            'area_1'=>'required',
            'cip_1'=>'required',

        ]);
        if($validator1->fails())
        {
            return response()->json([
                'status'=>400,
                'errors'=>$validator1->messages(),
            ]);
        }
        else{
            $etiquetas1 = EtiquetasG1::find($id1);
            if($etiquetas1){
                $etiquetas1->d1 =$request->input('d1_1');
                $etiquetas1->d2 =$request->input('d2_1');
                $etiquetas1->d3 =$request->input('d2_1');
                $etiquetas1->area =$request->input('area_1');
                $etiquetas1->idcip =$request->input('cip_1');
                $etiquetas1->update();
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
    public function destroy($id1)
    {
        $etiquetas1 = EtiquetasG1::find($id1);
        $etiquetas1->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Etiqueta eliminada',
        ]);
    }
}
