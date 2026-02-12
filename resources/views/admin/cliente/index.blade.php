@extends('template')

@section('title', 'VISTA-CLIENTES')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold ">ADMINISTRACIÓN DE LOS CLIENTES</h1>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="mb-2 btn-group" role="group" aria-label="Button group">
                    @can('admin.clientes.create')
                        <a href="{{ route('admin.clientes.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i>
                        </a>
                    @endcan
                    @can('admin.clientes.reportes')
                        <a href="{{ route('admin.clientes.pdf') }}" target="_blank" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a href="{{ route('admin.clientes.excel') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i>
                        </a>
                    @endcan
                </div>

                <div class="card">
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success" role="alert">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped table-hover display responsive nowrap" width="100%"
                                id="tblClients">
                                <thead class="thead">
                                    <tr>
                                        <th></th>
                                        <th># Identidad</th>
                                        <th>Nombre</th>
                                        <th>Teléfono</th>
                                        <th>Correo</th>
                                        <th>Dirección</th>
                                        <th>Fecha de Nacimiento</th>
                                        <th class="text-center"></th>
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
        @method('DELETE')
    </form>
@endsection

@section('css')
    <link href="DataTables/datatables.min.css" rel="stylesheet">
@endsection

@section('js')
    <script src="DataTables/datatables.min.js"></script>
    <script>
        var editUrl = "{{ route('admin.clientes.edit', ['cliente' => ':cliente']) }}";
        var deleteUrl = "{{ route('admin.clientes.destroy', ['cliente' => ':cliente']) }}";
        document.addEventListener("DOMContentLoaded", function() {
            new DataTable('#tblClients', {
                responsive: true,
                fixedHeader: true,
                ajax: {
                    url: '{{ route('admin.clients.list') }}',
                    dataSrc: 'data'
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'dni'
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        data: 'telefono'
                    },
                    {
                        data: 'correo'
                    },
                    {
                        data: 'direccion'
                    },
                    {
                        data: 'fecha_nacimiento'
                    },
                    {
                        // Agregar columna para acciones
                        data: null,
                        render: function(data, type, row) {
                            return `<a class="btn btn-sm btn-info" href="/creditoclientes/${row.id}">
                                        <i class="fas fa-eye"></i> Ver Créditos
                                    </a>
                                    <a class="btn btn-sm btn-primary ms-2" href="${editUrl.replace(':cliente', row.id)}">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-sm btn-danger ms-2" onclick="deleteClient(${row.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>`;
                        }

                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.0.3/i18n/es-ES.json',
                },
                order: [
                    [0, 'desc']
                ]
            });
        });

        // Función para eliminar un cliente
        function deleteClient(clientId) {
            Swal.fire({
                title: "Eliminar",
                text: "¿Estás seguro de que quieres eliminar este cliente?",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    var form = document.querySelector('#deleteForm');
                    form.action = deleteUrl.replace(':cliente', clientId);
                    // Enviar el formulario
                    form.submit();
                }
            });
        }
    </script>
@stop
