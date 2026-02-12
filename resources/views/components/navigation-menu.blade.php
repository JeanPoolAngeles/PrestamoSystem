<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                @can('admin.usuarios.index')
                    <div class="sb-sidenav-menu-heading">PANEL PRINCIPAL</div>
                    <a class="nav-link" href="{{ route('panel') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">ADMINISTRACIÓN</div>
                    <a class="nav-link" href="{{ route('admin.compania.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        Compañia
                    </a>
                    <a class="nav-link" href="{{ route('admin.backup.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        Backup BD
                    </a>
                    <a class="nav-link" href="{{ route('admin.formas.index') }}">
                        <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                        Forma pagos
                    </a>
                @endcan
                <div class="sb-sidenav-menu-heading">PAGINAS</div>
                @can('admin.prestamos.index')
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapsePrestamos" aria-expanded="false" aria-controls="collapsePrestamos">
                        <div class="sb-nav-link-icon"><i class="fas fa-dollar-sign 5x"></i></div>
                        PRESTAMOS
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapsePrestamos" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            @can('admin.prestamos.index')
                                <a class="nav-link" href="{{ route('admin.prestamos.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                    NUEVO PRESTAMO
                                </a>
                                <a class="nav-link" href="{{ route('admin.prestamos.show') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                    LISTADO PRESTAMOS
                                </a>
                            @endcan
                        </nav>
                    </div>
                @endcan
                @can('admin.clientes.index')
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseClientes" aria-expanded="false" aria-controls="collapseClientes">
                        <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                        CLIENTES
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseClientes" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            @can('admin.prestamos.index')
                                <a class="nav-link" href="{{ route('admin.clientes.create') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                    NUEVO CLIENTE
                                </a>
                                <a class="nav-link" href="{{ route('admin.clientes.index') }}">
                                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                    LISTA DE CLIENTES
                                </a>
                            @endcan
                        </nav>
                    </div>
                @endcan
            </div>
        </div>
        <hr class="text-white">
        <div class="sb-sidenav-footer bg-secondary text-white text-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="logo-container">
                        <img src="{{ asset('storage/' . $compania->foto) }}" alt="Logo" class="img-fluid"
                            style="width: auto; height: auto; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </nav>
</div>
