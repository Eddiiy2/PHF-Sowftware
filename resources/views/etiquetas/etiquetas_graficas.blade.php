@extends('layouts.app')
@section('contenido')
    {{-- USO DE HEAD ETIQUEAS_GRAFICAS1 --}}

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="stylesheet" href="{{ asset('build/assets/modal.css') }}">

    </head>
    {{-- END USO DE HEAD ETIQUEAS_GRAFICAS1 --}}

    {{-- MODAL PARA AGREGAR ETIQUETAS A LA GRAFICA 1 --}}
    <div class="modal fade" id="AddEtiquetas1Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Añadir nueva etiqueta </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <ul id="saveform_errList"></ul>

                    <div class="form-group mb-3">
                        <label for="">D1</label>
                        <input type="text" id="AddD1G1" class="d1 form-control">
                        <label for="">D2</label>
                        <input type="text" id="AddD2G1" class="d2 form-control">
                        <label for="">D3</label>
                        <input type="text" id="AddD3G1" class="d3 form-control">
                        <label for="">Area</label>
                        <input type="text" id="AddAreaG1" class="area form-control">
                        <label for="">Cip</label>
                        <input type="number" id="AddCipG1" class="idcip form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary add_etiqueta1">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    {{-- END MODAL PARA AGREGAR ETIQUETAS A LA GRAFICA 1 --}}

    {{-- MODAL PARA EDITAR ETIQUETAS_GRAFICA1 --}}
    <div class="modal fade" id="EditarEtiquetas1Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar etiqueta </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul id="updateform_errlist"></ul>

                    <input type="hidden" id="edit_etiquetas2">

                    <div class="form-group mb-3">
                        <label for="">D1</label>
                        <input type="text" id="Edit_D1G1" class="d1 form-control">
                        <label for="">D2</label>
                        <input type="text" id="Edit_D2G1" class="d2 form-control">
                        <label for="">D3</label>
                        <input type="text" id="Edit_D3G1" class="d3 form-control">
                        <label for="">Area</label>
                        <input type="text" id="Edit_AreaG1" class="area form-control">
                        <label for="">Cip</label>
                        <input type="number" id="Edit_CipG1" class="idcip form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary update_etiqueta1">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL PARA EDITAR ETIQUETAS_GRAFICA1  --}}

    {{-- MODAL PARA ELIMINAR ETIQUETAS DE GRAFICA 1 --}}
    <div class="modal fade" id="DeleteEtiquetas1Modal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="delete_1">

                    <h4>¿Esta seguro de eliminar la etiqueta?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary delete_etiqueta1">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    {{-- END MODAL PARA ELIMINAR ETIQUETAS DE GRAFICA 1 --}}

    {{-- USO DE HTML PAA ETIQUETAS_GRAFICAS1 --}}
    <div class="row mt-1" style="width:100%; height:100%;">
        <div class="row">
            <div class="col-md-12">

                <div id="success_message"></div>

                <div class="col-0 col-lg-12 col-xxl-12 d-flex">
                    <div class="card-body">
                        <h4 style='font-size: 20px; color:black;'><strong> Etiquetas Grafica </strong></h4>
                        <strong style="display: none; " id="grafica"> {{ $grafica }}</strong>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#AddEtiquetas1Modal">
                            Añadir nueva etiqueta
                        </button>
                        <table class="table table-striped response response " align="center center">
                            <thead id="contenido">

                            </thead>
                            <tbody id="tableEtiquetas1">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- END USO DE HTML PAA ETIQUETAS_GRAFICAS1 --}}

    {{-- USO DE AJAX --}}

    <script>


        etiquetas1();

        function etiquetas1() {
            var graficas = document.getElementById("grafica").innerHTML.trim();
            //console.log(graficas);
            $.ajax({
                type: "GET",
                url: "/etiqueta/" + graficas,
                dataType: "JSON",
                success: function(response) {
                    //console.log(response);
                    $("#tableEtiquetas1").html("");
                    $.each(response.tabla, function(key, eti1) {
                        $("#tableEtiquetas1").append(
                            '<tr>\<td>' + eti1.id + '</td>\
                                                 <td>' + eti1.d1 + '</td>\
                                                 <td>' + eti1.d2 + '</td>\
                                                                        <td>' + eti1.d3 + '</td>\
                                                                        <td>' + eti1.area + '</td>\
                                                                        <td>' + eti1.idcip + '</td>\
                                                                        <td><button type="button" value="' + eti1.id + '" class="edit_etiquetas1 btn btn-primary btn-sm">  Editar</button> </td>\
                                                                        <td><button type="button" value="' + eti1.id + '" class="delete_etiquetas1 btn btn-danger btn-sm"> Eliminar</button> </td>\
                                                                        </tr>'
                        );
                        titulos();

                    });
                }
            });

        }
        function titulos(){
            contenido.innerHTML = ''
            contenido.innerHTML += `
            <tr>
                <th>ID</th>
                <th>D1</th>
                <th>D2</th>
                <th>D3</th>
                <th>AREA</th>
                <th>CIP</th>
                <th>ACCION</th>
            </tr>
                `;
        }

        $(document).ready(function() {
            $(document).on('click', '.add_etiqueta1', function(e) {
                e.preventDefault();

                var graficas = document.getElementById("grafica").innerHTML.trim();
                //console.log(graficas);
                //console.log("hola");

                //recolectando los dato
                var data = {
                    'd1': $('.d1').val(),
                    'd2': $('.d2').val(),
                    'd3': $('.d3').val(),
                    'area': $('.area').val(),
                    'idcip': $('.idcip').val(),
                };
                //console.log(data);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "/agregarEtiqueta/" + graficas,
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
                            $('#AddEtiquetas1Modal').modal('hide');
                            $('#AddEtiquetas1Modal').find('input').val("");
                            etiquetas1();
                        }

                    }

                });

            });
        });

        $(document).on('click', '.edit_etiquetas1', function(e) {
            e.preventDefault();
            var graficas = document.getElementById("grafica").innerHTML.trim();
            //console.log(graficas);

            var id_etiqueta1 = $(this).val();

            console.log(id_etiqueta1);
            $('#EditarEtiquetas1Modal').modal('show');
            $.ajax({
                typ: "GET",
                url: "/editarEtiqueta/" + graficas + "/" + id_etiqueta1,
                success: function(response) {
                    //console.log(response);
                    if (response.status == 404) {
                        $('#success_message').html("");
                        $('#success_message').addClass("alert alert-danger");
                        $('#success_message').text(response.message);
                        $('#success_message').fadeOut(5000);

                    } else {
                        $('#edit_etiquetas2').val(id_etiqueta1);

                        $("#Edit_D1G1").val(response.etiquetas1.d1);
                        $("#Edit_D2G1").val(response.etiquetas1.d2);
                        $("#Edit_D3G1").val(response.etiquetas1.d3);
                        $("#Edit_AreaG1").val(response.etiquetas1.area);
                        $("#Edit_CipG1").val(response.etiquetas1.idcip);

                    }
                }
            });
        });

        $(document).on('click', '.update_etiqueta1', function(e) {
            e.preventDefault();
            var graficas = document.getElementById("grafica").innerHTML.trim();
            //console.log(graficas);

            var eti1 = $('#edit_etiquetas2').val();

            var data = {
                'd1': $('#Edit_D1G1').val(),
                'd2': $('#Edit_D2G1').val(),
                'd3': $('#Edit_D3G1').val(),
                'area': $('#Edit_AreaG1').val(),
                'idcip': $('#Edit_CipG1').val(),
            }
            //console.log(data);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "PUT",
                url: "/actualizarEtiqueta/" + graficas + "/" + eti1,
                data: data,
                dataType: "json",
                success: function(response) {
                    //console.log(response);
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

                        $('#EditarEtiquetas1Modal').modal('hide');

                        etiquetas1();

                    }

                }

            });
        });
        $(document).on('click', '.delete_etiquetas1 ', function(e) {
            e.preventDefault();


            var id_delete1 = $(this).val();
            //alert(id_delete);
            $('#delete_1').val(id_delete1);
            $('#DeleteEtiquetas1Modal').modal('show');

        });


        $(document).on('click', '.delete_etiqueta1', function(e) {
            e.preventDefault();
            var graficas = document.getElementById("grafica").innerHTML.trim();
            //console.log(graficas);
            var id_delete1 = $('#delete_1').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "DELETE",
                url: "/eliminarEtiquetas/" + graficas + "/" + id_delete1,
                success: function(response) {
                    //console.log(response);
                    $('#success_message').addClass('alert alert-success')
                    $('#success_message').text(response.message);
                    $('#success_message').fadeOut(5000);
                    $('#DeleteEtiquetas1Modal').modal('hide');

                    etiquetas1();

                }

            });


        });
    </script>
    {{-- USO DE AJAX --}}
@endsection
