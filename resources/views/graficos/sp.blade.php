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
                            <span
                                style="font-family:Lobster; font-weight: 600; font-size: 18px; background-color: red; color: white; text-align: center">{{ $planta[0]['nombre'] }}</span>
                            <span
                                style="font-family:Lobster; color: rgb(0, 0, 0); text-align: center; font-weight: 600; margin-top:5px;">{{ $planta[0]['sucursal'] }}</span>
                            <br><br>

                            <ul class="list-group" style='font-size: 10px'>
                                <li class="list-group-item p-1">

                                    <span><strong> Proceso: </strong></span>
                                    <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                        <span>{{ $nomcip }}</span>
                                    </div>

                                </li>

                                @foreach ($infos as $info)
                                    <li class="list-group-item p-1">
                                        <strong> Fecha inicio: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span>{{ $info['fecha_inicio'] }}</span>
                                        </div>
                                    <li class="list-group-item p-1"> <strong> Fecha final: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['fecha_final'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Hora inicio: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['hora_inicio'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Hora final: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['hora_final'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Duracion: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['duracion'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Operación: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span>{{ $info['tipo_cip'] }}</span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Usuario: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['usuario'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Equipo: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['equipo'] }} </span>
                                        </div>

                                    </li>
                                    <li class="list-group-item p-1"> <strong> Setpoint agua: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['agua_sp'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Setpoint jarabe simple: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['js_sp'] }} </span>
                                        </div>
                                    </li>
                                    <li class="list-group-item p-1"> <strong> Setpoint fructosa: </strong>
                                        <div style="width: 100%; margin-top: -15px; margin-left: 140px;">
                                            <span> {{ $info['hfcs_sp'] }} </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <br>


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
                        </button>


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
                        <div class="contenedor-arriba-uno" id="chart_agua"> </div>
                        <div class="contenedor-arriba-dos" id="fqa"></div>

                        <?php if ($sp == 'sp03') { ?>
                        <div class="contenedor-tres" id="dbchart" style="pointer-events: none; opacity: 0.4;"></div>
                        <?php } else { ?>
                        <div class="contenedor-tres" id="dbchart"></div>
                        <?php } ?>


                    </div>
                </div>
            </div>
        </div>
    </body>

    {{--  <script>
        var $chartContainer = $('.c3');
        var $lastTick = $chartContainer.find('.c3-axis.c3-axis-x .tick').last();
        var translateValue = parseInt($lastTick.attr('transform').replace('translate(', ''), 10);
        var tickWidth = $lastTick[0].getBoundingClientRect().width / 2;
        $lastTick.attr('transform', 'translate(' + (translateValue - tickWidth) + ',0)');
    </script>  --}}

    <script>
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');

        var points = JSON.parse('{!! json_encode($points) !!}');
        //console.log(points);
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
                        fit: false, // Los labels se adaptan al ancho de la pantalla
                        count: 2,
                        outer: false,
                    },
                    height: 45,

                    padding: {
                        right: 20
                    },
                    label: { // ADD
                        text: "{!! $ejes['x'] !!}",
                        position: 'middle'
                    }
                },
                y: {
                    min: 1,
                    padding: {
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

                    //lines: [{value: 2}, {value: 4, class: 'grid4', text: 'LABEL 4'} ]
                },
                y: {
                    //show: true
                }
            },
        });
    </script>

    <script>
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');

        var points = JSON.parse('{!! json_encode($points) !!}');
        var labels = JSON.parse('{!! json_encode($labels_grap2) !!}');
        //console.log(labels);

        var chart = c3.generate({

            bindto: '#fqa',
            data: {
                json: puntos,
                keys: {
                    x: 'horas',
                    value: ["Cloro", "Conductividad", "Velocidad", "Ph", "Lote", "Propiedades PSQA"],
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
                        fit: false, // Los labels se adaptan al ancho de la pantalla
                        count: 2,
                        outer: false,
                    },
                    height: 45,
                    padding: {
                        right: 20
                    },
                    label: { // ADD
                        text: "",
                        position: 'middle'
                    },
                    show: true
                },
                y: {
                    min: 1,
                    padding: {
                        bottom: 0
                    },
                    label: { // ADD
                        text: "",
                        position: 'outer-middle'
                    },
                }
            },
            grid: {
                x: {
                    lines: points
                },
                y: {
                    //show: true
                }
            },
        });
    </script>

    <script>
        var puntos = JSON.parse('{!! json_encode($graficar) !!}');
        var points = JSON.parse('{!! json_encode($points) !!}');
        var labels = JSON.parse('{!! json_encode($labels_grap2) !!}');
        //console.log(labels);

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
                        fit: false, // Los labels se adaptan al ancho de la pantalla
                        count: 2,
                        outer: false,
                    },
                    height: 45,
                    padding: {
                        right: 20
                    },
                    label: { // ADD
                        text: "{!! $ejes['x'] !!}",
                        position: 'middle'
                    }
                },
                y: {
                    min: 1,
                    padding: {
                        bottom: 0
                    },
                    label: { // ADD
                        text: "{!! $ejes['y2'] !!}",
                        position: 'outer-middle'
                    },
                }
            },
            grid: {
                x: {
                    lines: points
                },
                y: {
                    //show: true
                }
            },
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
