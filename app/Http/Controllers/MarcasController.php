<?php

namespace App\Http\Controllers;

use App\Models\Marcas;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class MarcasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('marcas.listar_marcas');
    }


    public function marcas()
    {
        $marcas = DB::table('marcas')->OrderBy('idmarca', 'ASC')->get();
        return response()->json(array('marcas' =>$marcas));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'=> 'required',
            'area' => 'required',
            'cip' => 'required',

        ]);

        if($validator->fails())
        {

            return response()->json([
                'status'=>400,
                'errors'=>$validator->messages(),
            ]);
        } else {
            $marcas = new Marcas;
            $marcas->nombre = $request->input('nombre');
            $marcas->area = $request->input('area');
            $marcas->idcip = $request->input('cip');
            $marcas->save();
            return response()->json([
                'status'=>200,
                'message'=>'Marca agregado con exito',
            ]);

        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Marcas $marcas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $marcas = Marcas::find($id);
        if($marcas){
            return response()->json([
                'status'=>200,
                'marcas'=>$marcas,
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
    public function update(Request $request,$id)
    {
        //CREANDO METODO PARA ACTUALIZAR
        $validator =  Validator::make($request->all(), [
            'nombre'=> 'required',
            'area' => 'required',
            'cip' => 'required',

        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' =>400,
                'errors' =>$validator->messages(),
               ]);
        }
        else{
            $marcas = Marcas::find($id);
            if($marcas){
                $marcas->nombre =$request->input('nombre');
                $marcas->area =$request->input('area');
                $marcas->idcip =$request->input('cip');

                $marcas->update();
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
    public function destroy(Marcas $marcas)
    {
        //
    }
}
