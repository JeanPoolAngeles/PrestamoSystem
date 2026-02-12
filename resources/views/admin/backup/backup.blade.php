@extends('template')

@section('title', 'VISTA-BACKUP')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold">COPIA DE SEGURIDAD Y RESTAURACIÓN</h1>
        </div>
    </div>
    <div class="container mt-4 my-4 mb-4">
        <div class="card mt-4">
            <div class="card-body">
                <!-- Mensaje dinámico sobre el último backup -->
                @if (Cache::has('last_backup_time'))
                    <p class="text-center">Última copia de seguridad realizada:
                        <strong>{{ \Carbon\Carbon::parse(Cache::get('last_backup_time'))->format('d-m-Y H:i:s') }}</strong>
                    </p>
                    @php
                        $remainingTime = \Carbon\Carbon::parse(Cache::get('last_backup_time'))
                            ->addHours(2)
                            ->diffForHumans(now(), ['parts' => 2]);
                    @endphp
                    <p class="text-center text-danger">
                        Debes esperar {{ $remainingTime }} para realizar otra copia de seguridad.
                    </p>
                @endif

                <!-- Botón para realizar la copia de seguridad -->
                <form action="{{ route('admin.backup.create') }}" method="POST">
                    @csrf
                    <button type="submit" id="copia_id"
                        class="btn btn-primary {{ Cache::has('last_backup_time') && \Carbon\Carbon::parse(Cache::get('last_backup_time'))->diffInHours(now()) < 2 ? 'disabled' : '' }}">
                        Realizar copia de seguridad
                    </button>
                </form>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-body">
                <form action="{{ route('admin.backup.restore') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="sqlFile">Selecciona tu archivo .sql para restaurar la base de datos:</label><br>
                    <input type="file" name="sqlFile" id="sqlFile" accept=".sql" required>
                    <button id="subir_id" type="submit" class="btn btn-danger mt-2">Restaurar</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
            });
        </script>
    @endif
@endsection
