@extends('template')

@section('title', 'VISTA-ABONOS')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold">ADMINISTRACIÓN DE LOS ABONOS DE CLIENTES</h1>
        </div>
    </div>
    <div class="row" style="padding: 30px">
        <div class="card col-md-6 text-center">
            <div class="row">
                <div class="col-md-12 mt-2 mb-2 my-2">
                    <div class="btn-group" role="group" aria-label="Button group">
                        <a href="{{ route('admin.creditoclientes.index', $cliente->id) }}" class="btn btn-info btn-sm"
                            data-placement="left"><i class="fas fa-dollar-sign"></i>
                            {{ __('Creditos') }}
                        </a>
                        <a href="#" class="btn btn-primary btn-sm" onclick="abonoCredito()">
                            <i class="fas fa-plus"></i>
                            {{ __('Abonar') }}
                            <i class="fas fa-dollar-sign"></i>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 mt-2 mb-2 my-2 ms-2">
                    @livewire('calculo-prestamo', ['clienteId' => $cliente->id, 'creditoId' => $credito->id])
                </div>
            </div>
        </div>
        <div class="card col-md-6 text-center">
            <div class="card shadow-lg border-0">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-primary">
                        <p id="comentario-texto" class="mb-2">{{ $credito->comentario ?? 'Sin comentario' }}</p>
                    </li>
                    <li class="list-group-item list-group-item-secondary">
                        <textarea id="nuevoComentario" class="form-control" placeholder="Escribe un comentario...">{{ $credito->comentario }}</textarea>
                    </li>
                    <li class="list-group-item list-group-item-success"><button class="btn btn-success mt-2"
                            onclick="actualizarComentario({{ $credito->id }})">Actualizar</button></li>
                </ul>
            </div>
        </div>
        <div class="card col-md-12 text-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-primary">
                                Crédito N°: {{ $credito->id }}
                            </li>
                            <li class="list-group-item list-group-item-secondary">
                                Fecha: {{ $credito->created_at }}
                            </li>
                            <li class="list-group-item list-group-item-success">
                                Monto: {{ optional($credito->prestamo)->total_pagar ?? 'No disponible' }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            @if (optional($credito->prestamo)->cliente)
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-primary">
                                        Cliente:
                                        {{ optional(optional($credito->prestamo)->cliente)->nombre ?? 'Cliente no encontrado' }}
                                    </li>
                                    <li class="list-group-item list-group-item-secondary">
                                        Teléfono:
                                        {{ optional(optional($credito->prestamo)->cliente)->telefono ?? 'Teléfono no disponible' }}
                                    </li>
                                    <li class="list-group-item list-group-item-secondary">
                                        Dirección:
                                        {{ optional(optional($credito->prestamo)->cliente)->direccion ?? 'Direccion no disponible' }}
                                    </li>
                                </ul>
                            @else
                                <p class="text-danger">No hay información del cliente.</p>
                            @endif
                        </ul>
                    </div>
                </div>
                <hr class="mt-4 my-4 mb-4 ms-5 ml-5">
                @if ($abonos->isEmpty())
                    <p>No hay registros disponibles.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover display responsive nowrap" width="100%"
                            id="tblCreditos">
                            <thead class="thead">
                                <tr>
                                    <th>Id</th>
                                    <th>Monto</th>
                                    <th>Forma Pago</th>
                                    <th>Usuario</th>
                                    <th>Fecha/Hora</th>
                                    <th>Foto</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($abonos as $abono)
                                    <tr>
                                        <td>{{ $abono->id }}</td>
                                        <td>{{ $abono->monto }}</td>
                                        <td>{{ $abono->formapago->nombre }}</td>
                                        <td>{{ $abono->usuario->name }}</td>
                                        <td>{{ $abono->created_at }}</td>
                                        <td>
                                            @if ($abono->foto)
                                                <img src="{{ asset('storage/' . $abono->foto) }}" alt="Voucher"
                                                    style="max-width: 100px; max-height: 100px;">
                                            @else
                                                Sin Voucher de Pago
                                            @endif
                                        </td>
                                        <td><a href="{{ route('admin.creditoclientes.ticket', $abono->id) }}"
                                                target="_blank" class="btn btn-danger btn-sm">Ticket</a></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td>Total</td>
                                    <td><b>{{ number_format($abonado, 2) }}</b></td>
                                    <td colspan="5" class="text-center"><b>Restante:
                                            {{ number_format($credito->prestamo->total_pagar - $abonado, 2) }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer">
                {{ $abonos->links() }}
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
                        <input type="hidden" id="id_credito" value="{{ $credito->id }}">
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
                                <input id="telefono" class="form-control" type="text" placeholder="Telefono"
                                    required>
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

                            @if (!empty($credito->abonos->last()->foto))
                                <p>Seleccione una imagen</p>
                            @endif
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
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
@endsection

@section('js')
    <script>
        var ticketUrl = "{{ route('admin.creditoclientes.ticket', ['id' => ':creditocliente']) }}";
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
        const foto = document.querySelector('input[name="foto"]').files[0]; // Obtener el archivo

        document.addEventListener("DOMContentLoaded", function() {
            btnProcesar.addEventListener('click', function() {
                const idCredito = document.getElementById('id_credito').value;
                const monto = document.getElementById('monto').value;
                const forma = document.getElementById('forma').value;
                const fotoInput = document.querySelector('input[name="foto"]'); // Capturar input de imagen
                const foto = fotoInput.files[0]; // Obtener archivo seleccionado

                // Validar campos requeridos
                if (!idCredito || !monto || !forma) {
                    Swal.fire({
                        position: "top-end",
                        icon: 'warning',
                        title: 'Todos los campos son requeridos',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true
                    });
                    return;
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
                        const formData = new FormData(); // Crear un objeto FormData
                        formData.append('id_credito', idCredito);
                        formData.append('monto', monto);
                        formData.append('forma', forma);
                        if (foto) formData.append('foto', foto); // Agregar imagen si se seleccionó

                        fetch("{{ route('admin.creditoclientes.registrarAbono') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Incluir CSRF token
                                },
                                body: formData, // Enviar FormData en lugar de JSON
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
                                    abonos();
                                    setTimeout(() => {
                                        window.open(
                                            `${ticketUrl.replace(':creditocliente', data.ticket)}`,
                                            '_blank');
                                        window.location.reload();
                                    }, 500);
                                }
                            })
                            .catch(error => {
                                console.log('Error al abonar crédito:', error);
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

        function abonoCredito() {
            fetch(detalleUrl.replace(':creditocliente', {{ $credito->id }}), {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(
                            `HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    cliente.value = data.cliente.nombre;
                    telefono.value = data.cliente.telefono;
                    total.value = data.credito.total;
                    abonado.value = data.credito.abonado;
                    restante.value = data.credito.restante;
                    monto.value = "";
                    $('#modalAbono').modal('show');
                })
                .catch(error => {
                    // Manejar otros errores
                    console.log('Error al abonar crédito:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema'
                    });
                });

        }

        function actualizarComentario(creditoId) {
            if (!creditoId) {
                console.error('El ID del crédito es inválido.');
                Swal.fire('Error', 'No se pudo identificar el crédito para actualizar el comentario.', 'error');
                return;
            }

            const comentarioInput = document.getElementById('nuevoComentario');
            const comentarioTexto = document.getElementById('comentario-texto');

            if (!comentarioInput || !comentarioTexto) {
                console.error('No se encontró el campo de comentario.');
                Swal.fire('Error', 'No se pudo encontrar el campo de comentario.', 'error');
                return;
            }

            const comentario = comentarioInput.value.trim();
            if (!comentario) {
                Swal.fire('Error', 'El comentario no puede estar vacío.', 'error');
                return;
            }

            fetch(`/creditoclientes/comentario/${creditoId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        comentario
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Comentario Actualizado', data.message, 'success');
                        comentarioTexto.textContent = comentario; // Actualizar en tiempo real
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Hubo un problema al actualizar el comentario.', 'error');
                });
        }

        function abonos() {
            document.getElementById('monto').textContent = '';
            document.getElementById('foto').textContent = '';
        }
    </script>
@stop
