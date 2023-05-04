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

                        <ul class="list-group" style='font-size: 10px'>
                            @foreach ($infos as $info)
                                <li class="list-group-item p-1"> <strong> Fecha inicio: </strong>
                                    <span>{{ $info['fecha_inicio'] }}</span>
                                    {{--  style="margin-left:110px;"  --}}
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
                            max: window.innerWidth > 800 ? 10 : 4
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

    {{--  <script src="https://cdn.jsdelivr.net/npm/js-html2pdf@1.1.4/lib/html2pdf.min.js"></script>  --}}
    {{--  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>  --}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"
        integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2017.1.223/styles/kendo.common.min.css" />
    <script src="https://kendo.cdn.telerik.com/2017.1.223/js/jszip.min.js"></script>
    <script src="https://kendo.cdn.telerik.com/2017.1.223/js/kendo.all.min.js"></script>



    <script>
        function pdf_init() {

            var element = document.getElementById('photo');
            var opt = {
                margin: 1,
                filename: 'myfile.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1

                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'pt',
                    format: [screen.height - 410, screen.width - 620],
                    orientation: 'l'
                }
            };
            // Old monolithic-style usage:
            html2pdf(element, opt);


        }
    </script>

    <script>
        function getPDF(selector) {
            kendo.drawing.drawDOM($(selector)).then(function(group) {
                kendo.drawing.pdf.saveAs(group, 'testing.pdf');
            });
        }
    </script>

    {{--  <script type="text/javascript">
        $('#pdf-generate').click(function() {
            var enviar = document.getElementById('photo');
            getPDF(enviar);
        })
    </script>  --}}


    <script>
        function printDiv(divName) {
            // fix weird back fill
            d3.select('#' + divName).selectAll("path").attr("fill", "none");
            //fix no axes
            d3.select('#' + divName).selectAll("path.domain").attr("stroke", "black");
            //fix no tick
            d3.select('#' + divName).selectAll(".tick line").attr("stroke", "black");
            // fix text going off the export (becuase my labels font size is larger than default)
            d3.select('#' + divName + "> svg").style("font-size", "12px");
            // add any other styles needed
            var printContents = document.getElementById(divName).innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            window.location.reload();


        }
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"
        integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{--  Guardar pdf por medio del navegador  --}}
    <script>
        $('#pdf-generate').click(function() {
            console.log('pdfGenerate');
            html2canvas(document.querySelector('#photo')).then((canvas) => {
                let base64image = canvas.toDataURL('image/png');
                // console.log(base64image);
                //let pdf = new jsPDF('l', 'mm', [280, 530]);
                //pdf.addImage(base64image, 'PNG', 10, 10);

                let pdf = new jsPDF('l', 'pt', 'a3');
                pdf.addImage(base64image, 'PNG', 10, 10, 1115, 750);
                pdf.save('graficas.pdf');
            });

        })
    </script>

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
