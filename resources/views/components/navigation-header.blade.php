<nav class="sb-topnav navbar navbar-expand navbar-light bg-primary">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3 text-center" href="{{ route('panel') }}">{{ config('app.name') }}</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <!--form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            <input class="form-control" type="text" placeholder="Buscar..." aria-label="Search for..."
                aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
        </div>
    </form-->
    <div class="container">
        <div class="card-body text-right text-white">
            <a>Bienvenido(a): {{ auth()->user()->name }}</a>
        </div>
    </div>
    <!-- Notification Icon -->
    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle position-relative" id="notificationDropdown" href="#"
                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>

            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-lg" aria-labelledby="notificationDropdown">
                <li class="dropdown-header text-center fw-bold text-primary">Notificaciones</li>
                <div class="dropdown-divider"></div>

            </ul>
        </li>

        <!-- User Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user fa-fw"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                @can('admin.usuarios.index')
                    <li><a class="dropdown-item" href="{{ route('profile.index') }}">Perfil</a></li>
                @endcan
                <li>
                    <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="{{ route('logout') }}">Cerrar Sesión</a></li>
            </ul>
        </li>
    </ul>
</nav>
