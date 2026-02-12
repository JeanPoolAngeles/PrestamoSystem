@extends('template')

@section('title', 'Creditos')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body ">
            <h1 class="fw-bold">ADMINISTRACIÓN DE CREDITOS</h1>
        </div>
    </div>
    <div class="container mt-1">
        <div class="row">
            <div class="col-sm-12">
                @can('admin.creditoclientes.reportes')
                    <div class="container mt-2 my-4 mb-2 ms-4">
                        <div class="mb-4">
                            <a href="{{ route('admin.creditoclientes.reportPdf') }}" class="btn btn-danger btn-sm"
                                target="_blank">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('admin.creditoclientes.reportExcel') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    </div>
                @endcan
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover display responsive nowrap" width="100%"
                                id="tblCreditos">
                                <thead class="thead">
                                    <tr>
                                        <th>Id</th>
                                        <th>Prestamo</th>
                                        <th>Monto</th>
                                        <th>Cliente</th>
                                        <th>Abonado</th>
                                        <th>Restante</th>
                                        <th>Fecha/Hora</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalAbono" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <h3>Abono</h3>
                    </h5>
                    <button class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" id="id_credito">
                        <div class="col-md-12 mb-2">
                            <label>Cliente <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary"><i class="fas fa-user"></i></span>
                                </div>
                                <input id="cliente" class="form-control" type="text" placeholder="Cliente" readonly>
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Teléfono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary"><i class="fas fa-phone"></i></span>
                                </div>
                                <input id="telefono" class="form-control" type="text" placeholder="Telefono" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Total <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                <input id="total" class="form-control" type="text" readonly placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Abonado <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                <input id="abonado" class="form-control" type="text" readonly placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Restante <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                <input id="restante" class="form-control" type="text" readonly placeholder="0.00">
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label>Abono <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary"><i class="fas fa-dollar-sign"></i></span>
                                </div>
                                <input id="monto" class="form-control" type="number" step="0.01" min="0.01"
                                    placeholder="0.00">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="forma">Forma Pago <span class="text-danger">*</span></label>
                            <select id="forma" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach ($formapagos as $formapago)
                                    <option value="{{ $formapago->id }}">{{ $formapago->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr>
                        <div class="form-group col-md-6">
                            <label for="foto">Foto</label>
                            <input type="file" id="foto" name="foto"
                                class="form-control {{ $errors->has('foto') ? 'is-invalid' : '' }}" accept="image/*">
                            {!! $errors->first('foto', '<div class="invalid-feedback">:message</div>') !!}
                            <p>Seleccione una imagen</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" id="btnProcesar" type="button">Completar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="{{ asset('DataTables/datatables.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endsection

@section('js')
    <script src="{{ asset('DataTables/datatables.min.js') }}"></script>
    <script>
        var ticketUrl = "{{ route('admin.creditoclientes.ticket', ['id' => ':creditocliente']) }}";
        var abonosUrl = "{{ route('admin.creditoclientes.abonos', ['id' => ':creditocliente']) }}";
        var detalleUrl = "{{ route('admin.creditoclientes.detalle', ['id' => ':creditocliente']) }}";
        const cliente = document.querySelector('#cliente');
        const telefono = document.querySelector('#telefono');
        const total = document.querySelector('#total');
        const abonado = document.querySelector('#abonado');
        const restante = document.querySelector('#restante');
        const monto = document.querySelector('#monto');
        const forma = document.querySelector('#forma');
        const id_credito = document.querySelector('#id_credito');
        const btnProcesar = document.querySelector('#btnProcesar');


        document.addEventListener("DOMContentLoaded", function() {
            let clienteId = window.location.pathname.split("/").pop(); // Extraer el ID de la URL

            new DataTable('#tblCreditos', {
                responsive: true,
                fixedHeader: true,
                ajax: {
                    url: `/listarCreditoclientes/${clienteId}`,
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.log("Error en DataTables:", xhr.responseText);
                    },
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('Accept', 'application/json');
                    }
                },
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'monto_prestamo'
                    },
                    {
                        data: 'total'
                    },
                    {
                        data: 'cliente'
                    },
                    {
                        data: 'abonado'
                    },
                    {
                        data: 'restante'
                    },
                    {
                        data: 'fecha',
                        render: function(data, type, row) {
                            return type === 'display' ? new Date(data).toLocaleString() : data;
                        }
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            const abonarButton = row.restante >= 1 ?
                                `<a class="btn btn-sm btn-primary" onclick="abonoCredito(${row.id})" href="#">Abonar</a>` :
                                '';

                            return `<a class="btn btn-sm btn-danger" href="${abonosUrl.replace(':creditocliente', row.id)}">Abonos</a>` +
                                abonarButton;
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


            btnProcesar.addEventListener('click', function() {
                const idCredito = document.getElementById('id_credito').value;
                const monto = document.getElementById('monto').value;
                const forma = document.getElementById('forma').value;
                const fotoInput = document.querySelector('input[name="foto"]'); // Capturar input de imagen
                const foto = fotoInput.files[0]; // Obtener archivo seleccionado

                if (!idCredito || !monto || !forma) {
                    Swal.fire({
                        position: "top-end",
                        icon: 'warning',
                        title: 'Todos los campos son requeridos',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                    return; // Detener ejecución si faltan campos
                }

                Swal.fire({
                    title: "Abonar",
                    text: "¿Estás seguro de abonar?",
                    icon: "info",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, abonar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('id_credito', idCredito);
                        formData.append('monto', monto);
                        formData.append('forma', forma);
                        if (foto) formData.append('foto', foto); // Solo si se sube una imagen

                        fetch("{{ route('admin.creditoclientes.registrarAbono') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Incluir CSRF token
                                },
                                body: formData, // Enviar como FormData para admitir archivos
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    position: "top-end",
                                    icon: data.icon,
                                    title: data.title,
                                    showConfirmButton: false,
                                    timer: 1500,
                                    toast: true
                                });

                                if (data.icon === 'success') {
                                    abonos(); // Limpiar los campos después del abono
                                    setTimeout(() => {
                                        window.open(
                                            `${ticketUrl.replace(':creditocliente', data.ticket)}`,
                                            '_blank');
                                        window.location.reload();
                                    }, 500);
                                }
                            })
                            .catch(error => {
                                console.error('Error al abonar crédito:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Hubo un problema al procesar el abono.'
                                });
                            });
                    }
                });
            });

        });

        function abonoCredito(creditoId) {
            fetch(detalleUrl.replace(':creditocliente', creditoId), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id_credito').value = creditoId;
                    document.getElementById('cliente').value = data.cliente.nombre;
                    document.getElementById('telefono').value = data.cliente.telefono;
                    document.getElementById('total').value = data.credito.total;
                    document.getElementById('abonado').value = data.credito.abonado;
                    document.getElementById('restante').value = data.credito.restante;
                    document.getElementById('monto').value = "";

                    // Limpiar el campo de foto antes de abrir el modal
                    document.getElementById('foto').value = '';

                    $('#modalAbono').modal('show');
                })
                .catch(error => {
                    console.error('Error al abonar crédito:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema'
                    });
                });
        }

        function abonos() {
            document.getElementById('monto').textContent = '';
            document.getElementById('foto').textContent = '';
        }
    </script>
    <script>
        function actualizarCredito(creditoId) {
            Livewire.emit('actualizarCredito', creditoId);
        }
    </script>
@stop
