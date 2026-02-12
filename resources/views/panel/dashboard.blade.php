@extends('template')

@section('title', 'VISTA-Dashboard')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .icon-container {
            width: 120px;
            height: 120px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 70%;
        }

        .bg-gradient-success {
            background: linear-gradient(90deg, rgba(72, 199, 142, 1) 0%, rgba(40, 167, 69, 1) 100%);
            color: #fff;
        }

        .bg-gradient-primary {
            background: linear-gradient(90deg, rgba(0, 123, 255, 1) 0%, rgba(0, 83, 180, 1) 100%);
            color: #fff;
        }

        .bg-cliente-primary {
            background: linear-gradient(90deg, rgb(243, 201, 14) 0%, rgba(248, 170, 2, 0.795) 100%);
            color: #fff;
        }

        .bg-proveedor-primary {
            background: linear-gradient(90deg, rgb(51, 7, 133) 0%, rgba(27, 2, 73, 0.795) 100%);
            color: #fff;
        }

        .bg-compra-primary {
            background: linear-gradient(90deg, rgb(223, 36, 36) 0%, rgb(236, 69, 69) 100%);
            color: #fff;
        }

        .bg-categoria-primary {
            background: linear-gradient(90deg, rgb(6, 68, 63) 0%, rgb(12, 64, 68) 100%);
            color: #fff;
        }

        .bg-gastos-primary {
            background: linear-gradient(90deg, rgb(226, 8, 255) 0%, rgb(234, 0, 255) 100%);
            color: #fff;
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <script>
            let message = "{{ session('success') }}";
            Swal.fire(message);
        </script>
    @endif
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-header bg-primary ">
                <div class="card-body text-center">
                    <h1 class="mt-4 text-white">PANEL DE VISTA</h1>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            @can('admin.venta.index')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-gradient-success text-white mb-4">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-container me-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-cash-register fa-3x"></i>
                            </div>
                            <div class="mt-4 mb-4 text-container">
                                <span>Generar Nuevo Prestamo</span>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('admin.prestamos.index') }}">Ir a
                                prestamos</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('admin.clientes.index')
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-cliente-primary text-white mb-4">
                        <div class="card-body d-flex align-items-center">
                            <div class="icon-container me-3 d-flex align-items-center justify-content-center">
                                <i class="fas fa-users fa-3x"></i>
                            </div>
                            <div class="mt-2 mb-2 text-container">
                                <span>Clientes: {{ $totales['clients'] }}</span>
                            </div>
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="{{ route('admin.clientes.index') }}">Ver Más</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            @endcan
        </div>
        <hr class="mt-4">
        @can('admin.cajas.index')
            <hr>
            <div class="col-md-12">
                <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </div>
        @endcan
    </div>
@endsection

@section('js')
    <script>
        /*
        document.addEventListener('DOMContentLoaded', function() {

            if (document.getElementById('prestamosPorSemana')) {
                prestamosSemana()
            }

            //prestamos
            var dataPrestamo = @json($prestamos);

            // Verifica si hay datos de prestamos o compras
            var hayDatosPrestamo = dataPrestamo && Object.keys(dataPrestamo).length > 0;

            if (hayDatosPrestamo) {
                if (document.getElementById('prestamosPorMes')) {
                    var ctx = document.getElementById('prestamosPorMes').getContext('2d');
                    var dataPrestamos = @json($prestamos);

                    var datasets = Object.keys(dataPrestamos).map(year => ({
                        label: `Préstamos ${year}`,
                        data: Object.values(dataPrestamos[year]),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }));

                    var labels = Object.keys(dataPrestamos[Object.keys(dataPrestamos)[0]]);

                    var chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: datasets
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            } else {
                // Si no hay datos, puedes realizar alguna acción de manejo de error o simplemente mostrar un mensaje
                console.log('No hay datos disponibles para mostrar el gráfico.');
            }
        });


        function prestamosSemana() {
            var ctx = document.getElementById('prestamosPorSemana').getContext('2d');
            var prestamosData = {!! json_encode($prestamosPorSemana) !!};

            var labels = prestamosData.map(item => item.diaEnEspanol);
            var prestamosValores = prestamosData.map(item => item.monto);

            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Préstamos por semana',
                        data: prestamosValores,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }*/
    </script>
@endsection
