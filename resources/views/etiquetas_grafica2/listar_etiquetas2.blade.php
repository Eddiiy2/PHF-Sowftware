@extends('layouts.app')
@section('contenido')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"
            integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>

    </head>
    {{-- MODAL PARA EL AGREGAR NUEVA ETIQUETA --}}
    <div class="modal fade" id="AddEtiquetas2Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir nueva etiqueta para Cip1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul id="saveform_errList"></ul>

                    <div class="form-group mb-3">
                        <label for="">D1</label>
                        <input type="text" id="AddD1" class="d1 form-control">
                        <label for="">D2</label>
                        <input type="text" id="AddD2" class="d2 form-control">
                        <label for="">Area</label>
                        <input type="text" id="AddArea" class="area form-control">
                        <label for="">Cip</label>
                        <input type="number" id="AddCip" class="cip form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary add_etiqueta2">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL PARA EL AGREGAR NUEVA ETIQUETA --}}

    {{-- MODAL PARA EDITAR ETIQUETAS2  --}}
    <div class="modal fade" id="EditarEtiquetas2Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar etiqueta para Cip2</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="updateform_errlist"></ul>

                    <input type="hidden" id="edit_etiquetas2">

                    <div class="form-group mb-3">
                        <label for="">D1</label>
                        <input type="text" id="Edit_D1" class="d1 form-control">
                        <label for="">D2</label>
                        <input type="text" id="Edit_D2" class="d2 form-control">
                        <label for="">Area</label>
                        <input type="text" id="Edit_Area" class="area form-control">
                        <label for="">Cip</label>
                        <input type="number" id="Edit_Cip" class="cip form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary update_etiqueta2">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- end MODAL PARA EDITAR ETIQUETAS2  --}}

    {{-- MODAL PARA ELIMINAR --}}
    <div class="modal fade" id="DeleteEtiquetas2Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="delete_2">

                    <h4>¿Esta seguro de eliminar la etiqueta?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary delete_etiqueta2">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL PARA ELIMINAR --}}

    {{-- USO DE HTML PARA VISTA DE ETIQUETAS PARA GRAFICA 2 --}}
    <div class="row mt-1" style="width:100%; height:100%;">
        <div class="row">
            <div class="col-md-12">
                <div id="success_message"></div>

                <div class="col-0 col-lg-12 col-xxl-12 d-flex">

                    <div class="card-body">
                        <h4 style='font-size: 20px; color:black;'><strong> Etiquetas Grafica 2 </strong></h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#AddEtiquetas2Modal">
                            Añadir nueva etiqueta
                        </button>

                        <table class="table table-striped response response " align="center center">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>D1</th>
                                    <th>D2</th>
                                    <th>AREA</th>
                                    <th>CIP</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody id="tableEtiquetas2">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END USO DE HTML PARA VISTA DE ETIQUETAS PARA GRAFICA 2 --}}


    {{-- AJAX PARA EL USO DE EDI DE ETIQUETAS PARA GRAFICA 2 --}}

    <script>
        //PARA PODER VER LOS DATOS DEBEDEMOS DE SACAR LA FUNCION Y ASIPODER VISUALIZARLO EN CONSOLA
        etiquetas2();

        function etiquetas2() {
            $.ajax({
                type: "GET",
                url: "/vista-etiquetas2",
                dataType: "json",
                success: function(response) {
                    //console.log(response.etiquetas2);
                    $("#tableEtiquetas2").html("");
                    $.each(response.etiquetas2, function(key, eti2) {
                        $("#tableEtiquetas2").append(
                            '<tr>\<td>' + eti2.id + '</td>\
                                                                             <td>' + eti2.d1 + '</td>\
                                                                             <td>' + eti2.d2 + '</td>\
                                                                             <td>' + eti2.area + '</td>\
                                                                             <td>' + eti2.idcip + '</td>\
                                                                             <td><button type="button" value="' + eti2.id + '" class="edit_etiquetas2 btn btn-primary btn-sm"> <img src="img/pencil-square.svg"> </button> </td>\
                                                                             <td><button type="button" value="' + eti2.id + '" class="delete_etiquetas2 btn btn-danger btn-sm" style= "margin: 0px -60px;"> <img src="img/delet.svg"> </button> </td>\
                                                                             </tr>'
                        );
                    });
                }
            });
        }


        //METODO PARA EL BOTON DE AGREGAR NUEVA ETIQUETA

        $(document).ready(function() {
            $(document).on('click', '.add_etiqueta2', function(e) {
                e.preventDefault(); //evita recarga la pagina
                //console.log("hols");


                var data = {
                    'd1': $('.d1').val(),
                    'd2': $('.d2').val(),
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
                    type: "POST",
                    url: "/agregar-etiqueta2",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        //console.log(response);
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
                            $('#AddEtiquetas2Modal').modal('hide');
                            $('#AddEtiquetas2Modal').find('input').val("");
                            etiquetas2();
                        }
                    }
                });

            });

        });

        //AJAX PARA EL BOTON DE EDITAR
        $(document).on('click', '.edit_etiquetas2 ', function(e) {
            e.preventDefault();
            //OBTENIENDO EL ID
            var id_etiquetas2 = $(this).val();
            //console.log(id_etiquetas2);
            $('#EditarEtiquetas2Modal').modal('show');
            $.ajax({
                type: "GET",
                url: "/editar-etiqueta2/" + id_etiquetas2,
                success: function(response) {
                    //console.log(response);
                    if (response.status == 404) {
                        $('#success_message').html("");
                        $('#success_message').addClass("alert alert-danger");
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);

                    } else {
                        $('#edit_etiquetas2').val(id_etiquetas2);
                        $("#Edit_D1").val(response.etiquetas2.d1);
                        $("#Edit_D2").val(response.etiquetas2.d2);
                        $("#Edit_Area").val(response.etiquetas2.area);
                        $("#Edit_Cip").val(response.etiquetas2.idcip);

                    }
                }
            });

        });

        $(document).on('click', '.update_etiqueta2', function(e) {
            e.preventDefault();

            var etiqueta_id2 = $('#edit_etiquetas2').val();
            var data = {
                'd1': $('#Edit_D1').val(),
                'd2': $('#Edit_D2').val(),
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
                url: "/actualizar-etiqueta2/" + etiqueta_id2,
                data: data,
                dataType: "json",
                success: function(response) {
                    console.log(response);
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

                        $('#EditarEtiquetas2Modal').modal('hide');

                        etiquetas2();

                    }
                }


            });

        });
        $(document).on('click', '.delete_etiquetas2 ', function(e) {
            e.preventDefault();
            var id_delete = $(this).val();
            //alert(id_delete);
            $('#delete_2').val(id_delete);
            $('#DeleteEtiquetas2Modal').modal('show');
        });

        $(document).on('click', '.delete_etiqueta2', function(e) {
            e.preventDefault();

            var id_delete = $('#delete_2').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            $.ajax({
                type: "DELETE",
                url: "/eliminar-etiquetas2/" + id_delete,
                success: function(response) {
                    //console.log(response);
                    $('#success_message').addClass('alert alert-success')
                    $('#success_message').text(response.message);
                    $('#success_message').fadeOut(5000);
                    $('#DeleteEtiquetas2Modal').modal('hide');

                    etiquetas2();


                }




            });

        });
    </script>
    {{-- END PARA EL USO DE EDI DE ETIQUETAS PARA GRAFICA 2 --}}
@endsection
