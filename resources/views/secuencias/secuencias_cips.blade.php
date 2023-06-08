@extends('layouts.app')
@section('contenido')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    </head>
    {{--  MODAL PARA AGREGAR NUEVA SECUENCIA --}}
    <div class="modal fade" id="AddSecuenciaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir nueva secuencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="saveform_errList"></ul>

                    <div class="form-group mb-3">
                        <label for="">Valor</label>
                        <input type="number" id="Addsecuencia" class="valor form-control">
                        <label for="">Nombre de secuencia</label>
                        <input type="text" id="Addsecuencia" class="secuencia form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary agregar_secuencia">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- END MODAL PAARA AGREGAR SECUENCIA --}}

    {{-- MODAL PARA PODER EDITAR ETIQUETAS --}}
    <div class="modal fade" id="editarModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar secuencia </h5>
                    <ul id="updateform_errlist"></ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_secuencia2">

                    <div class="form-group mb-3">
                        <label for="">Secuencia</label>
                        <input type="text" id="edit_name2" class="secuencia form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary update_secuencia2 ">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    {{--  modal para poder editar las secuencias --}}

    {{-- MODAL PARA ELIMINAR SECUENCIAS --}}
    <div class="modal fade" id="DeleteSecuenciaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="delete_1">

                    <h4>¿Esta seguro de eliminar la secuencia?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary delete_secuencia1">Eliminar</button>
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
                                <h4 style='font-size: 20px; color:black;'><strong> Secuencias para cips</strong></h4>
                                <strong style="display:none; " id="envios"> {{ $envios }}</strong>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                    data-bs-target="#AddSecuenciaModal">
                                    Añadir nueva secuencia</button><br>
                                <br>
                                <div class="table-responsive">
                                    <table class="table table-striped" align="center center">
                                        <thead id="contenido">

                                        </thead>
                                        <tbody id="tableCip02">

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

        <!-- APLICANDO AJAX PARA LA INSERCCION DE DATOS DE LA TABLA Y EL USO DE UPDATE Y EDIT -->

        <script>
            visualizarCip02();

            function visualizarCip02() {
                var enviado = document.getElementById("envios").innerHTML.trim();
                //console.log(enviado);

                $.ajax({
                    type: "GET",
                    url: "/secuenciasVer/" + enviado,
                    dataType: "json",
                    success: function(response) {
                        //console.log(response.mandar);
                        $('#tableCip02').html("");
                        $.each(response.mandar, function(key, item2) {
                            $('#tableCip02').append(
                                '<tr>\
                                                         <td>' + item2.valor + '</td>\
                                                        <td>' + item2.secuencia + '</td>\
                                                         <td><button type="button" value="' + item2.valor + '" class="edit_secuencia2 btn btn-primary  btn-sm"> Editar</button> </td>\
                                                         <td><button type="button" value="' + item2.valor + '" class="delete_secuencia2 btn btn-danger btn-sm" >Eliminar </button> </td>\
                                                         </tr>'

                            );

                            titulos();

                        });
                    }

                });
            }

            function titulos() {
                contenido.innerHTML = ''
                contenido.innerHTML += `
                <tr>
                    <th>VALOR</th>
                    <th>NOMBRE DE SECUENCIAS</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
                    `;

            }
            $(document).ready(function() {
                $(document).on('click', '.agregar_secuencia', function(e) {
                    e.preventDefault();

                    var enviado = document.getElementById("envios").innerHTML.trim();

                    var data = {
                        'valor': $('.valor').val(),
                        'secuencia': $('.secuencia').val(),
                    };
                    //console.log(data);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        type: "POST",
                        url: "/agregarSecuencia/" + enviado,
                        data: data,
                        dataType: "json",
                        success: function(response) {
                            //consolo.log(response);
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
                                $('#AddSecuenciaModal').modal('hide');
                                $('#AddSecuenciaModal').find('input').val("");
                                visualizarCip02()
                            }
                        }
                    });
                });

            });
            //OBTENIENDO EL ID DE CADA UNA DE LAS FILAS PARA PODER MANDAR LOS DATOS JSON
            $(document).on('click', '.edit_secuencia2', function(e) {
                e.preventDefault();
                var enviado = document.getElementById("envios").innerHTML.trim();

                //OBTENIENDO EL ID
                var valor_id2 = $(this).val();
                //COMPROBANDO POR MEDIO DE LA CONSOLA DEL NAVEGADOR SI NOS REGRESA EL ID CORRECTO DE CADA UNA DE LAS FILAS
                //console.log(valor_id2);
                $('#editarModal2').modal('show');
                $.ajax({
                    type: "GET",
                    url: "/editarSecuencia/" + enviado + "/" + valor_id2,
                    success: function(response) {
                        // console.log(response);
                        if (response.status == 404) {
                            $('#success_message').html("");
                            $('#success_message').addClass("alert alert-danger");
                            $('#success_message').text(response.message);
                            $('#success_message').fadeOut(5000);

                        } else {
                            $('#edit_secuencia2').val(valor_id2);

                            $("#edit_name2").val(response.secuencias1.secuencia);

                        }
                    }
                });
            });

            $(document).on('click', '.update_secuencia2', function(e) {
                e.preventDefault();
                var enviado = document.getElementById("envios").innerHTML.trim();


                var secuencia2_id = $('#edit_secuencia2').val();
                var data = {
                    'secuencia': $('#edit_name2').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "/actualizarSecuencia/" + enviado + "/" + secuencia2_id,
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        // console.log(response);
                        if (response.status == 400) {
                            //obteniendo los campos de errores

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


                            $('#editarModal2').modal('hide');

                            visualizarCip02();
                        }
                    }

                });

            });

            $(document).on('click', '.delete_secuencia2', function(e) {
                e.preventDefault();


                var id_delete = $(this).val();

                $('#delete_1').val(id_delete);
                $('#DeleteSecuenciaModal').modal('show');
            });

            $(document).on('click', '.delete_secuencia1', function(e) {
                e.preventDefault();
                var enviado = document.getElementById("envios").innerHTML.trim();

                var id_delet = $('#delete_1').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "DELETE",
                    url: "/eliminarSecuencia/" + enviado + "/" + id_delet,
                    success: function(response) {
                        $('#success_message').addClass('alert alert-success')
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);
                        $('#DeleteSecuenciaModal').modal('hide');

                        visualizarCip02();
                    }

                });

            });
        </script>


        <!-- APLICANDO AJAX PARA LA INSERCCION DE DATOS DE LA TABLA Y EL USO DE UPDATE Y EDIT -->
    @endsection
