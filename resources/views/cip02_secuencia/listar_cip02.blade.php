@extends('layouts.app')
@section('contenido')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    </head>

    <!-- Modal PARA EDITAR-->
    <div class="modal fade" id="editarModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar secuencia Cip02</h5>
                    <ul id="updateform_errlist"></ul>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_secuencia2">

                    <div class="form-group mb-3">
                        <label for="">Secuencia</label>
                        <input type="text" id="edit_name2" class="name form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary update_secuencia2 ">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <!-- USO DE HTML PARA VISTA -->
    <div class="row mt-1" style="width:100%; height:100%;">
        <div id="success_message"></div>
        <div class="col-0 col-lg-12 col-xxl-12 d-flex ">
            <div class="card">
                <table class="table table-striped response response " align="center center">
                    <thead>
                        <tr>
                            <th>VALOR</th>
                            <th>NOMBRE DE SECUENCIAS</th>
                            <th>ACCION REQUERIDA</th>
                        </tr>
                    </thead>
                    <tbody id="tableCip02">

                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- USO DE HTML PARA VISTA -->

    <!-- APLICANDO AJAX PARA LA INSERCCION DE DATOS DE LA TABLA Y EL USO DE UPDATE Y EDIT -->

    <script>
        visualizarCip02()

        function visualizarCip02() {
            $.ajax({
                type: "GET",
                url: "/vista-datos2",
                dataType: "json",
                success: function(response) {
                    //console.log(response.secuencias2);
                    $('#tableCip02').html("");
                    $.each(response.secuencias2, function(key, item2) {
                        $('#tableCip02').append(
                            '<tr>\
                                                                    <td>' + item2.valor + '</td>\
                                                                    <td>' + item2.secuencia + '</td>\
                                                                    <td><button type="button" value="' + item2.valor + '" class="edit_secuencia2 btn btn-primary">  <img src="img/pencil-square.svg"> Editar</button> </td>\
                                                                    </tr>'

                        );

                    });
                }

            });
        }
        //OBTENIENDO EL ID DE CADA UNA DE LAS FILAS PARA PODER MANDAR LOS DATOS JSON
        $(document).on('click', '.edit_secuencia2', function(e) {
            e.preventDefault();
            //OBTENIENDO EL ID
            var valor_id2 = $(this).val();
            //COMPROBANDO POR MEDIO DE LA CONSOLA DEL NAVEGADOR SI NOS REGRESA EL ID CORRECTO DE CADA UNA DE LAS FILAS
            //console.log(valor_id2);
            $('#editarModal2').modal('show');
            $.ajax({
                type: "GET",
                url: "/editar-cip02/" + valor_id2,
                success: function(response) {
                    // console.log(response);
                    if (response.status == 404) {
                        $('#success_message').html("");
                        $('#success_message').addClass("alert alert-danger");
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);

                    } else {
                        $('#edit_secuencia2').val(valor_id2);
                        $("#edit_name2").val(response.secuencias.secuencia);

                    }
                }
            });

            $(document).on('click', '.update_secuencia2', function(e) {
                e.preventDefault();

                var secuencia2_id = $('#edit_secuencia2').val();
                var data = {
                    'name': $('#edit_name2').val(),
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "/actualizar-secuencia2/" + secuencia2_id,
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

        });
    </script>


    <!-- APLICANDO AJAX PARA LA INSERCCION DE DATOS DE LA TABLA Y EL USO DE UPDATE Y EDIT -->
@endsection
