    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> PHF SOFTWARE </title>

    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/c3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">

    {{--  Importando js de C3 para las graficas y Ajax para la consulta de la tabla index  --}}
    <script src="{{ URL::asset('js/c3.js') }}"></script>
    <script src="{{ URL::asset('js/d3-v5-min.js') }}"></script>
    <script src="{{ URL::asset('build/assets/app.js') }}"></script>
    <script src="{{ URL::asset('js/ajax.js') }}"></script>
    <script src="{{ URL::asset('js/all.js') }}"></script>

    <script src="{{ URL::asset('js/imprimir.js') }}"></script>

    {{--  <script src="{{ URL::asset('js/html2canvas.js') }}"></script>
    <script src="{{ URL::asset('js/html2canvas.min.js') }}"></script>  --}}

    {{--  -----------------------------------------------------------------------  --}}


    <div class="row mt-1" style="width:100%; height:100%;" id="photo">
        {{--  col-12 para vista en celular
            col-lg-6 Para vista en tablet
            col-xxl-3 para vista en pc  --}}

        <div class="col-0 col-lg-2 col-xxl-2 d-flex">
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
                                <li class="list-group-item p-1"> <strong> Duracion: </strong> {{ $info['duracion'] }}
                                </li>
                                <li class="list-group-item p-1"> <strong> Tipo de cip: </strong>
                                    {{ $info['tipo_cip'] }}
                                </li>
                                <li class="list-group-item p-1"> <strong> Usuario: </strong> {{ $info['usuario'] }}
                                </li>
                                <li class="list-group-item p-1"> <strong> Equipo: </strong> {{ $info['equipo'] }} </li>
                            @endforeach
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
                            <tbody>
                                @foreach ($datostabla as $datotabla)
                                    <tr>
                                        <td class="p-1">{{ $datotabla['nombre'] }} </td>
                                        <td class="p-1"> {{ $datotabla['inicio'] }} </td>
                                        <td class="p-1"> {{ $datotabla['fin'] }} </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{--  </div>  --}}

                        <button onclick="pdf_init()">imprimir</button>
                    </div>


                </div>
            </div>
        </div>

        <div class="col-12 col-lg-10 col-xxl-10 d-flex ">
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
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');
        var points = JSON.parse('{!! json_encode($points) !!}');
        var labels = JSON.parse('{!! json_encode($labels_grap1) !!}');

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
                            max: window.innerWidth > 800 ? 15 : 4
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

    <script>
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');
        var points = JSON.parse('{!! json_encode($points) !!}');
        var labels = JSON.parse('{!! json_encode($labels_grap2) !!}');
        // console.log(labels[0].x);
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
                            max: window.innerWidth > 800 ? 15 : 4
                        },

                    },

                    height: 45,

                    label: { // ADD
                        text: "{!! $ejes['x'] !!}",
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

                        text: "{!! $ejes['y'] !!}",
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

    <script src="https://cdn.jsdelivr.net/npm/js-html2pdf@1.1.4/lib/html2pdf.min.js"></script>
    <script>
        function pdf_init() {
            // Get the element to print
            var element = document.getElementById('photo');

            // Define optional configuration
            var options = {
                filename: 'my-file.pdf'
            };

            // Create instance of html2pdf class
            var exporter = new html2pdf(element, {
                margin: 10,
                filename: 'myfile.pdf',
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2,
                    logging: true,
                    dpi: 192,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'mm',
                    format: 'a4',
                    orientation: 'portrait'
                }
            });

            // Download the PDF or...
            exporter.getPdf(true).then((pdf) => {
                console.log('pdf file downloaded');
            });

            // Get the jsPDF object to work with it




        }
    </script>
