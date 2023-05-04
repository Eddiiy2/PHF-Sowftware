<?php

namespace App\Http\Controllers;

use App\Models\Tabla;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\App;


class TablaController extends Controller
{


    public function descargar($datos)
    {

        // $dompdf = new Dompdf();
        // $html = file_get_contents(resource_path('views/graficos/graficas.blade.php'));
        // $dompdf->loadHtml($html);
        // $dompdf->render();
        // $dompdf->stream();


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


        $view =  View::make('pdf', compact('graficar', 'ejes', 'infos', 'datostabla', 'points', 'labels_grap2', 'labels_grap1'))->render();
        $pdf = App::make('dompdf.wrapper');


        $pdf->setBasePath(public_path() . '/build/assets/');
        $pdf->setBasePath(public_path() . '/css/');

        $pdf->setPaper('A3', 'landscape');

        $pdf->setOptions([

            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'chroot' => public_path(),
            'debugCss' => true,
            'logOutputFile' => storage_path('logs/dompdf.log')
        ]);

        $pdf->loadHTML($view);
        return $pdf->stream('invoice.pdf');
    }

    public function realizar()
    {
        return view('realizar');
    }


    public function bienvenida()
    {
        $usuario = "clientes@apizaco.com";
        $encontrado = Usuario::where('correo', $usuario)->first();
        if (is_null($encontrado)) {
            //  return "USUARIO NO EXISTE";
            return redirect()->back();
        } else {
            $clave_dieron = "apizaco";
            $clave_guardada = $encontrado->password;

            if (Hash::check($clave_dieron, $clave_guardada)) {
                Auth::login($encontrado);
            }
        }

        return view('clientes.principal');
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
        return view('blog.principal');
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

            if (preg_replace('/[0-9]+/', '', $separados[0]) == "cip") {

                $datos[] = json_decode(json_encode(DB::select("select (select nombre from $tabla as t1 join $nom_cips as t2 on t1.idcip=t2.id group by nombre) cip,
                (select t2.id from $tabla as t1 join $nom_cips as t2 on t1.idcip=t2.id group by t2.id) idcip, idlavados, usuario, t3.equipo,
                min(fecha) f_inicial, max(fecha) f_final, (cast(min(fecha + hora::time) :: timestamp as time)) hora_inicial, (cast(max(fecha + hora::time) :: timestamp as time)) hora_final,
                (select max((fecha + hora::time)::timestamp) - min((fecha + hora::time)::timestamp) duracion), t2.tipo_cip from $tabla as t1 join $tipo_cip  as t2 on t1.tipo_cip = t2.valor
                join $equipo_cip as t3 on t1.equipo = t3.valor group by idlavados,t3.equipo,usuario,t2.valor,t3.valor having t2.valor != 0 and t3.valor != 0 order by idlavados;
                ")), true);
            } else {
                if (preg_replace('/[0-9]+/', '', $separados[0]) == "sp") {
                    $nom_sabores =  "" . $separados[0] . "_sabores_" . $separados[4];
                    $nom_equipos =  "" . $separados[0] . "_equipos_" . $separados[4];

                    $datos[] = json_decode(json_encode(DB::select("select (select nombre from $tabla as t1 join $nom_cips as t2 on t1.idsp=t2.id group by nombre) sp,
                    (select t2.id from $tabla as t1 join $nom_cips as t2 on t1.idsp=t2.id group by t2.id) idsp, idpreparacion,
                    usuario, t3.equipo, min(fecha) f_inicial, max(fecha) f_final, (cast(min(fecha + hora::time) :: timestamp as time)) hora_inicial, (cast(max(fecha + hora::time) :: timestamp as time)) hora_final,
                    (select max((fecha + hora::time)::timestamp) - min((fecha + hora::time)::timestamp) duracion), t2.sabor from $tabla as t1 join $nom_sabores as t2 on t1.sabor = t2.valor
                    join $nom_equipos as t3 on t1.equipo = t3.valor group by idpreparacion,t3.equipo,usuario,t2.valor,t1.equipo having t1.equipo != 0 order by idpreparacion;
                    ")), true);
                }
            }
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


        $graficar = [];
        $infos = [];
        $fecha = str_replace('-', '_', $separados[2]);
        $tabla = $separados[1] . "_" . str_replace("-", "_", $separados[2]) . "_cips";
        $tabla = $separados[1] . "_" . $fecha . "_" . $separados[4];
        $points = [];

        $titulopagina =  $separados[1] . "_" . $separados[3] . "_" . $fecha;


        if (preg_replace('/[0-9]+/', '', $separados[1]) == "cip") {
            $ejes = ['x' => "Horas", 'y' => "Temperaturas ", 'y2' => 'Conductividad'];

            $idlavados = $separados[0];
            $cip_secuencia = $separados[1] . "_secuencia_" . $separados[4];


            $marcas = DB::select("WITH verSecuencias AS (SELECT $cip_secuencia.secuencia,hora,Row_Number() OVER (ORDER BY hora) - Row_Number()
            OVER (PARTITION BY $cip_secuencia.secuencia ORDER BY hora) AS Seq FROM $tabla
            inner join $cip_secuencia on $tabla.secuencia = $cip_secuencia.valor where idlavados = $idlavados)
            SELECT verSecuencias.secuencia nombre, Min(hora) AS inicio, (Max(hora)+ INTERVAL '00:00:10') AS fin
            FROM verSecuencias, marcas where lower(verSecuencias.secuencia) = lower(marcas.nombre) GROUP BY verSecuencias.secuencia, Seq ORDER BY inicio");
            $marcas = json_decode(json_encode($marcas), true);

            foreach ($marcas as $marca) {
                $points[] = ['value' => $marca['inicio'], 'class' => 'black', 'text' => $marca['nombre']];
                $points[] = ['value' => $marca['fin'], 'class' => 'black'];
            }

            //$points = json_encode($points,true); No es necesario convertirlo a JSON aqui ya que en el blade se convertira
            //dd($points);

            $tipo_cip = $separados[1] . "_tipo_cip_" . $separados[4];
            $equipo = $separados[1] . "_equipo_" . $separados[4];

            $infos = DB::select("select (select max((fecha + hora::time)::timestamp) - min((fecha + hora::time)::timestamp) from $tabla where idlavados = $idlavados) duracion ,
            (select t2.tipo_cip maximo from $tabla as t1 inner join $tipo_cip as t2 on t1.tipo_cip=t2.valor where idlavados = $idlavados group by t2.valor having t2.tipo_cip != 'Ninguno' order by maximo asc limit 1) tipo_cip,
            (SELECT usuario FROM $tabla where idlavados = $idlavados limit 1 ) usuario,
            (SELECT t4.equipo FROM $tabla as t3 inner join $equipo as t4 on t3.equipo=t4.valor where idlavados = $idlavados order by t3.equipo desc limit 1 ) equipo,
            max(fecha) Fecha_final , min(fecha) Fecha_inicio, (cast(max(fecha + hora::time) :: timestamp as time)) Hora_final, (cast(min(fecha + hora::time) :: timestamp as time)) Hora_inicio from $tabla where idlavados = $idlavados;");
            $infos = json_decode(json_encode($infos), true);


            //Datos para la tabla
            $datostabla = DB::select("WITH verSecuencias AS (SELECT t2.secuencia nombre,((fecha + hora::time)::timestamp) hora, Row_Number() OVER (ORDER BY ((fecha + hora::time)::timestamp)) - Row_Number()
            OVER (PARTITION BY t2.secuencia ORDER BY hora) AS Seq FROM $tabla as t1 inner join $cip_secuencia as t2 on
            t1.secuencia = t2.valor where idlavados = 1) SELECT nombre, Min(hora) AS inicio,
            (Max(hora)+ INTERVAL '00:00:10') AS fin FROM verSecuencias GROUP BY nombre, Seq ORDER BY inicio");
            $datostabla = json_decode(json_encode($datostabla), true);

            $datostabladep = [];
            foreach ($datostabla as $dato) {
                $datostabladep[] = ['nombre' => $dato['nombre'], 'inicio' => (explode(" ", $dato['inicio']))[1], 'fin' => (explode(" ", $dato['fin']))[1]];
            }

            //dd($datostabladep);

            $sentencia = DB::select("select * from $tabla where idlavados=$idlavados");
            $datos  = (array) json_decode(json_encode($sentencia), true);

            // Variable para las letras mostradas  de HORAS y TEMPERATURA de las graficas

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

            return view('graficos.graficas', ['graficar' => $graficar, 'ejes' => $ejes, 'infos' => $infos, 'datostabla' => $datostabladep, 'points' => $points, 'labels_grap2' => $labels_grap2, 'labels_grap1' => $labels_grap1, 'titulopagina' => $titulopagina]);
        } else {
            // Condicional para las graficas del sistema de preparacion
            if (preg_replace('/[0-9]+/', '', $separados[1]) == "sp") {

                $idpreparacion = $separados[0];
                $marcas = [];
                $sabores = $separados[1] . "_sabores_" . $separados[4];
                $equipo = $separados[1] . "_equipos_" . $separados[4];


                $infos = DB::select("select (select max((fecha + hora::time)::timestamp) - min((fecha + hora::time)::timestamp) from $tabla where idpreparacion = $idpreparacion) duracion ,
                (select t2.sabor maximo from $tabla as t1 inner join $sabores as t2 on t1.sabor=t2.valor where idpreparacion = $idpreparacion group by t2.valor having t2.sabor != 'Seleccione un Sabor' order by maximo asc limit 1) tipo_cip,
                (SELECT usuario FROM $tabla where idpreparacion = $idpreparacion limit 1 ) usuario,
                (SELECT t4.equipo FROM $tabla as t3 inner join $equipo as t4 on t3.equipo=t4.valor where idpreparacion = $idpreparacion ORDER BY t4.equipo DESC limit 1) equipo,
                (SELECT agua_sp FROM $tabla order by agua_sp desc limit 1) agua_sp,
                (SELECT js_sp FROM $tabla order by agua_sp desc limit 1) js_sp,
                (SELECT hfcs_sp FROM $tabla order by agua_sp desc limit 1) hfcs_sp,
                max(fecha) Fecha_final , min(fecha) Fecha_inicio, (cast(max(fecha + hora::time) :: timestamp as time)) Hora_final, (cast(min(fecha + hora::time) :: timestamp as time)) Hora_inicio from $tabla where idpreparacion = $idpreparacion;");

                $infos = json_decode(json_encode($infos), true);
                //dd($infos);

                //Datos para la tabla
                $datostabla = [];
                //dd($datostabla);

                $sentencia = DB::select("select * from $tabla where idpreparacion=$idpreparacion");
                $datos  = (array) json_decode(json_encode($sentencia), true);

                // Variable para las letras mostradas  de HORAS y TEMPERATURA de las graficas

                $area = $separados[4];
                $idsp = $separados[5];


                // Obtniendo las etiquetas para la grafica 1 mostrada en la parte inferior del card
                $etiquetas_grap1 = DB::select("select * from etiquetas_grafica1 where area='$area' and idcip= $idsp;");
                $etiquetas_grap1 = json_decode(json_encode($etiquetas_grap1), true);
                // Obtniendo las etiquetas para la grafica 2 mostrada en la parte inferior del card
                $etiquetas_grap2 = DB::select("select * from etiquetas_grafica2 where area='$area' and idcip= $idsp;");
                $etiquetas_grap2 = json_decode(json_encode($etiquetas_grap2), true);



                //Etiquetas que se muestran en la grafica 1
                $labels_grap1 = [];
                $labels_grap1 = ['x' => $etiquetas_grap1[0]['d1'], 'y' => $etiquetas_grap1[0]['d2'], 'z' => $etiquetas_grap1[0]['d3']];
                //Etiquetas que se muestran en la grafica 2
                $labels_grap2 = [];


                $ejes = [];

                switch ($separados[1]) {
                    case 'sp01':
                        $ejes = ['x' => "Horas", 'y' => "Litros ", 'y2' => 'Kilogramos'];


                        $labels_grap2 = ['x' => $etiquetas_grap2[0]['d1'], 'y' => $etiquetas_grap2[0]['d2'], 'z' => $etiquetas_grap2[0]['d3']];
                        foreach ($datos as $dato) {
                            $graficar[] = [
                                'horas' => $dato['hora'],
                                $etiquetas_grap1[0]['d1'] => $dato['agua_sp'], $etiquetas_grap1[0]['d2'] => $dato['agua_acc'], $etiquetas_grap1[0]['d3'] => $dato['agua_vel'],
                                $etiquetas_grap2[0]['d1'] => $dato['hfcs_sp'], $etiquetas_grap2[0]['d2'] => $dato['hfcs_acc'], $etiquetas_grap2[0]['d3'] => $dato['hfcs_vel']
                            ];
                        }

                        break;
                    case 'sp02':
                        $ejes = ['x' => "Horas", 'y' => "Litros ", 'y2' => 'Kilogramos'];

                        $labels_grap2 = ['x' => $etiquetas_grap2[0]['d1'], 'y' => $etiquetas_grap2[0]['d2'], 'z' => $etiquetas_grap2[0]['d3']];
                        foreach ($datos as $dato) {
                            $graficar[] = [
                                'horas' => $dato['hora'],
                                $etiquetas_grap1[0]['d1'] => $dato['agua_sp'], $etiquetas_grap1[0]['d2'] => $dato['agua_acc'], $etiquetas_grap1[0]['d3'] => $dato['agua_vel'],
                                $etiquetas_grap2[0]['d1'] => $dato['js_sp'], $etiquetas_grap2[0]['d2'] => $dato['js_acc'], $etiquetas_grap2[0]['d3'] => $dato['js_vel']
                            ];

                            //dd($graficar);
                        }
                        break;
                    case 'sp03':
                        $ejes = ['x' => "Horas", 'y' => "Litros ", 'y2' => 'Kilogramos'];


                        foreach ($datos as $dato) {
                            $graficar[] = [
                                'horas' => $dato['hora'], $etiquetas_grap1[0]['d1'] => $dato['agua_sp'], $etiquetas_grap1[0]['d2'] => $dato['agua_acc'], $etiquetas_grap1[0]['d3'] => $dato['agua_vel']
                            ];
                        }
                        break;
                }

                return view('graficos.sp', ['graficar' => $graficar, 'ejes' => $ejes, 'infos' => $infos, 'datostabla' => $datostabla, 'points' => $points, 'labels_grap2' => $labels_grap2, 'labels_grap1' => $labels_grap1, 'sp' => $separados[1], 'titulopagina' => $titulopagina]);
            }
        }
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


    public function consultaridcip($datos)
    {
        $separados = explode("_", $datos);
        $nombre = $separados[0];
        $area = $separados[1];

        $nom = DB::select("select nombre_real from nom_cips_$area where nombre='$nombre';");

        if ($nom != null) {
            return json_encode($nom);
        } else {
            return response()->json(['estado' => 'Fallido'], 200);
        }
    }


    public function indextime($datos)
    {

        $graficar = [];
        $ejes = ['x' => "Horas", 'y' => "Temperaturas ", 'y2' => 'Conductividad'];
        $infos = [];
        $points = [];

        $separados = explode("=", $datos); // Separando el string por = en substrings
        $separados = str_replace(' ', '', $separados); // Eliminando los espacios en blanco que hay en cada palabra
        $cip = $separados[0];
        $area = $separados[1];
        $fecha = $separados[2];

        $letra = preg_replace('/[0-9]+/', '', $cip);
        $num = preg_replace("/[A-Za-z]+/", '', $cip);
        $nomtabla = $letra . "_" . $num;

        // [0] -> cip
        // [1] -> area
        // [2] -> fecha tabla

        $titulopagina =  $cip . "_" . $fecha;
        $secuencia = $cip . "_secuencia_" . $area;

        $marcas = DB::select("WITH verSecuencias AS (SELECT $secuencia.secuencia, cast(t_stamp :: timestamp(0) as time) as hora, Row_Number() OVER (ORDER BY t_stamp) - Row_Number()
        OVER (PARTITION BY $secuencia.secuencia ORDER BY t_stamp) AS Seq FROM $nomtabla
        inner join $secuencia on $nomtabla.secuencia = $secuencia.valor)
        SELECT verSecuencias.secuencia nombre, Min(hora) AS inicio, (Max(hora)+ INTERVAL '00:00:10') AS fin
        FROM verSecuencias, marcas where lower(verSecuencias.secuencia) = lower(marcas.nombre) GROUP BY verSecuencias.secuencia, Seq ORDER BY inicio");
        $marcas = json_decode(json_encode($marcas), true);

        foreach ($marcas as $marca) {
            $points[] = ['value' => $marca['inicio'], 'class' => 'black', 'text' => $marca['nombre']];
            $points[] = ['value' => $marca['fin'], 'class' => 'black'];
        }

        $tipo_cip = $cip . "_tipo_cip_" . $area;
        $equipo = $cip . "_equipo_" . $area;

        $infos = DB::select("select (select max(cast(t_stamp :: timestamp(0) as time)) - min(cast(t_stamp :: timestamp(0) as time)) from $nomtabla) duracion ,
        (select t2.tipo_cip maximo from $nomtabla as t1 inner join $tipo_cip as t2 on t1.tipo_cip=t2.valor group by t2.valor having t2.tipo_cip != 'Ninguno' order by maximo asc limit 1) tipo_cip,
        (SELECT usuario FROM $nomtabla limit 1 ) usuario,
        (SELECT t4.equipo FROM $nomtabla as t3 inner join $equipo as t4 on t3.equipo=t4.valor limit 1 ) equipo,
        max(DATE(t_stamp)) Fecha_final , min(DATE(t_stamp)) Fecha_inicio, max(cast(t_stamp :: timestamp(0) as time)) Hora_final, min(cast(t_stamp :: timestamp(0) as time)) Hora_inicio from cip_01;");
        $infos = json_decode(json_encode($infos), true);

        //Datos para la tabla
        $datostabla = DB::select("WITH verSecuencias AS (SELECT t2.secuencia nombre, cast(t_stamp :: timestamp(0) as time) as hora, Row_Number() OVER (ORDER BY t_stamp) - Row_Number()
        OVER (PARTITION BY t2.secuencia ORDER BY t_stamp) AS Seq FROM $nomtabla as t1 inner join $secuencia as t2 on
        t1.secuencia = t2.valor ) SELECT nombre, Min(hora) AS inicio,
        (Max(hora)+ INTERVAL '00:00:10') AS fin FROM verSecuencias GROUP BY nombre, Seq ORDER BY inicio");
        $datostabla = json_decode(json_encode($datostabla), true);

        $sentencia = DB::select("select id,conductividad,equipo,secuencia,tipo_cip,temp_ret,temp_sal,usuario,idcip,cast(t_stamp :: timestamp(0) as time) hora,ozono_tqs_horizontal, ozono_lineas from $nomtabla;");
        $datos  = (array) json_decode(json_encode($sentencia), true);

        // Obtniendo las etiquetas para la grafica 2 mostrada en la parte inferior del card
        $etiquetas_grap2 = DB::select("select * from etiquetas_grafica2 where area='$area' and idcip= (select idcip from $nomtabla order by idcip desc limit 1);");
        $etiquetas_grap2 = json_decode(json_encode($etiquetas_grap2), true);

        // Obtniendo las etiquetas para la grafica 1 mostrada en la parte inferior del card
        $etiquetas_grap1 = DB::select("select * from etiquetas_grafica1 where area='$area' and idcip= (select idcip from $nomtabla order by idcip desc limit 1);");
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

        return json_encode([$infos, $datostabla, $graficar, $ejes, $points, $labels_grap2,  $labels_grap1, $titulopagina]);
        // return view('graficos.cipsTiempoReal', ['graficar' => $graficar, 'ejes' => $ejes, 'points' => $points, 'labels_grap2' => $labels_grap2, 'labels_grap1' => $labels_grap1, 'titulopagina' => $titulopagina]);

        //return view('graficos.cipsTiempoReal', ['graficar' => $graficar, 'ejes' => $ejes, 'infos' => $infos, 'datostabla' => $datostabla, 'points' => $points, 'labels_grap2' => $labels_grap2, 'labels_grap1' => $labels_grap1, 'titulopagina' => $titulopagina]);


    }

    public function viewtime($datos)
    {
        return view('graficos.cipsTiempoReal', ['datos' => $datos]);
    }
}
