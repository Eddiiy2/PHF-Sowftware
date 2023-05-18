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
                    <span
                        style="font-family:Lobster; font-weight: 600; font-size: 18px; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); text-align: center">{{ $planta[0]['nombre'] }}</span>
                    <span
                        style="font-family:Lobster; color: rgb(0, 0, 0); text-align: center; font-weight: 600; margin-top:5px;">{{ $planta[0]['sucursal'] }}</span>

                    <br><br>

                    <strong style="display: none" id="datos"> {{ $datos }} </strong>
                    <ul class="list-group" style='font-size: 10px' id="listainfo">

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
        primeraInstancia()
        llamar();
    });

    function cerrar() {
        window.open('', '_parent', '');
        window.close();
    }

    function primeraInstancia() {
        let datos = document.querySelector("#datos").innerText.trim();

        fetch('/timereal/ ' + datos)
            .then(response => response.json())
            .then(respuesta => {


                listainfo.innerHTML = ''
                listainfo.innerHTML += `
                <li class="list-group-item p-1">
                    <span><strong> Proceso: </strong></span>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>{{ $nomcip }}</span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>   ${ respuesta[0][0].fecha_inicio } </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].fecha_final }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].hora_inicio }  </span>
                    </div>

                </li>
                <li class="list-group-item p-1"> <strong> Hora final: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].hora_final }</span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Duracion: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].duracion }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Tipo de cip: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${ respuesta[0][0].tipo_cip } </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Usuario: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].usuario }  </span>
                    </div>

                </li>
                <li class="list-group-item p-1"> <strong> Equipo: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].equipo }  </span>
                    </div>

                </li>
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
            var points = respuesta[3]; // Puntos de las marcas
            var x = respuesta[2].x; //Etiqueta para el eje x
            var y = respuesta[2].y2; // Etiqueta para el eje y
            var horas = respuesta[7]; // Definiendo el rango de horas de la X
            var conductividad = respuesta[8]; //Priemera pluma de la grafica 1
            var ozono_lineas = respuesta[9]; //Segunda pluma de la grafica 1
            var ozono_hori = respuesta[10]; //Tercera pluma de la grafica 1

            var chart = c3.generate({

                bindto: '#chart_conductividad',
                data: {
                    x: 'x',
                    xFormat: '%H:%M:%S',
                    columns: [horas, conductividad, ozono_lineas, ozono_hori]
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
                            fit: false, // Los labels se adaptan al ancho de la pantalla
                            count: 2,
                            outer: false,
                        },

                        padding: {
                            right: 12
                        },

                        height: 45,

                        label: { // ADD
                            text: x,
                            position: 'middle'
                        }
                    },
                    y: {
                        min: 1,
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
                    d3.selectAll('.c3-xgrid-line.black').each(function(d, i) {
                        // cache the group node
                        var groupNode = d3.select(this).node();
                        // for each 'text' element within the group
                        d3.select(this).select('text').each(function(d, i) {
                            // hide the text to get size o  f group box otherwise text affects size.
                            d3.select(this).attr("hidden", true);
                            // use svg getBBox() func to get the group size without the text - want the position
                            var groupBx = groupNode.getBBox();
                            d3.select(this)
                                .attr('transform', null) // remove text rotation
                                .attr('x', groupBx
                                    .x) // x-offset from left of chart
                                .attr('y',
                                    0) // y-offset of the text from the top of the chart
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

                    d3.selectAll('.c3-xgrid-line.hora').each(function(d, i) {
                        var groupNode = d3.select(this).node();
                        d3.select(this).select('text').each(function(d, i) {
                            d3.select(this).attr("hidden", true);
                            var groupBx = groupNode.getBBox();
                            d3.select(this)
                                .attr('transform', null)
                                .attr('x', groupBx.x)
                                .attr('y', groupBx.height - 18)
                                .attr('dx', 5)
                                .attr('dy', 15)
                                .attr("hidden", null)
                                .attr("text-anchor", null)
                                .style('fill', 'black');
                        })
                    })


                }
            });
        }

        function segundaGrafica(respuesta) {
            var points = respuesta[3]; // Puntos de las marcas
            var x = respuesta[2].x; //Etiqueta para el eje x
            var y = respuesta[2].y2; // Etiqueta para el eje y
            var horas = respuesta[7]; // Definiendo el rango de horas de la X
            var temp_retorno = respuesta[11]; //Primera pluma de la grafica 2
            var temp_salida = respuesta[12]; //Segunda pluma de la grafica 2

            var chart = c3.generate({

                bindto: '#dbchart',
                data: {
                    x: 'x',
                    xFormat: '%H:%M:%S',
                    columns: [horas, temp_retorno, temp_salida]

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
                            fit: false, // Los labels se adaptan al ancho de la pantalla
                            count: 2,
                            outer: false,
                        },
                        padding: {
                            right: 12
                        },
                        height: 45,

                        label: { // ADD
                            text: x,
                            position: 'middle'
                        }
                    },
                    reotated: true,
                    y: {
                        min: 1,
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
                    d3.selectAll('.c3-xgrid-line.black').each(function(d, i) {
                        // cache the group node
                        var groupNode = d3.select(this).node();
                        // for each 'text' element within the group
                        d3.select(this).select('text').each(function(d, i) {
                            // hide the text to get size o  f group box otherwise text affects size.
                            d3.select(this).attr("hidden", true);
                            // use svg getBBox() func to get the group size without the text - want the position
                            var groupBx = groupNode.getBBox();
                            d3.select(this)
                                .attr('transform', null) // remove text rotation
                                .attr('x', groupBx
                                    .x) // x-offset from left of chart
                                .attr('y',
                                    0) // y-offset of the text from the top of the chart
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

                    d3.selectAll('.c3-xgrid-line.hora').each(function(d, i) {
                        var groupNode = d3.select(this).node();
                        d3.select(this).select('text').each(function(d, i) {
                            d3.select(this).attr("hidden", true);
                            var groupBx = groupNode.getBBox();
                            d3.select(this)
                                .attr('transform', null)
                                .attr('x', groupBx.x)
                                .attr('y', groupBx.height - 18)
                                .attr('dx', 5)
                                .attr('dy', 15)
                                .attr("hidden", null)
                                .attr("text-anchor", null)
                                .style('fill', 'black');
                        })
                    })


                }
            });
        }
    }

    function llamar() {
        setInterval(function() {
            //alert("imprimiendo cada 5 seg...");

            let datos = document.querySelector("#datos").innerText.trim();

            fetch('/timereal/ ' + datos)
                .then(response => response.json())
                .then(respuesta => {

                    if (respuesta['estado'] == 'vacio') {
                        cerrar();
                    }


                    listainfo.innerHTML = ''
                    listainfo.innerHTML += `
                    <li class="list-group-item p-1">
                        <span><strong> Proceso: </strong></span>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span>{{ $nomcip }}</span>
                        </div>
                    </li>
                    <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span>   ${ respuesta[0][0].fecha_inicio } </span>
                        </div>
                    </li>
                    <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span> ${ respuesta[0][0].fecha_final }  </span>
                        </div>
                    </li>
                    <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span> ${ respuesta[0][0].hora_inicio }  </span>
                        </div>

                    </li>
                    <li class="list-group-item p-1"> <strong> Hora final: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span> ${ respuesta[0][0].hora_final }</span>
                        </div>
                    </li>
                    <li class="list-group-item p-1"> <strong> Duracion: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span> ${ respuesta[0][0].duracion }  </span>
                        </div>
                    </li>
                    <li class="list-group-item p-1"> <strong> Tipo de cip: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span>  ${ respuesta[0][0].tipo_cip } </span>
                        </div>
                    </li>
                    <li class="list-group-item p-1"> <strong> Usuario: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span> ${ respuesta[0][0].usuario }  </span>
                        </div>

                    </li>
                    <li class="list-group-item p-1"> <strong> Equipo: </strong>
                        <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                            <span> ${ respuesta[0][0].equipo }  </span>
                        </div>

                    </li>
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
                var points = respuesta[3]; // Puntos de las marcas
                var x = respuesta[2].x; //Etiqueta para el eje x
                var y = respuesta[2].y2; // Etiqueta para el eje y
                var horas = respuesta[7]; // Definiendo el rango de horas de la X
                var conductividad = respuesta[8]; //Priemera pluma de la grafica 1
                var ozono_lineas = respuesta[9]; //Segunda pluma de la grafica 1
                var ozono_hori = respuesta[10]; //Tercera pluma de la grafica 1

                var chart = c3.generate({

                    bindto: '#chart_conductividad',
                    data: {
                        x: 'x',
                        xFormat: '%H:%M:%S',
                        columns: [horas, conductividad, ozono_lineas, ozono_hori]
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
                                fit: false, // Los labels se adaptan al ancho de la pantalla
                                count: 2,
                                outer: false,
                            },

                            padding: {
                                right: 12
                            },

                            height: 45,

                            label: { // ADD
                                text: x,
                                position: 'middle'
                            }
                        },
                        y: {
                            min: 1,
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
                        d3.selectAll('.c3-xgrid-line.black').each(function(d, i) {
                            // cache the group node
                            var groupNode = d3.select(this).node();
                            // for each 'text' element within the group
                            d3.select(this).select('text').each(function(d, i) {
                                // hide the text to get size o  f group box otherwise text affects size.
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

                        d3.selectAll('.c3-xgrid-line.hora').each(function(d, i) {
                            var groupNode = d3.select(this).node();
                            d3.select(this).select('text').each(function(d, i) {
                                d3.select(this).attr("hidden", true);
                                var groupBx = groupNode.getBBox();
                                d3.select(this)
                                    .attr('transform', null)
                                    .attr('x', groupBx.x)
                                    .attr('y', groupBx.height - 18)
                                    .attr('dx', 5)
                                    .attr('dy', 15)
                                    .attr("hidden", null)
                                    .attr("text-anchor", null)
                                    .style('fill', 'black');
                            })
                        })


                    }
                });
            }

            function segundaGrafica(respuesta) {
                var points = respuesta[3]; // Puntos de las marcas
                var x = respuesta[2].x; //Etiqueta para el eje x
                var y = respuesta[2].y2; // Etiqueta para el eje y
                var horas = respuesta[7]; // Definiendo el rango de horas de la X
                var temp_retorno = respuesta[11]; //Primera pluma de la grafica 2
                var temp_salida = respuesta[12]; //Segunda pluma de la grafica 2

                var chart = c3.generate({

                    bindto: '#dbchart',
                    data: {
                        x: 'x',
                        xFormat: '%H:%M:%S',
                        columns: [horas, temp_retorno, temp_salida]

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
                                fit: false, // Los labels se adaptan al ancho de la pantalla
                                count: 2,
                                outer: false,
                            },
                            padding: {
                                right: 12
                            },
                            height: 45,

                            label: { // ADD
                                text: x,
                                position: 'middle'
                            }
                        },
                        reotated: true,
                        y: {
                            min: 1,
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
                        d3.selectAll('.c3-xgrid-line.black').each(function(d, i) {
                            // cache the group node
                            var groupNode = d3.select(this).node();
                            // for each 'text' element within the group
                            d3.select(this).select('text').each(function(d, i) {
                                // hide the text to get size o  f group box otherwise text affects size.
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

                        d3.selectAll('.c3-xgrid-line.hora').each(function(d, i) {
                            var groupNode = d3.select(this).node();
                            d3.select(this).select('text').each(function(d, i) {
                                d3.select(this).attr("hidden", true);
                                var groupBx = groupNode.getBBox();
                                d3.select(this)
                                    .attr('transform', null)
                                    .attr('x', groupBx.x)
                                    .attr('y', groupBx.height - 18)
                                    .attr('dx', 5)
                                    .attr('dy', 15)
                                    .attr("hidden", null)
                                    .attr("text-anchor", null)
                                    .style('fill', 'black');
                            })
                        })


                    }
                });
            }

        }, 10000)

    }
</script>
