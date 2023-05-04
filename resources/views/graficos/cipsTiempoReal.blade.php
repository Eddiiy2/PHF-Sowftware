<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title id="titulopagina"> PHF SOFTWARE </title>

<link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/c3.css') }}">
<link rel="stylesheet" href="{{ asset('css/all.css') }}">
{{--  <link rel="stylesheet" href="{{ asset('css/boostrap502.css') }}">  --}}


{{--  Importando js de C3 para las graficas y Ajax para la consulta de la tabla index  --}}
<script src="{{ URL::asset('js/c3.js') }}"></script>
<script src="{{ URL::asset('js/d3-v5-min.js') }}"></script>
<script src="{{ URL::asset('build/assets/app.js') }}"></script>
<script src="{{ URL::asset('js/ajax.js') }}"></script>
<script src="{{ URL::asset('js/all.js') }}"></script>


{{--  -----------------------------------------------------------------------  --}}


<div class="row mt-1" style="width:100%; height:100%;" id="photo">

    {{--  col-12 para vista en celular
            col-lg-6 Para vista en tablet
            col-xxl-3 para vista en pc  --}}

    <div class="col-3 col-lg-2 col-xxl-2 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header bg-dark bg-gradient text-light">
                <span> DATOS </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <strong style="display: none" id="datos"> {{ $datos }} </strong>


                    <ul class="list-group" style='font-size: 10px' id="listainfo">
                        {{--  @foreach ($infos as $info)
                            <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                                <span>{{ $info['fecha_inicio'] }}</span>
                            </li>
                            <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                                {{ $info['fecha_final'] }}
                            </li>
                            <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                                {{ $info['hora_inicio'] }}
                            </li>
                            <li class="list-group-item p-1"> <strong> Hora final: </strong>
                                {{ $info['hora_final'] }}
                            </li>
                            <li class="list-group-item p-1"> <strong> Duracion: </strong> {{ $info['duracion'] }}
                            </li>
                            <li class="list-group-item p-1"> <strong> Tipo de cip: </strong>
                                {{ $info['tipo_cip'] }}
                            </li>
                            <li class="list-group-item p-1"> <strong> Usuario: </strong> {{ $info['usuario'] }}
                            </li>
                            <li class="list-group-item p-1"> <strong> Equipo: </strong> {{ $info['equipo'] }} </li>
                        @endforeach  --}}
                    </ul>
                </div>
                <br>
                <div class="row">
                    {{--  height:360px;overflow:auto;  --}}
                    {{--  <div class="card-body" id="taula">  --}}
                    <table class="table table-borderless table-responsive" style='font-size: 10px'>
                        <thead>
                            <tr class="table-secondary">
                                <th>PASO DE CIP</th>
                                <th>INICIO</th>
                                <th>FINAL</th>
                            </tr>
                        </thead>
                        <tbody id="datostabla">
                            {{--  @foreach ($datostabla as $datotabla)
                                <tr>
                                    <td class="p-1">{{ $datotabla['nombre'] }} </td>
                                    <td class="p-1"> {{ $datotabla['inicio'] }} </td>
                                    <td class="p-1"> {{ $datotabla['fin'] }} </td>

                                </tr>
                            @endforeach  --}}
                        </tbody>
                    </table>
                    {{--  </div>  --}}
                    {{--  <button id="pdf-generate">imprimir</button>  --}}
                    {{-- onclick="printDiv('photo')"  --}}
                </div>


            </div>
        </div>
    </div>

    <div class="col-9 col-lg-10 col-xxl-10 d-flex ">
        {{--  <div class="card flex-fill w-100  h-100">  --}}
        <div class="card">
            <div class="card-header bg-dark bg-gradient text-light">
                <span> GRAFICAS </span>
            </div>
            <div class="card-body">
                <div class="contenedor" id="chart_conductividad"></div>
                <div class="contenedor" id="dbchart"></div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        llamar();
    });

    function llamar() {
        setInterval(function() {
            //alert("imprimiendo cada 5 seg...");

            let datos = document.querySelector("#datos").innerText.trim();

            fetch('/timereal/ ' + datos)
                .then(response => response.json())
                .then(respuesta => {


                    listainfo.innerHTML = ''
                    listainfo.innerHTML += `
                    <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                        <span> ${ respuesta[0][0].fecha_inicio }</span>
                    </li>
                    <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                        ${ respuesta[0][0].fecha_final }
                    </li>
                    <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                        ${ respuesta[0][0].hora_inicio }
                    </li>
                    <li class="list-group-item p-1"> <strong> Hora final: </strong>
                        ${ respuesta[0][0].hora_final }
                    </li>
                    <li class="list-group-item p-1"> <strong> Duracion: </strong> ${ respuesta[0][0].duracion }
                    </li>
                    <li class="list-group-item p-1"> <strong> Tipo de cip: </strong>
                        ${ respuesta[0][0].tipo_cip }
                    </li>
                    <li class="list-group-item p-1"> <strong> Usuario: </strong> ${ respuesta[0][0].usuario }
                    </li>
                    <li class="list-group-item p-1"> <strong> Equipo: </strong> ${ respuesta[0][0].equipo } </li>
                    `;

                    datostabla.innerHTML = ''
                    for (let i = 0; i < respuesta[1].length; i++) {
                        // console.log(respuesta[1][i]);
                        datostabla.innerHTML += `
                        <tr>
                            <td class="p-1">${ respuesta[1][i].nombre }</td>
                            <td class="p-1">${ respuesta[1][i].inicio }</td>
                            <td class="p-1">${ respuesta[1][i].fin }</td>

                        </tr>
                    `;
                    }

                    primerGrafica(respuesta);
                    segundaGrafica(respuesta);
                    //console.log(respuesta[3].y);


                });

            function primerGrafica(respuesta) {

                var puntos = respuesta[2]; // Puntos para graficar
                var points = respuesta[4]; // Puntos de las marcas
                var labels = respuesta[6]; // Nombre de las plumas
                var x = respuesta[3].x; //Etiqueta para el eje x
                var y = respuesta[3].y2; // Etiqueta para el eje y


                var chart = c3.generate({

                    bindto: '#chart_conductividad',
                    data: {
                        json: puntos,
                        keys: {
                            x: 'horas',
                            value: [labels[0].x, labels[0].y, labels[0].z],
                        }
                    },

                    point: {
                        r: 0,
                        //show: false,
                        focus: {
                            expand: {
                                enabled: true,
                                r: 5
                            }
                        },
                    },
                    axis: {
                        x: {

                            type: 'categories', //timeseries
                            tick: {

                                centered: true,
                                format: '%H:%M:%S',
                                rotate: 0,
                                multiline: false,
                                fit: true, // Los labels se adaptan al ancho de la pantalla
                                culling: true,
                                outer: false,
                                culling: {
                                    max: window.innerWidth > 800 ? 10 : 4
                                },


                            },

                            height: 45,

                            label: { // ADD
                                text: x,
                                position: 'middle'
                            }
                        },
                        y: {
                            label: { // ADD
                                text: y,
                                position: 'outer-middle'
                            },


                        }

                    },
                    grid: {
                        x: {
                            //show: true,
                            lines: points

                            //lines: [{value: 2}, {value: 4, class: 'grid4', text: 'LABEL 4'} ]
                        },
                        y: {
                            //show: true
                        }
                    },

                    onresized: function() {

                        window.innerWidth > 800 ? chart.internal.config.axis_x_tick_culling_max =
                            8 : chart.internal
                            .config.axis_x_tick_culling_max = 4;
                    },

                    onrendered: function() {

                        // for each svg element with the class 'c3-xgrid-line'
                        d3.selectAll('.c3-xgrid-line').each(function(d, i) {

                            // cache the group node
                            var groupNode = d3.select(this).node();

                            // for each 'text' element within the group
                            d3.select(this).select('text').each(function(d, i) {

                                // hide the text to get size of group box otherwise text affects size.
                                d3.select(this).attr("hidden", true);

                                // use svg getBBox() func to get the group size without the text - want the position
                                var groupBx = groupNode.getBBox();

                                d3.select(this)
                                    .attr('transform', null) // remove text rotation
                                    .attr('x', groupBx
                                        .x) // x-offset from left of chart
                                    .attr('y',
                                        0
                                    ) // y-offset of the text from the top of the chart
                                    .attr('dx',
                                        5) // small x-adjust to clear the line
                                    .attr('dy',
                                        15) // small y-adjust to get onto the chart
                                    .attr("hidden",
                                        null) // better make the text visible again
                                    .attr("text-anchor",
                                        null) // anchor to left by default
                                    .style('fill', 'black'); // color it red for fun
                            })
                        })
                    }

                });

            }

            function segundaGrafica(respuesta) {

                var puntos = respuesta[2]; // Puntos para graficar
                var points = respuesta[4]; // Puntos de las marcas
                var labels = respuesta[5]; // Nombre de las plumas
                var x = respuesta[3].x; //Etiqueta para el eje x
                var y = respuesta[3].y; // Etiqueta para el eje y

                var chart = c3.generate({

                    bindto: '#dbchart',
                    data: {
                        json: puntos,
                        keys: {
                            x: 'horas',
                            value: [labels[0].x, labels[0].y]
                        }
                    },

                    point: {
                        r: 0,
                        //show: false,
                        focus: {
                            expand: {
                                enabled: true,
                                r: 5
                            }
                        },
                    },
                    axis: {
                        x: {

                            type: 'categories', //timeseries
                            tick: {

                                //count:3
                                centered: true,
                                format: '%H:%M:%S',
                                rotate: 0,
                                multiline: false,
                                fit: true, // Los labels se adaptan al ancho de la pantalla
                                culling: true,
                                outer: false,
                                culling: {
                                    max: window.innerWidth > 800 ? 10 : 4
                                },

                            },

                            height: 45,

                            label: { // ADD
                                text: x,
                                position: 'middle'
                            }
                        },
                        reotated: true,
                        y: {

                            padding: {
                                top: 0,
                                bottom: 0
                            },



                            label: { // ADD

                                text: y,
                                position: 'outer-middle'
                            },


                        }

                    },
                    grid: {
                        x: {
                            //show: true,
                            lines: points
                        },
                        y: {
                            //show: true
                        }
                    },

                    onresized: function() {

                        window.innerWidth > 800 ? chart.internal.config.axis_x_tick_culling_max =
                            8 : chart.internal
                            .config.axis_x_tick_culling_max = 4;
                    },

                    onrendered: function() {

                        // for each svg element with the class 'c3-xgrid-line'
                        d3.selectAll('.c3-xgrid-line').each(function(d, i) {

                            // cache the group node
                            var groupNode = d3.select(this).node();

                            // for each 'text' element within the group
                            d3.select(this).select('text').each(function(d, i) {

                                // hide the text to get size of group box otherwise text affects size.
                                d3.select(this).attr("hidden", true);

                                // use svg getBBox() func to get the group size without the text - want the position
                                var groupBx = groupNode.getBBox();

                                d3.select(this)
                                    .attr('transform', null) // remove text rotation
                                    .attr('x', groupBx
                                        .x) // x-offset from left of chart
                                    .attr('y',
                                        0
                                    ) // y-offset of the text from the top of the chart
                                    .attr('dx',
                                        5) // small x-adjust to clear the line
                                    .attr('dy',
                                        15) // small y-adjust to get onto the chart
                                    .attr("hidden",
                                        null) // better make the text visible again
                                    .attr("text-anchor",
                                        null) // anchor to left by default
                                    .style('fill', 'black'); // color it red for fun
                            })
                        })
                    }
                });
            }

        }, 2000)

    }
</script>
