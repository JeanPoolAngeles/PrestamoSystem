@extends('template')

@section('title', 'EDITAR-PRESTAMO')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h2 class="fw-bold">Préstamo: #{{ $prestamo->id }}</h2>
        </div>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            @if ($errors->any())
                @foreach ($errors->all() as $item)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ $item }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif
            <div class="card card-default">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.prestamos.update', ['id' => $prestamo->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card shadow-sm">
                            <div class="card-header bg-secondary text-white text-center">
                                <div class="container text-center">
                                    <h5 class="fw-bold">Actualizar Documentos</h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="letra" class="form-label">Subir Letra:</label>
                                        @if ($prestamo->letra)
                                            <img src="{{ asset('storage/' . $prestamo->letra) }}" alt="Letra"
                                                class="img-thumbnail mb-2" height="auto" width="auto">
                                        @endif
                                        <input id="letra" name="letra" class="form-control" type="file"
                                            accept="image/*">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="garantia" class="form-label">Subir Garantía:</label>
                                        @if ($prestamo->garantia)
                                            <img src="{{ asset('storage/' . $prestamo->garantia) }}" alt="Garantía"
                                                class="img-thumbnail mb-2" height="auto" width="auto">
                                        @endif
                                        <input id="garantia" name="garantia" class="form-control" type="file"
                                            accept="image/*">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="foto" class="form-label">Subir Foto:</label>
                                        @if ($prestamo->foto)
                                            <img src="{{ asset('storage/' . $prestamo->foto) }}" alt="Foto"
                                                class="img-thumbnail mb-2" height="auto" width="auto">
                                        @endif
                                        <input id="foto" name="foto" class="form-control" type="file"
                                            accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">Actualizar Documentos</button>
                            <a href="{{ route('admin.prestamos.show') }}" class="btn btn-secondary">Cancelar</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
