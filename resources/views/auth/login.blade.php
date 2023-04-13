<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Login</title>
    <link href="build/assets/app.css" rel="stylesheet">
    <script src="build/assets/app.js"></script>
</head>

<body id="particles-js"></body>
<div class="animated bounceInDown">
    <div class="container">
        <span class="error animated tada" id="msg"></span>
        <form name="form1" action="{{ route('validar') }}" method="POST" class="box"
            onsubmit="return checkStuff()">
            @csrf
            <h4>PHF <span>SOFTWARE IE4.0 V2023</span></h4>
            <label class="poner_correo" for="form3Example3">Correo Electronico</label>
            <input type="email" name="correo" id="campo_usuario" class="form-control"
                placeholder="Correo Electronico" autocomplete="off" />
            <i class="typcn typcn-eye" id="eye"></i>
            <label class="poner_contraseña" for="form3Example4">Contraseña</label>
            <input input type="password" name="clave" id="campo_clave" class="form-control" placeholder="Contraseña"
                autocomplete="on" />

            <input type="submit" value="Ingresar" class="btn1">
        </form>

    </div>

</div>

</html>
