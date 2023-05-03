@extends('layouts.app')
@section('contenido')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    </head>

    <!-- Modal -->
    <div class="modal fade" id="editarModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar secuencia Cip01</h5>

                    <ul id="updateform_errlist"></ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>

                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_secuencia">

                    <div class="form-group mb-3">
                        <label for="">Secuencia</label>
                        <input type="text" id="edit_name" class="name form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary update_secuencia ">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- USO DE HTML -->
    <div class="row mt-1" style="width:100%; height:100%;">
        <div id="success_message"></div>

        <div class="col-0 col-lg-12 col-xxl-12 d-flex ">


            <div class="card-body">
                <table class="table table-striped response response " align="center center">
                    <thead>
                        <tr>
                            <th>VALOR</th>
                            <th>NOMBRE DE SECUENCIAS</th>
                            <th>ACCION REQUERIDA</th>
                        </tr>
                    </thead>
                    <tbody id="tableCip01">

                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <script>
        $(document).ready(function() {

            vistaDato();

            function vistaDato() {
                $.ajax({
                    type: "GET",
                    url: "/vista-datos",
                    dataType: "json",
                    success: function(response) {
                        //console.log(response.secuencias);
                        //PARA QUWE PRIMERO VACIE LOS DATOS Y LUEGO CARGE EL CONTENIDO ACTUALIZADO
                        $('#tableCip01').html("");
                        $.each(response.secuencias, function(key, item) {
                            $('#tableCip01').append(
                                '<tr>\
                                        <td>' + item.valor + '</td>\
                                        <td>' + item.secuencia + '</td>\
                                        <td><button type="button" value="' + item.valor + '" class="edit_secuencia btn btn-primary">  <img src="img/pencil-square.svg"> Editar</button> </td>\
                                        </tr>'
                            );

                        });

                    }

                });

            }

            //creando funcion para obtener el id para asi poder editar los datos de la tabla
            $(document).on('click', '.edit_secuencia', function(e) {
                e.preventDefault();
                //se manda atraes el id y por medio de console identificamos que si esta devolviendo el valor del id correspodiente
                var valor_id = $(this).val();
                //console.log(valor_id);
                //aca mandamos a hbablar al modal para poder editar
                $('#editarModal').modal('show');
                $.ajax({
                    type: "GET",
                    url: "/editar-cip01/" + valor_id,
                    success: function(response) {
                        //console.log(response);

                        if (response.status == 404) {
                            $('#success_message').html("");
                            $('#success_message').addClass("alert alert-danger");
                            $('#success_message').text(response.message);
                            $('#success_message').fadeOut(5000);

                        } else {
                            $('#edit_secuencia').val(valor_id);
                            $("#edit_name").val(response.secuencias.secuencia);

                        }
                    }

                });

            });

            //creando funcion del botoon para actualizar los registros
            $(document).on('click', '.update_secuencia', function(e) {
                e.preventDefault();

                var secuencia_id = $('#edit_secuencia').val();
                var data = {
                    'name': $('#edit_name').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "/actualizar-secuencia1/" + secuencia_id,
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        //console.log(response);

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


                            $('#editarModal').modal('hide');

                            vistaDato();
                        }

                    }

                })

            });


        });
    </script>
@endsection
