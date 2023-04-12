<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="build/assets/app.css" rel="stylesheet">

</head>

<body>
    <!-- Section: Design Block -->
    <div class="row mt-1" style="width:100%; height:100%;">
        <section class="text-center">
            <!-- Background image -->
            <div class="p-5 bg-image"
                style="
          background-image: url('https://mdbootstrap.com/img/new/textures/full/171.jpg');
          height: 300px;">
            </div>
            <!-- Background image -->

            <div class="card mx-4 mx-md-5 shadow-5-strong"
                style="
          margin-top: -120px;
          background: hsla(0, 17%, 80%, 0.8);
          backdrop-filter: blur(30px);">

                <div class="card-body py-5 px-md-5">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <h2 class="fw-bold mb-5">PHF SOFTWARE IE4.0 V2023</h2>
                            <form action="{{ route('validar') }}" method="POST">
                                @csrf
                                <!-- 2 column grid layout with text inputs for the first and last names -->

                                <!-- Email input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="form3Example3">Correo Electronico</label>
                                    <input type="email" name="correo" id="campo_usuario" class="form-control" />

                                </div>

                                <!-- Password input -->
                                <div class="form-outline mb-4">
                                    <label class="form-label" for="form3Example4">Contraseña</label>
                                    <input type="password" name="clave" id="campo_c" class="form-control" />

                                </div>

                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-center mb-4">
                                    <input class="form-check-input me-2" type="checkbox" value=""
                                        id="form2Example33" checked />
                                    <label class="form-check-label" for="form2Example33">
                                        Recuerdame
                                    </label>
                                </div>
                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary btn-block mb-4">
                                    Ingresar
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </section>
    <!-- Section: Design Block -->



</body>

</html>
