@extends('layouts.app')
@section('contenido')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    </head>

    {{--  MODAL PARA AGREGAR NUEVA SECUENCIA --}}
    <div class="modal fade" id="AddEquipoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir nuevo equipo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="saveform_errList"></ul>

                    <div class="form-group mb-3">
                        <label for="">Valor</label>
                        <input type="number" id="Addequipo" class="valor form-control">
                        <label for="">Nombre del equipo</label>
                        <input type="text" id="Addequipo" class="equipo form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary agregar_equipo">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- END MODAL PAARA AGREGAR SECUENCIA --}}

    {{-- MODAL PARA PODER EDITAR ETIQUETAS --}}
    <div class="modal fade" id="EquipoModal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar equipo </h5>
                    <ul id="updateform_errlist"></ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_equipo2">

                    <div class="form-group mb-3">
                        <label for="">Equipo</label>
                        <input type="text" id="edit_name2" class="equipo form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary update_equipo ">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    {{--  modal para poder editar las secuencias --}}

    {{-- MODAL PARA ELIMINAR SECUENCIAS --}}
    <div class="modal fade" id="DeleteEquipoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="delete_1">

                    <h4>¿Esta seguro de eliminar el equipo?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary delete_equipo1">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL PARA ELIMINAR SECUENCIAS --}}



    <!-- USO DE HTML PARA VISTA -->
    <div class="row mt-1" style="width:100%; height:100%;">
        <div class="col-12 col-lg-12 col-xxl-12 d-flex">
            <div class="card flex-fill w-100">
                <div class="row">
                    <div class="col-md-12">
                        <div id="success_message"></div>
                        <div class="col-0 col-lg-12 col-xxl-12 d-flex">
                            <div class="card-body">
                                <h4 style='font-size: 20px; color:black;'><strong> Equipos</strong></h4>
                                <strong style="display:none; " id="listado"> {{ $listado }}</strong>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#AddEquipoModal">
                                    Añadir nuevo equipo</button><br>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-striped" align="center center">
                                        <thead id="titulos">

                                        </thead>
                                        <tbody id="tableEquipos">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div </div>
            </div>
        </div>
        <!-- USO DE HTML PARA VISTA -->


        {{-- AJAX PARA EQUIPOS --}}

        <script>
            //PARA VISUALIZAR LOS DATOS DE LA FUNCION
            visualizarEquipos();

            function visualizarEquipos() {
                //POR MEDIO DEL INNER DEVOLVEMOS EL VALOR Y LO GUARDASO EN UNA VARIABLE, ESTO PARA AL MOMENTO QUE HACER CLICK EN EQUIPO NOS DEVUELVA EL VALOR QUE
                //OCUPARAMOS PARA PODER HACER LAS CONSULTAS A LA DB DE MANERA GENERICA
                var listado = document.getElementById("listado").innerHTML.trim();
                //console.log(listado);
                //AJAX PARA PODER RECOLECTAR Y MOSTRAR A LA PANTALLA QUE FUERON ENVIADOS DIRECTAMENTE DESDE EL CONTROLADOR
                $.ajax({
                    //SOLICITUD DE CONSULTA
                    type: "GET",
                    //RUTA A LA CUAL REDIRECCIONAREMOS
                    url: "/equipos/" + listado,
                    //DATOS EN JSON
                    dataType: "json",
                    success: function(response) {
                        //HABLAMOS AL ID DE LA TABLA DE LA VISTA HTML
                        $('#tableEquipos').html("");
                        //FUNCIO QUE RECORRERA TODO LO DATOS PARA PODER INGRESARLO
                        $.each(response.enviar, function(key, item) {
                            $('#tableEquipos').append(
                                '<tr>\
                                                                                                                                                    <td>' +
                                item
                                .valor +
                                ' </td>\
                                                                                                                                                    <td>' +
                                item
                                .equipo +
                                ' </td>\
                                                                                                                                                    <td><button type="button" value="' +
                                item
                                .valor +
                                '" class="edit_equipo btn btn-primary  btn-sm">  Editar</button> </td>\
                                                                                                                                         <td><button type="button" value="' +
                                item
                                .valor +
                                '" class="delete_equipo btn btn-danger btn-sm" > Eliminar </button> </td>\
                                                                                                                                                </tr>'

                            );
                            encabezado();

                        });
                    }
                });
            }
            //ESTA FUNCION CONTIENE TODO EL ENCABEZADO DE LA TABLA
            function encabezado() {
                titulos.innerHTML = ''
                titulos.innerHTML += `
                <tr>
                    <th>VALOR</th>
                    <th>EQUIPO</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                `;
            }

            $(document).ready(function() {
                //FUNCION PARA AGREGAR EQUIPO
                $(document).on('click', '.agregar_equipo', function(e) {
                    e.preventDefault();

                    var listado = document.getElementById("listado").innerHTML.trim();
                    //
                    var data = {
                        'valor': $('.valor').val(),
                        'equipo': $('.equipo').val(),

                    };
                    //console.log(data);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "/agregarEquipo/" + listado,
                        data: data,
                        dataType: "json",
                        success: function(response) {

                            if (response.status == 400) {
                                $('#saveform_errList').html("");
                                $('#saveform_errList').addClass('alert alert-danger');
                                $.each(response.errors, function(key, err_values) {
                                    $('#saveform_errList').append('<li>' + err_values +
                                        '</li>');
                                });

                            } else {
                                $('#saveform_errList').html("");
                                $('#success_message').addClass('alert alert-success');
                                $('#success_message').text(response.message);
                                $('#success_message').fadeOut(5000);
                                $('#AddEquipoModal').modal('hide');
                                $('#AddEquipoModal').find('input').val("");
                                visualizarEquipos();

                            }

                        }
                    });

                });
            });

            //CREADNO FUNCION PARA EL BOTON DE EDITAR
            $(document).on('click', '.edit_equipo', function(e) {
                e.preventDefault();

                var listado = document.getElementById("listado").innerHTML.trim();
                //DEVOLVIENDO DEL ID CORRECTO DE CADA FILA
                var valor_equipo = $(this).val();
                //VISUALIZANDO EL ID DEVULTO POR MEDIO DE CONSOLA
                console.log(valor_equipo);
                //MANDAMOS A LLAMAR EL MODAL DE EDITAR
                $('#EquipoModal').modal('show');

                $.ajax({
                    type: "GET",
                    url: "/editarEquipo/" + listado + "/" + valor_equipo,
                    success: function(response) {
                        if (response.statusCode == 404) {
                            $('#success_message').html("");
                            $('#success_message').addClass("alert alert-danger");
                            $('#success_message').text(response.message);
                            $('#success_message').fadeOut(5000);
                        } else {
                            $('#edit_equipo2').val(valor_equipo);
                            $("#edit_name2").val(response.equipo1.equipo);
                        }
                    }

                });
            });
            $(document).on('click', '.update_equipo', function(e) {
                e.preventDefault();
                var listado = document.getElementById("listado").innerHTML.trim();

                var id_equipos = $('#edit_equipo2').val();

                var data = {
                    'equipo': $('#edit_name2').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "/actualizarEquipo/" + listado + "/" + id_equipos,
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 400) {
                            $('#updateform_errlist').html("");
                            $('#updateform_errlist').addClass('alert alert-danger');
                            $.each(response.errors, function(key, err_values) {
                                $('#updateform_errlist').append('<li>' + err_values +
                                    '</li>');

                            });

                        } else if (response.status == 404) {
                            $('#updateform_errlist').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#success_message').fadeOut(5000);

                        } else {
                            $('#updateform_errlist').html("");
                            $('#success_message').html("");
                            $('#success_message').addClass('alert alert-success');
                            $('#success_message').text(response.message);
                            $('#success_message').fadeOut(5000);

                            $('#EquipoModal').modal('hide');

                            visualizarEquipos();



                        }

                    }

                });

            });
            $(document).on('click', '.delete_equipo', function(e) {
                e.preventDefault();
                //RECUPERANDO EL ID  DE LA FILA
                var id_equipo = $(this).val();

                //MANDANDO EL ID AL MODAL PARA PODER ELIMINAR
                $('#delete_1').val(id_equipo);
                $('#DeleteEquipoModal').modal('show');

            });
            $(document).on('click', '.delete_equipo1', function(e) {

                e.preventDefault();
                var listado = document.getElementById("listado").innerHTML.trim();
                var id_equipo = $('#delete_1').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "DELETE",
                    url: "/eliminarEquipo/" + listado + "/" + id_equipo,
                    success: function(response) {
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);
                        $('#DeleteEquipoModal').modal('hide');

                        visualizarEquipos();
                    }

                });

            });
        </script>


        {{-- END AJAX PARA EQUIPOS --}}
    @endsection
