@extends('template')

@section('title', 'ADMIN-PRESTAMOS')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold ">Administración de Nuevos Préstamos</h1>
        </div>
    </div>
    <hr class="text-black">
    <div class="container">
        <div class="text-center mt-4 mb-4">
            <button id="btnProcesar" type="button" class="btn btn-lg btn-outline-primary">
                <i class="fas fa-file-invoice-dollar"></i> Generar Préstamo
            </button>
        </div>
        <hr class="text-black">
        <div class="row mt-4">
            <!-- Sección de datos del cliente -->
            <div class="col-lg-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="fw-bold">Buscar Cliente</h5>
                    </div>
                    <div class="card-body">
                        <label>Nombre Cliente </label> | <span id="errorBusqueda" class="text-danger"></span>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-search"></i></span>
                            <input id="buscarCliente" name="cliente" class="form-control" type="text"
                                placeholder="Nombre del cliente">
                            <input id="id_cliente" name="id_cliente" class="form-control" type="hidden">
                        </div>
                        <label>Teléfono</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-phone"></i></span>
                            <input id="tel_cliente" class="form-control" type="text" placeholder="Teléfono" disabled>
                        </div>

                        <label>Dirección</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-home"></i></span>
                            <input id="dir_cliente" class="form-control" type="text" placeholder="Dirección" disabled>
                        </div>
                    </div>
                </div>

                <!-- Subida de Archivos -->
                <div class="card shadow-sm">
                    <div class="card-header bg-secondary text-white text-center">
                        <h5 class="fw-bold">Documentos</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="letra" class="form-label">Subir Letra:</label>
                            <input id="letra" name="letra" class="form-control" type="file" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="garantia" class="form-label">Subir Garantía:</label>
                            <input id="garantia" name="garantia" class="form-control" type="file" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="foto" class="form-label">Subir Foto:</label>
                            <input id="foto" name="foto" class="form-control" type="file" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Sección de Datos del Préstamo -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h5 class="fw-bold">Detalles del Préstamo</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-4">
                            <div class="col-md-4">
                                <h5 class="text-primary fw-bold">Monto</h5>
                                <span id="monto_cli" class="fw-bold fs-5">0.00</span>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-danger fw-bold">Total a Pagar</h5>
                                <span id="total_pagar" class="fw-bold fs-5">0.00</span>
                            </div>
                            <div class="col-md-4">
                                <h5 class="text-success fw-bold">Interés</h5>
                                <span id="ganancia" class="fw-bold fs-5">0.00</span>
                            </div>
                        </div>
                        <hr class="mt-4 my-4 mb-4">
                        <div class="mb-3">
                            <label for="monto" class="form-label">Monto del Préstamo <span
                                    class="text-danger">*</span></label>
                            <input id="monto" name="monto" class="form-control" type="number"
                                placeholder="Ingrese monto" min="100" oninput="calcularTasas()" required>
                        </div>

                        <div class="mb-3">
                            <label for="interes" class="form-label">Tasa de Interés (%) <span
                                    class="text-danger">*</span></label>
                            <input id="interes" name="interes" class="form-control" type="number" step="0.01"
                                value="0.10" oninput="calcularTasas()" required>
                        </div>

                        <div class="mb-3">
                            <label for="metodo" class="form-label">Método <span class="text-danger">*</span></label>
                            <select id="metodo" class="form-control" required>
                                <option value="">Seleccione Método</option>
                                <option value="Credito">Crédito</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="metodo_pago" class="form-label">Método de Pago <span
                                    class="text-danger">*</span></label>
                            <select id="metodo_pago" class="form-control" required>
                                <option value="">Seleccione Método</option>
                                <option value="dia">Por Día</option>
                                <option value="semana">Por Semana</option>
                                <option value="quincena">Por Quincena</option>
                                <option value="mes">Por Mes</option>
                            </select>
                        </div>

                        <div class="form-group" id="cantidadContainer">
                            <label for="cantidad">Cantidad (meses) <span class="text-danger">*</span></label>
                            <input id="cantidad" name="cantidad" class="form-control" type="number" min="1"
                                oninput="calcularTasas()" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <style>
        /* Ajustes para botones y tarjetas */
        @media (max-width: 768px) {
            .fixed-button {
                width: 90%;
            }

            .btn {
                font-size: 0.9rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
    <style>
        /* Mejoras de estilos */
        .input-group-text {
            border-radius: 10px 0 0 10px;
        }

        .form-control {
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.3);
        }

        .card {
            border-radius: 15px;
        }

        .btn-outline-primary {
            border-radius: 10px;
            font-size: 18px;
            transition: all 0.3s ease-in-out;
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
            transform: scale(1.05);
        }
    </style>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>

    <script>
        const prestamoUrl = "{{ route('admin.prestamos.index') }}";
        const limiteUrl = "{{ route('admin.creditoclientes.limitecliente', ['id' => ':creditocliente']) }}";

        document.addEventListener('DOMContentLoaded', function() {
            const btnProcesar = document.querySelector('#btnProcesar');

            $("#buscarCliente").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('admin.prestamos.cliente') }}",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            if (data.length === 0) {
                                errorBusqueda.textContent = "No se encontraron resultados.";
                            } else {
                                errorBusqueda.textContent = '';
                            }
                            response(data);
                        }
                    });
                },
                minLength: 1, // Número mínimo de caracteres para mostrar sugerencias
                select: function(event, ui) {
                    id_cliente.value = ui.item.id;
                    tel_cliente.value = ui.item.telefono;
                    dir_cliente.value = ui.item.direccion;
                }
            });

            document.getElementById('metodo_pago').addEventListener('change', function() {
                const metodo = this.value;
                const cantidadContainer = document.getElementById('cantidadContainer');
                cantidadContainer.style.display = metodo ? 'block' : 'none';
            });

            btnProcesar.addEventListener('click', function() {
                const idCliente = document.getElementById('id_cliente').value;
                const monto = document.getElementById('monto').value;
                const ganancia = document.getElementById('ganancia').value;
                const metodo = document.getElementById('metodo').value;
                const interes = document.getElementById('interes').value;
                const metodoPago = document.getElementById('metodo_pago').value;
                const cantidad = document.getElementById('cantidad').value;
                const letra = document.getElementById('letra').files[0];
                const garantia = document.getElementById('garantia').files[0];
                const foto = document.getElementById('foto').files[0];
                const totalPagar = parseFloat(monto) + (parseFloat(monto) * parseFloat(interes));

                // Validar campos requeridos
                if (!idCliente || !monto || !metodo || !metodoPago || !cantidad) {
                    mostrarAlerta('Todos los campos con * son obligatorios.', 'warning');
                    return;
                }

                const formData = new FormData();
                formData.append('id_cliente', idCliente);
                formData.append('monto', monto);
                formData.append('ganancia', ganancia);
                formData.append('metodo', metodo);
                formData.append('interes', interes);
                formData.append('total_pagar', totalPagar.toFixed(2));
                formData.append('metodo_pago', metodoPago);
                formData.append('cantidad', cantidad);
                if (letra) formData.append('letra', letra);
                if (garantia) formData.append('garantia', garantia);
                if (foto) formData.append('foto', foto);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¿Deseas procesar este préstamo?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, procesar!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(prestamoUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                },
                                body: formData,
                            })
                            .then((response) => response.json())
                            .then((data) => {
                                if (data.success) {
                                    mostrarAlerta(data.message, 'success');
                                    setTimeout(() => {
                                        window.location.href =
                                            "{{ route('admin.prestamos.show') }}";
                                    }, 500);
                                } else {
                                    mostrarAlerta(data.message ||
                                        'Hubo un error al procesar el préstamo.', 'error');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                mostrarAlerta('Ocurrió un error inesperado.', 'error');
                            });
                    }
                });
            });
        });

        function calcularTasas() {
            const montoInput = document.getElementById('monto');
            const interesInput = document.getElementById('interes');
            const cantidadInput = document.getElementById('cantidad');

            // Recuperar valores
            const monto = parseFloat(montoInput.value) || 0; // Monto del préstamo
            const interes = parseFloat(interesInput.value) || 0; // Interés porcentual
            const cantidad = parseInt(cantidadInput.value) || 0; // Cantidad de períodos (meses)

            // Validar entrada
            if (monto <= 0 || interes <= 0 || cantidad <= 0) {
                document.getElementById('total_pagar').textContent = '0.00';
                document.getElementById('ganancia').textContent = '0.00';
                return;
            }

            // Calcular interés total (monto * (interés / 100))
            const interesTotal = monto * interes;

            // Calcular ganancia (interés total * cantidad de períodos)
            const ganancia = interesTotal * cantidad;

            // Calcular total a pagar
            const totalPagar = monto + ganancia;

            // Actualizar valores en el formulario
            document.getElementById('monto_cli').textContent = monto.toFixed(2);
            document.getElementById('total_pagar').textContent = totalPagar.toFixed(2);
            document.getElementById('ganancia').textContent = ganancia.toFixed(2);
        }

        function mostrarAlerta(texto, icono) {
            Swal.fire({
                showConfirmButton: false,
                title: "Respuesta",
                text: texto,
                icon: icono,
                toast: true,
                timer: 1500,
                position: "top-end",
            });
        }
    </script>
@stop
