    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title id="titulopagina"> TITULO </title>

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

    <body id="graficas">
        <div class="row mt-1" style="width:100%; height:100%;">

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

                            <ul class="list-group" style='font-size: 10px'>
                                @foreach ($infos as $info)
                                    <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                                        {{ $info['fecha_inicio'] }}
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
                                    <li class="list-group-item p-1"> <strong> Duracion: </strong>
                                        {{ $info['duracion'] }}
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Operación: </strong>
                                        {{ $info['tipo_cip'] }}
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Usuario: </strong> {{ $info['usuario'] }}
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Equipo: </strong> {{ $info['equipo'] }}
                                    </li>

                                    <li class="list-group-item p-1"> <strong> Setpoint agua: </strong>
                                        {{ $info['agua_sp'] }}
                                    </li>

                                    <li class="list-group-item p-1"> <strong> Setpoint jarabe simple: </strong>
                                        {{ $info['js_sp'] }}
                                    </li>

                                    <li class="list-group-item p-1"> <strong> Setpoint fructosa: </strong>
                                        {{ $info['hfcs_sp'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <br>

                        {{--
                        <button id="pdf">PDF</button>
                        <button type="button"
                            onclick="printJS({ printable: 'graficas', type: 'html',
                        css: ['{{ asset('css/c3.css') }}', '{{ asset('css/boostrap502.css') }}'],
                        style: `@page {
                            margin: 5mm;
                            size: 380mm 200mm;
                        }

                        @media print {
                            body {
                                margin: 0mm;
                                width: 580mm;
                                height: 60mm
                                display: inline-block;
                                margin: 0px auto;
                                text-align: center;
                            }
                        }`
                     })">
                            Print Form
                        </button>  --}}


                        <div class="row">



                            {{--  height:360px;overflow:auto;  --}}
                            {{--  <div class="card-body" id="taula">  --}}
                            {{--  <table class="table table-borderless table-responsive" style='font-size: 10px'>
                                <thead>
                                    <tr class="table-secondary">
                                        <th>PASO DE CIP</th>
                                        <th>INICIO</th>
                                        <th>FINAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datostabla as $datotabla)
                                        <tr>
                                            <td class="p-1">{{ $datotabla['nombre'] }} </td>
                                            <td class="p-1"> {{ $datotabla['inicio'] }} </td>
                                            <td class="p-1"> {{ $datotabla['fin'] }} </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>  --}}
                            {{--  </div>  --}}

                            {{--  <button id="pdf-generate">imprimir</button>  --}}
                            {{-- onclick="printDiv('photo')"  --}}
                        </div>


                    </div>
                </div>
            </div>

            <div class="col-9 col-lg-10 col-xxl-10 d-flex">
                {{--  <div class="card flex-fill w-100  h-100">  --}}
                <div class="card">
                    <div class="card-header bg-dark bg-gradient text-light">
                        <span> GRAFICAS </span>
                    </div>
                    <div class="card-body" id="card-graficas">

                        <?php if ($sp == 'sp03') { ?>
                        <div class="contenedor_individual" id="chart_agua"> </div>
                        <?php } else { ?>
                        <div class="contenedor" id="chart_agua"> </div>
                        <div class="contenedor" id="dbchart"></div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </body>


    <script>
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');
        var points = JSON.parse('{!! json_encode($points) !!}');
        var labels = JSON.parse('{!! json_encode($labels_grap1) !!}');

        var chart = c3.generate({

            bindto: '#chart_agua',
            data: {
                json: puntos,
                keys: {
                    x: 'horas',
                    value: [labels.x, labels.y, labels.z],
                },


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
                        text: "{!! $ejes['x'] !!}",
                        position: 'middle'
                    }
                },
                y: {
                    label: { // ADD
                        text: "{!! $ejes['y'] !!}",
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

                window.innerWidth > 800 ? chart.internal.config.axis_x_tick_culling_max = 8 : chart.internal
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
                            .attr('x', groupBx.x) // x-offset from left of chart
                            .attr('y', 0) // y-offset of the text from the top of the chart
                            .attr('dx', 5) // small x-adjust to clear the line
                            .attr('dy', 15) // small y-adjust to get onto the chart
                            .attr("hidden", null) // better make the text visible again
                            .attr("text-anchor", null) // anchor to left by default
                            .style('fill', 'black'); // color it red for fun
                    })
                })
            }

        });
    </script>

    <script>
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');
        var points = JSON.parse('{!! json_encode($points) !!}');
        var labels = JSON.parse('{!! json_encode($labels_grap2) !!}');

        var chart = c3.generate({

            bindto: '#dbchart',
            data: {
                json: puntos,
                keys: {
                    x: 'horas',
                    value: [labels.x, labels.y, labels.z],
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
                        text: "{!! $ejes['x'] !!}",
                        position: 'middle'
                    }
                },
                y: {
                    label: { // ADD
                        text: "{!! $ejes['y2'] !!}",
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

                window.innerWidth > 800 ? chart.internal.config.axis_x_tick_culling_max = 8 : chart.internal
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
                            .attr('x', groupBx.x) // x-offset from left of chart
                            .attr('y', 0) // y-offset of the text from the top of the chart
                            .attr('dx', 5) // small x-adjust to clear the line
                            .attr('dy', 15) // small y-adjust to get onto the chart
                            .attr("hidden", null) // better make the text visible again
                            .attr("text-anchor", null) // anchor to left by default
                            .style('fill', 'black'); // color it red for fun
                    })
                })
            }

        });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.js"
        integrity="sha512-Fd3EQng6gZYBGzHbKd52pV76dXZZravPY7lxfg01nPx5mdekqS8kX4o1NfTtWiHqQyKhEGaReSf4BrtfKc+D5w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" media="print"></script>

    <script>
        $('#pdf').click(function() {
            $('#graficas').printThis({

                pageTitle: "UTOPIAN PRINT",
                debug: false,
                importCSS: true,
                importStyle: true,
                printContainer: true,
                removeInline: false,
                printDelay: 333,
                {{--  header: "<h1>Look at all of my kitties!</h1>",
                footer: "<h1 styile:'background: white;'>Footer</h1>",  --}}
                base: "https://jasonday.github.io/printThis/",
                formValues: true
            });
        });
    </script>

    <script src="https://printjs-4de6.kxcdn.com/print.min.js"></script>
    <script src="https://printjs-4de6.kxcdn.com/print.min.css"></script>

    <script>
        $(document).ready(function() {
            var ti = JSON.parse('{!! json_encode($titulopagina) !!}');
            var titulo = document.getElementById('titulopagina');
            titulo.innerHTML = ''
            titulo.innerHTML += `
                ${ ti }
            `;

        });
    </script>
