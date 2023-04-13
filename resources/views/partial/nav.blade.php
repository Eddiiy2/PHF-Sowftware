<nav class="navbar navbar-expand navbar-light navbar-bg">
    <a class="sidebar-toggle js-sidebar-toggle">
        <i class="hamburger align-self-center"></i>
    </a>
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav navbar-align">
            <li class="nav-item dropdown">
                @can('EsAdmin')
                    <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                        <span class="text-dark">{{ Auth::user()->nombre }}</span>
                    </a>
                @endcan
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="{{ route('salir') }}"><i class="fas fa-sign-out"></i> Cerrar Sesión
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
