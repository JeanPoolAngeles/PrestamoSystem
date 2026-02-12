@extends('template')

@section('title', 'LISTAR-PRESTAMOS')

@section('content')
    <div class="card mt-1  text-center bg-primary text-white">
        <div class="card-body">
            <h1>ADMINISTRACIÓN DE HISTORIAL DE PRESTAMOS</h1>
        </div>
    </div>
    <div class="card-body" style="padding: 15px;">
        <div class="row">
            <div class="col-sm-12">
                @can('admin.prestamos.reportes')
                    <div class="mb-2">
                        <a href="#" id="exportPdf" class="btn btn-danger btn-sm" target="_blank">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                        <a href="#" id="exportExcel" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                @endcan
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover display responsive nowrap" width="100%"
                                id="tblPrestamos">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Total Prestado</th>
                                        <th>Total a Pagar</th>
                                        <th>Cliente</th>
                                        <th>Método</th>
                                        <th>Método de Pago</th>
                                        <th>Fecha/Hora</th>
                                        <th>Letra</th>
                                        <th>Garantia</th>
                                        <th>Foto</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <form id="deleteForm" action="#" method="post">
        @csrf
        @method('PUT')
    </form>
@endsection

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <style>
        .dt-buttons button {
            background: lightgray;
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        var ticketUrl = "{{ route('admin.prestamos.ticket', ['id' => ':prestamo']) }}";
        var anularUrl = "{{ route('admin.prestamos.anular', ['id' => ':prestamo']) }}";
        var editUrl = "{{ route('admin.prestamos.edit', ['id' => ':prestamo']) }}";

        document.addEventListener("DOMContentLoaded", function() {
            new DataTable('#tblPrestamos', {
                responsive: true,
                ajax: {
                    url: '{{ route('admin.prestamos.list') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'monto'
                    },
                    {
                        data: 'total_pagar'
                    },
                    {
                        data: 'cliente'
                    },
                    {
                        data: 'metodo'
                    },
                    {
                        data: 'metodo_pago'
                    },
                    {
                        data: 'created_at',
                        render: function(data) {
                            return new Date(data).toLocaleString();
                        }
                    },
                    {
                        data: 'letra',
                        render: function(data) {
                            return data ?
                                `<img src="${data}" alt="Letra" style="max-width: 80px; max-height: 80px;">` :
                                'Sin imagen';
                        }
                    },
                    {
                        data: 'garantia',
                        render: function(data) {
                            return data ?
                                `<img src="${data}" alt="Garantía" style="max-width: 80px; max-height: 80px;">` :
                                'Sin imagen';
                        }
                    },
                    {
                        data: 'foto',
                        render: function(data) {
                            return data ?
                                `<img src="${data}" alt="Foto del Préstamo" style="max-width: 80px; max-height: 80px;">` :
                                'Sin imagen';
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            return `
                                <a class="btn btn-sm btn-warning" onclick="anularPrestamo(${row.id})" href="#">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a class="btn btn-sm btn-primary" href="${editUrl.replace(':prestamo', row.id)}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                `;
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/es-ES.json',
                },
                dom: "<'row'<'col-sm-12 text-center'B>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'>,'PQlfrtip' ",
                buttons: [{
                        // Botón para Excel
                        extend: 'excelHtml5',
                        footer: true,
                        text: '<span class="badge badge-success"><i class="fas fa-file-excel"></i></span>'
                    },
                    {
                        // Botón para PDF
                        extend: 'pdfHtml5',
                        download: 'open',
                        footer: true,
                        text: '<span class="badge  badge-danger"><i class="fas fa-file-pdf"></i></span>',
                        exportOptions: {
                            columns: [0, ':visible']
                        }
                    },
                    {
                        // Botón para imprimir
                        extend: 'print',
                        footer: true,
                        text: '<span class="badge bg-purple"><i class="fas fa-print"></i></span>'
                    },
                    {
                        // Botón para CSV
                        extend: 'csvHtml5',
                        footer: true,
                        text: '<span class="badge  badge-success"><i class="fas fa-file-csv"></i></span>'
                    },
                    {
                        extend: 'colvis',
                        text: '<span class="badge  badge-info"><i class="fas fa-columns"></i></span>',
                        postfixButtons: ['colvisRestore']
                    }
                ],
                searchPanes: {
                    columns: [3, 4]
                },
                order: [
                    [0, 'desc']
                ]
            });
        });

        function anularprestamos(prestamosId) {
            Swal.fire({
                title: "Anular prestamos!!",
                text: "¿Estás seguro de anular la prestamos?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, anular!"
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.querySelector('#deleteForm');
                    form.action = anularUrl.replace(':prestamo', prestamosId);
                    form.submit();
                    mostrarAlerta('El prestamo se anuló', 'success');
                }
            });
        }

        function mostrarAlerta(texto, icono) {
            Swal.fire({
                showConfirmButton: false,
                title: "Respuesta",
                text: texto,
                icon: icono,
                toast: true,
                timer: 3500,
                position: "top-end",
            });
        }
    </script>
@endsection
