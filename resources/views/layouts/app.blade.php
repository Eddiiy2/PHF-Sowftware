<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <title> PHF SOFTWARE </title>

    <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/c3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all.css') }}">


    {{--  Importando js de C3 para las graficas y Ajax para la consulta de la tabla index  --}}
    <script src="{{ URL::asset('js/c3.js') }}"></script>
    <script src="{{ URL::asset('js/d3-v5-min.js') }}"></script>
    <script src="{{ URL::asset('build/assets/app.js') }}"></script>
    <script src="{{ URL::asset('js/ajax.js') }}"></script>
    <script src="{{ URL::asset('js/all.js') }}"></script>

    {{--  -----------------------------------------------------------------------  --}}



</head>

<body>

    <body>

        <div class="wrapper">
            <nav id="sidebar" class="sidebar js-sidebar">
                <div class="sidebar-content js-simplebar">
                    <a class="sidebar-brand" href="index.html">
                        <span class="align-middle">PHF SOFTWARE</span>
                    </a>

                    <ul class="nav-links" id="titulos-navegacion">
                        {{--  Aqui se pondran automaticamente los titulos de las areas mediante js  --}}
                    </ul>

                </div>
            </nav>

            <div class="main">
                @include('partial.nav')

                @yield('contenido')

            </div>


        </div>
    </body>
</body>


{{--  Ingresando los titulos y subtitulos de los cips obtenidos desde la base de datos por medio de ajax/fetch --}}
<script>
    $(document).ready(function() {
        var titulos_nav = document.getElementById("titulos-navegacion");

        fetch('/obtenerdivs')
            .then(response => response.json())
            .then(respuesta => {

                addtitulos(respuesta)
            });


        function addtitulos(respuesta) {
            let titulojson = respuesta;


            for (var i = 0; i < respuesta.length; i++) {
                titulos_nav.innerHTML += `
                <br><br>
                {{--  Primero div  --}}
                <li id='li-${ i+1 }' style="display:block;">
                    <div class="iocn-link">
                        <a>
                            <i id="iconos" class="fas fa-chart-area"></i>
                            <span class="link_name" id="titulos"> ${ respuesta[i].titulo } </span>
                        </a>
                        <span class="btn arrow" id="despliegue">▼</span>
                    </div>
                    <ul class="sub-menu" id="area${ i+1 }">
                        <li><a class="link_name"> ${ respuesta[i].titulo } </a></li>
                        <br>
                        {{--  <li><a href="/principal/Todos">► Todos </a></li>;  --}}
                        {{--  div para poner los cips desde la base de datos  --}}
                    </ul>
                </li>
                `;

                let n = i + 1;

                fetch('/nomcips/' + (i + 1))
                    .then(response => response.json())
                    .then(res => {
                        {{--  Agregando los valores al navbar  --}}
                        var menus = document.getElementById("area" + n);
                        for (let valor of res) {
                            const nom = valor.nombre;

                            menus.innerHTML +=
                                `<li><a href="/principal/${ valor.nombre }_area${ n }_${ titulojson[n-1].titulo }">►    ${ valor.nombre } </a></li>`;
                        }
                    });

            }

            titulos_nav.innerHTML += `
                <br><br>
                    <li>
                        <a href="/login">
                            <i class='fa fa-cog' id="iconos"></i>
                            <span class="link_name" id="titulos">Configuraciones</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link_name" >Configuraciones</a></li>
                        </ul>
                    </li>
                `;


            let arrow = document.querySelectorAll(".arrow");
            for (var i = 0; i < arrow.length; i++) {
                arrow[i].addEventListener("click", (e) => {
                    let arrowParent = e.target.parentElement
                        .parentElement; //selecting main parent of arrow
                    arrowParent.classList.toggle("showMenu");
                    verificar();
                });
            }

            function verificar() {
                var btn = document.getElementById("despliegue");
                if ($('#sub-menu').css('display') === 'block') {
                    btn.textContent = "▲";
                } else {
                    btn.textContent = "▼";
                }
            }

        }

    });
</script>

{{--  <script>
    let arrow = document.querySelectorAll(".arrow");
    for (var i = 0; i < arrow.length; i++) {
        arrow[i].addEventListener("click", (e) => {
            let arrowParent = e.target.parentElement.parentElement; //selecting main parent of arrow
            arrowParent.classList.toggle("showMenu");
            verificar();
        });
    }

    function verificar() {
        var btn = document.getElementById("despliegue");
        if ($('#sub-menu').css('display') === 'block') {
            btn.textContent = "▲";
        } else {
            btn.textContent = "▼";
        }
    };
</script> --}}



</html>
