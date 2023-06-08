@extends('layouts.app')
@section('contenido')
    {{-- HEAD --}}

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    </head>
    {{-- END HEAD --}}

    {{-- MODAL PARA EL AGREGAR NUEVA MARCA --}}
    <div class="modal fade" id="AddMarcaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir nueva marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul id="saveform_errList"></ul>

                    <div class="form-group mb-3">
                        <label for="">Nombre</label>
                        <input type="text" id="AddNombre" class="nombre form-control">
                        <label for="">Area</label>
                        <input type="text" id="AddArea" class="area form-control">
                        <label for="">Cip</label>
                        <input type="number" id="AddCip" class="cip form-control">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary add_marca">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END  MODAL PARA EL AGREGAR NUEVA MARCA --}}

    {{-- MODAL PARA EDITAR ETIQUETAS2  --}}
    <div class="modal fade" id="EditarMarcaModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar etiqueta para Cip2</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="updateform_errlist"></ul>

                    <input type="hidden" id="edit_marcas">


                    <div class="form-group mb-3">
                        <label for="">Nombre</label>
                        <input type="text" id="Edit_Nombre" class="nombre form-control">
                        <label for="">Area</label>
                        <input type="text" id="Edit_Area" class="area form-control">
                        <label for="">Cip</label>
                        <input type="number" id="Edit_Cip" class="cip form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary update_marca">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end MODAL PARA EDITAR ETIQUETAS2  --}}

    {{-- USO DE HTML PARA VISTA DE MARCAS --}}
    <div class="row mt-1" style="width:100%; height:100%;">
        <div class="row">
            <div class="col-md-12">
                <div id="success_message"></div>
                <div class="col-0 col-lg-12 col-xxl-12 d-flex">
                    <div class="card-body">
                        <h4 style='font-size: 20px; color:black;'><strong> Marcas </strong></h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#AddMarcaModal">
                            Añadir nueva marca
                        </button>
                        <table class="table table-striped response response " align="center center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>NOMBRE</th>
                                    <th>AREA</th>
                                    <th>CIP</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody id="tableMarca">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- USO DE AJAX --}}

    <script>
        marcas();

        function marcas() {
            $.ajax({
                type: "GET",
                url: "/vista-marcas",
                dataType: "json",
                success: function(reponse) {
                    //console.log(reponse.marcas);
                    $("#tableMarca").html("");
                    $.each(reponse.marcas, function(key, mar) {
                        $("#tableMarca").append(
                            '<tr>\<td>' + mar.idmarca + '</td>\
                                                            <td>' + mar.nombre + '</td>\
                                                            <td>' + mar.area + '</td>\
                                                            <td>' + mar.idcip + '</td>\
                                                            <td><button type="button" value="' + mar.idmarca + '" class="edit_marcas btn btn-primary btn-sm"> <img src="img/pencil-square.svg"> </button> </td>\
                                                            <td><button type="button" value="' + mar.idmarca + '" class="delete_etiquetas2 btn btn-danger btn-sm" style= "margin: 0px -60px;"> <img src="img/delet.svg"> </button> </td>\
                                                            </tr>'

                        );
                    });
                }

            });
        }

        //METODO PARA AGREGAR NUEVA MARCA

        $(document).ready(function() {
            $(document).on('click', '.add_marca', function(e) {
                e.preventDefault();
                //console.log("hola");

                var data = {
                    'nombre': $('.nombre').val(),
                    'area': $('.area').val(),
                    'cip': $('.cip').val(),
                };
                //console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: "/agregar-marcas",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
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
                            $('#AddMarcaModal').modal('hide');
                            $('#AddMarcaModal').find('input').val("");
                            marcas();
                        }
                    }

                });

            });
        });

        //AJAX EDITAR MARCA

        $(document).on('click', '.edit_marcas', function(e) {
            e.preventDefault();

            var id_marcas = $(this).val();
            console.log(id_marcas);
            $('#EditarMarcaModal').modal('show');
            $.ajax({
                type: "GET",
                url: "/editar-marca/" + id_marcas,
                success: function(response) {
                    if (response.status == 404) {
                        $('#success_message').html("");
                        $('#success_message').addClass("alert alert-danger");
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);

                    } else {
                        $('#edit_marcas').val(id_marcas);
                        $("#Edit_Nombre").val(response.marcas.nombre);
                        $("#Edit_Area").val(response.marcas.area);
                        $("#Edit_Cip").val(response.marcas.idcip);

                    }

                }

            });
        });

        $(document).on('click', '.update_marca', function(e) {
            e.preventDefault();

            var marca_id = $('#edit_marcas').val();
            var data = {
                'nombre': $('#Edit_Nombre').val(),
                'area': $('#Edit_Area').val(),
                'cip': $('#Edit_Cip').val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/actualizar-marca/" + marca_id,
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

                        $('#updateform_errList').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);


                    } else {
                        $('#updateform_errList').html("");
                        $('#success_message').html("");
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);

                        $('#EditarMarcaModal').modal('hide');

                        marcas();

                    }

                }

            });

        });
    </script>

    {{-- END USO DE AJAX --}}
@endsection
