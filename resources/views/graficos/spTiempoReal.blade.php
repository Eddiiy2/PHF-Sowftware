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

    <div class="row mt-1" style="width: 100%; height: 100%;" id="photo">

        <div class="col-3 col-lg-2 col-xxl-2 d-flex">
            <div class="card flex-fill w-100">
                <div class="card-header bg-dark bg-gradient text-light">
                    <span> DATOS PARA SP </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <span
                            style="font-family:Lobster; font-weight: 600; font-size: 18px; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); text-align: center">{{ $planta[0]['nombre'] }}</span>
                        <span
                            style="font-family:Lobster; color: rgb(0, 0, 0); text-align: center; font-weight: 600; margin-top:5px;">{{ $planta[0]['sucursal'] }}</span>

                        <br><br>

                        <strong style="display: none" id="datos"> {{ $datos }} </strong>
                        <ul class="list-group" style='font-size: 10px' id="listaSP">

                        </ul>
                    </div>
                    <br>

                </div>
            </div>
        </div>

        <div class="col-9 col-lg-10 col-xxl-10 d-flex ">
            {{--  <div class="card flex-fill w-100  h-100">  --}}
            <div class="card">
                <div class="card-header bg-dark bg-gradient text-light">
                    <span> GRAFICAS </span>
                </div>
                <div class="card-body" id="card-graficas">
                    <div class="contenedor-arriba-uno" id="chart_agua"></div>
                    <div class="contenedor-arriba-dos" id="fqa"></div>

                    <div class="contenedor-tres" id="dbchart"></div>


                </div>
            </div>
        </div>
    </div>



    {{-- CREANDO AJAX PARA SP EN TIEMPO REAL --}}

    <script>
        $(document).ready(function() {
            verSp()
        });

        function cerrar() {
            window.open('', '_parent', '');
            window.close();
        }

        function verSp() {
            let datos = document.querySelector("#datos").innerText.trim();
            //console.log(datos);

            fetch('/tiempoSp/ ' + datos)
                .then(response => response.json())
                .then(respuesta => {
                    listaSP.innerHTML = ''
                    listaSP.innerHTML += `
                <li class="list-group-item p-1">
                    <span><strong> Proceso: </strong></span>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>{{ $nomcip }}</span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${respuesta[0][0].fecha_inicio} </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${ respuesta[0][0].fecha_final }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].hora_inicio }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Hora final: </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].hora_final }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Duracion : </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${ respuesta[0][0].duracion } </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Operacion : </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${ respuesta[0][0].sabor }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Usuario : </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].usuario }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Equipo : </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].equipo }  </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Setpoing agua : </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${ respuesta[0][0].agua_sp } </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Setpoing JS : </strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span>  ${ respuesta[0][0].js_sp } </span>
                    </div>
                </li>
                <li class="list-group-item p-1"> <strong> Setpoing fruct:</strong>
                    <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                        <span> ${ respuesta[0][0].hfcs_sp} </span>
                    </div>
                </li>
                `;


                    primeraGrafica(respuesta);
                    segundaGrafica(respuesta);
                    teceraGrafica(respuesta);


                });


            function primeraGrafica(respuesta) {
                var points = respuesta[2];
                var x = respuesta[1].x;
                var y = respuesta[1].y;
                var horas = respuesta[6];
                var aguaSP = respuesta[7];
                ''
                //console.log(aguaSP);
                var aguaAcc = respuesta[8];
                var aguaVel = respuesta[9];


                var chart = c3.generate({

                    bindto: '#chart_agua',
                    data: {
                        x: 'x',
                        xFormat: '%H:%M:%S',
                        columns: [horas, aguaSP, aguaAcc, aguaVel]

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
                var points = respuesta[2];
                var x = respuesta[1].x;
                var y = respuesta[1].y2;
                var horas = respuesta[6];

                var hfcsp = respuesta[10];
                console.log(hfcsp);
                var hfcacc = respuesta[11];
                var hfcVel = respuesta[12];

                var chart = c3.generate({

                    bindto: '#dbchart',
                    data: {
                        x: 'x',
                        xFormat: '%H:%M:%S',
                        columns: [horas, hfcsp, hfcacc, hfcVel]

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

            function teceraGrafica(respuesta) {
                var points = respuesta[2];
                var x = respuesta[1].x;
                var y = respuesta[1].y2;
                //var horas = respuesta[6];
                //var clo = "Cloro";
                //var conduc = "Conductividad";
                //var vel = "Velocidad";


                var chart = c3.generate({

                    bindto: '#fqa',
                    data: {

                        columns: [
                            ['cloro'],
                            ['conductividad'],
                            ['velocidad'],
                            ['PH'],
                            ['Lote'],
                            ['Propiedades PSQA']
                        ],
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
        }

        function tiempoReal() {

            setInterval(function() {
                //alert("imprimiendo cada 5 seg...");

                let datos = document.querySelect('#datos').innerHTML.trim();


                fetch('/tiempoSp/ ' + datos)
                    .then(response => response.json())
                    .then(respuesta => {
                        listaSP.innerHTML = ''
                        listaSP.innerHTML += `
            <li class="list-group-item p-1">
                <span><strong> Proceso: </strong></span>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>{{ $nomcip }}</span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>  ${respuesta[0][0].fecha_inicio} </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>  ${ respuesta[0][0].fecha_final }  </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span> ${ respuesta[0][0].hora_inicio }  </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Hora final: </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span> ${ respuesta[0][0].hora_final }  </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Duracion : </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>  ${ respuesta[0][0].duracion } </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Operacion : </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>  ${ respuesta[0][0].sabor }  </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Usuario : </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span> ${ respuesta[0][0].usuario }  </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Equipo : </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span> ${ respuesta[0][0].equipo }  </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Setpoing agua : </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>  ${ respuesta[0][0].agua_sp } </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Setpoing JS : </strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span>  ${ respuesta[0][0].js_sp } </span>
                </div>
            </li>
            <li class="list-group-item p-1"> <strong> Setpoing fruct:</strong>
                <div style="width: 100%; margin-top: -15px; margin-left: 80px;">
                    <span> ${ respuesta[0][0].hfcs_sp} </span>
                </div>
            </li>
            `;


                        primeraGrafica(respuesta);
                        segundaGrafica(respuesta);
                        teceraGrafica(respuesta);


                    });

                function primeraGrafica(respuesta) {
                    var points = respuesta[2];
                    var x = respuesta[1].x;
                    var y = respuesta[1].y;

                    var aguaSP = respuesta[7];
                    var horas = respuesta[6];
                    //console.log(aguaSP);
                    var aguaAcc = respuesta[8];
                    var aguaVel = respuesta[9];


                    var chart = c3.generate({

                        bindto: '#chart_agua',
                        data: {
                            x: 'x',
                            xFormat: '%H:%M:%S',
                            columns: [horas, aguaSP, aguaAcc, aguaVel]

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
                    var points = respuesta[2];
                    var x = respuesta[1].x;
                    var y = respuesta[1].y2;
                    var horas = respuesta[6];

                    var hfcsp = respuesta[10];
                    console.log(hfcsp);
                    var hfcacc = respuesta[11];
                    var hfcVel = respuesta[12];

                    var chart = c3.generate({

                        bindto: '#dbchart',
                        data: {
                            x: 'x',
                            xFormat: '%H:%M:%S',
                            columns: [horas, hfcsp, hfcacc, hfcVel]

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

    {{-- END CREANDO AJAX PARA SP EN TIEMPO REAL --}}
