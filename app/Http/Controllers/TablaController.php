<?php

namespace App\Http\Controllers;

use App\Models\Tabla;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class TablaController extends Controller
{

    public function descargar()
    {
        // $dompdf = new Dompdf();
        // $html = file_get_contents(resource_path('views/graficos/graficas.blade.php'));
        // $dompdf->loadHtml($html);
        // $dompdf->render();
        // $dompdf->stream();
        $data1 = [
            'title' => 'Mi archivo PDF',
            'content' => 'Este es un ejemplo de Laravel-dompdf.',
        ];
        $data2 = [
            'author' => 'John Doe',
            'date' => '11/04/2023',
        ];
        $pdf = PDF::loadView('pdf.template', compact('data1', 'data2'));
        return $pdf->download('mi-archivo.pdf');



    }

  

    public function principal($nom)
    {
        // // $nom = cip(n)_area(n)
        $separados = explode("_", $nom);

        $cipObtenido = $separados[0];
        $areaObtenida = $separados[1];
        $nombreArea = $separados[2];

        //dd($nombreArea);
        //Obteniendo las tablas de los cips
        $tables = DB::select("SELECT * FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema';");
        $cips = (array) json_decode(DB::table("nom_cips_$areaObtenida")->get(), true);

        //Obteniendo los nombre de los cips de las tablas correspondientes segund su area
        $indices = DB::select("SELECT * FROM nom_cips_$areaObtenida where nombre='$cipObtenido'");
        $indices = json_decode(json_encode($indices), true);


        return view('clientes.index', ['cips' => $cips, 'indices' => $indices, 'nomarea' => $nombreArea, 'area' => $areaObtenida]);
    }

    public function index()
    {

        $tables = DB::select("SELECT * FROM pg_catalog.pg_tables WHERE schemaname != 'pg_catalog' AND schemaname != 'information_schema';");
        //dd($tables);

        $cips = (array) json_decode(DB::table('nom_cips')->get(), true);
        $equipos = (array) json_decode(DB::table('equipos')->get(), true);

        return view('clientes.index', ['cips' => $cips, 'equipos' => $equipos]);

        //return view('graficos.hola');
    }

    public function buscar($dbtabla)
    {
        $consultas = DB::select($dbtabla);
        $consultas = json_decode(json_encode($consultas), true);

        $datos = [];

        foreach ($consultas as $consulta) {
            $tabla = $consulta['tablename'];
            $separados = explode("_", $tabla);

            // $separados[0] -> cip01
            // $separados[1] -> año
            // $separados[2] -> mes
            // $separados[3] -> dia
            // $separados[4] -> area

            $tipo_cip = "" . $separados[0] . "_tipo_cip_" . $separados[4];
            $equipo_cip = "" . $separados[0] . "_equipo_" . $separados[4];
            $nom_cips = "nom_cips_" . $separados[4];

            $datos[] = json_decode(json_encode(DB::select("select (select nombre from $tabla as t1 join $nom_cips as t2 on t1.idcip=t2.id group by nombre) cip, (select t2.id from $tabla as t1 join $nom_cips as t2 on t1.idcip=t2.id group by t2.id) idcip, idlavados, usuario, t3.equipo, min(fecha) f_inicial, max(fecha) f_final, min(hora) hora_inicial, max(hora) hora_final,
            (select max(hora)-min(hora) duracion), t2.tipo_cip from $tabla as t1 join $tipo_cip as t2 on t1.tipo_cip = t2.valor join $equipo_cip as t3 on t1.equipo = t3.valor group by idlavados,t3.equipo,usuario,t2.valor having t2.valor != 0 order by idlavados;
            ")), true);
        }

        if ($consultas != null) {
            return json_encode($datos);
        } else {
            return response()->json(['estado' => 'Fallido'], 200);
        }
    }


    public function show($datos)
    {
        $separados = explode("=", $datos); // Separando el string por = en substrings
        $separados = str_replace(' ', '', $separados); // Eliminando los espacios en blanco que hay en cada palabra

        // [0] -> idlavados
        // [1] -> nombre del cip
        // [2] -> fecha tabla
        // [3] -> equipo
        // [4] -> area
        // [5] -> idcip


        $fecha = str_replace('-', '_', $separados[2]);

        $tabla = $separados[1] . "_" . str_replace("-", "_", $separados[2]) . "_cips";
        $idlavados = $separados[0];
        //dd($separados[1]);
        $cip_secuencia = $separados[1] . "_secuencia_" . $separados[4];
        $tabla = $separados[1] . "_" . $fecha . "_" . $separados[4];


        $marcas = DB::select("WITH verSecuencias AS (SELECT $cip_secuencia.secuencia,hora,Row_Number() OVER (ORDER BY hora) - Row_Number()
        OVER (PARTITION BY $cip_secuencia.secuencia ORDER BY hora) AS Seq FROM $tabla
        inner join $cip_secuencia on $tabla.secuencia = $cip_secuencia.valor where idlavados = $idlavados)
        SELECT verSecuencias.secuencia nombre, Min(hora) AS inicio, (Max(hora)+ INTERVAL '00:00:10') AS fin
        FROM verSecuencias, marcas where lower(verSecuencias.secuencia) = lower(marcas.nombre) GROUP BY verSecuencias.secuencia, Seq ORDER BY inicio");
        $marcas = json_decode(json_encode($marcas), true);

        $points = [];
        foreach ($marcas as $marca) {
            $points[] = ['value' => $marca['inicio'], 'class' => 'black', 'text' => $marca['nombre']];
            $points[] = ['value' => $marca['fin'], 'class' => 'black'];
        }
        //$points = json_encode($points,true); No es necesario convertirlo a JSON aqui ya que en el blade se convertira
        //dd($points);

        $tipo_cip = $separados[1] . "_tipo_cip_" . $separados[4];
        $equipo = $separados[1] . "_equipo_" . $separados[4];

        $infos = DB::select("select (select max(hora) - min(hora) from $tabla where idlavados = $idlavados) duracion ,
        (select t2.tipo_cip maximo from $tabla as t1 inner join $tipo_cip as t2 on t1.tipo_cip=t2.valor where idlavados = $idlavados group by t2.valor having t2.tipo_cip != 'Ninguno' order by maximo asc limit 1) tipo_cip,
        (SELECT usuario FROM $tabla where idlavados = $idlavados limit 1 ) usuario,
        (SELECT t4.equipo FROM $tabla as t3 inner join $equipo as t4 on t3.equipo=t4.valor where idlavados = $idlavados limit 1 ) equipo,
        max(fecha) Fecha_final , min(fecha) Fecha_inicio, max(hora) Hora_final, min(hora) Hora_inicio from $tabla where idlavados = $idlavados;");
        $infos = json_decode(json_encode($infos), true);


        //Datos para la tabla
        $datostabla = DB::select("WITH verSecuencias AS (SELECT t2.secuencia nombre,hora, Row_Number() OVER (ORDER BY hora) - Row_Number()
        OVER (PARTITION BY t2.secuencia ORDER BY hora) AS Seq FROM $tabla as t1 inner join $cip_secuencia as t2 on
        t1.secuencia = t2.valor where idlavados = 1) SELECT nombre, Min(hora) AS inicio,
        (Max(hora)+ INTERVAL '00:00:10') AS fin FROM verSecuencias GROUP BY nombre, Seq ORDER BY inicio");


        $datostabla = json_decode(json_encode($datostabla), true);
        //dd($datostabla);

        $sentencia = DB::select("select * from $tabla where idlavados=$idlavados");
        $datos  = (array) json_decode(json_encode($sentencia), true);

        $graficar = [];
        // Variable para las letras mostradas  de HORAS y TEMPERATURA de las graficas
        $ejes = ['x' => "Horas", 'y' => "Temperaturas ", 'y2' => 'Conductividad'];

        $area = $separados[4];
        $idcip = $separados[5];

        // Obtniendo las etiquetas para la grafica 2 mostrada en la parte inferior del card
        $etiquetas_grap2 = DB::select("select * from etiquetas_grafica2 where area='$area' and idcip= $idcip;");
        $etiquetas_grap2 = json_decode(json_encode($etiquetas_grap2), true);

        // Obtniendo las etiquetas para la grafica 1 mostrada en la parte inferior del card
        $etiquetas_grap1 = DB::select("select * from etiquetas_grafica1 where area='$area' and idcip= $idcip;");
        $etiquetas_grap1 = json_decode(json_encode($etiquetas_grap1), true);
        //dd($etiquetas_grap1[0]['d1']);

        //Etiquetas que se muestran en la grafica 2
        $labels_grap2 = [];
        $labels_grap2[] = ['x' => $etiquetas_grap2[0]['d1'], 'y' => $etiquetas_grap2[0]['d2']];
        //Etiquetas que se muestran en la grafica 1
        $labels_grap1 = [];
        $labels_grap1[] = ['x' => $etiquetas_grap1[0]['d1'], 'y' => $etiquetas_grap1[0]['d2'], 'z' => $etiquetas_grap1[0]['d3']];


        foreach ($datos as $dato) {
            $graficar[] = [
                'horas' => $dato['hora'], $etiquetas_grap2[0]['d1'] => $dato['temp_ret'], $etiquetas_grap2[0]['d2'] => $dato['temp_sal'],
                $etiquetas_grap1[0]['d1'] => $dato['conductividad'], $etiquetas_grap1[0]['d2'] => $dato['ozono_lineas'], $etiquetas_grap1[0]['d3'] => $dato['ozono_tqs_horizontal']
            ];
        }

        return view('graficos.graficas', ['graficar' => $graficar, 'ejes' => $ejes, 'infos' => $infos, 'datostabla' => $datostabla, 'points' => $points, 'labels_grap2' => $labels_grap2, 'labels_grap1' => $labels_grap1]);
    }

    // Metodo para obtener los cips de cada area y mostrarlos en el navbar para la visualizacion del cliente
    public function nomcips($n)
    {
        $cips = DB::select("select * from nom_cips_area$n;");

        if ($cips != null) {
            return json_encode($cips);
        } else {
            return response()->json(['estado' => 'Fallido'], 200);
        }
    }

    // Obteniendo el numero de divs que es igual al numero de cips que se mostraran en el navbar
    public function obtenerdivs()
    {
        $divs = DB::select("select * from view_cips_navbar;");

        if ($divs != null) {
            return json_encode($divs);
        } else {
            return response()->json(['estado' => 'Fallido'], 200);
        }
    }
}
