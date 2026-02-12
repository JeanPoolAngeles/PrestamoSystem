@extends('template')

@section('title', 'ADMIN-EMPRESA')

@section('content')
    <div class="card mt-1 text-center bg-primary text-white">
        <div class="card-body">
            <h1 class="fw-bold">ADMINISTRACIÓN DE LA EMPRESA</h1>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header bg-success text-center text-white">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div class="card-body">
                                    <span id="card_title">
                                        {{ __('EMPRESA ACTUAL') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($message = Session::get('success'))
                                <div class="alert alert-success" role="alert">
                                    {{ $message }}
                                </div>
                            @endif
                            <form method="POST" action="{{ route('admin.compania.update', $compania->id) }}"
                                enctype="multipart/form-data">
                                {{ method_field('PUT') }}
                                @csrf

                                <div class="box box-info padding-1">
                                    <div class="box-body row">
                                        <div class="form-group col-md-4">
                                            {{ Form::label('nombre') }}
                                            {{ Form::text('nombre', $compania->nombre, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''), 'placeholder' => 'Nombre']) }}
                                            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-4">
                                            {{ Form::label('telefono') }}
                                            {{ Form::text('telefono', $compania->telefono, ['class' => 'form-control' . ($errors->has('telefono') ? ' is-invalid' : ''), 'placeholder' => 'Teléfono']) }}
                                            {!! $errors->first('telefono', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-4">
                                            {{ Form::label('correo') }}
                                            {{ Form::text('correo', $compania->correo, ['class' => 'form-control' . ($errors->has('correo') ? ' is-invalid' : ''), 'placeholder' => 'Correo']) }}
                                            {!! $errors->first('correo', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-5">
                                            {{ Form::label('direccion') }}
                                            {{ Form::text('direccion', $compania->direccion, ['class' => 'form-control' . ($errors->has('direccion') ? ' is-invalid' : ''), 'placeholder' => 'Dirección']) }}
                                            {!! $errors->first('direccion', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-5">
                                            {{ Form::label('RUC') }}
                                            {{ Form::text('RUC', $compania->RUC, ['class' => 'form-control' . ($errors->has('RUC') ? ' is-invalid' : ''), 'placeholder' => 'Ingrese RUC']) }}
                                            {!! $errors->first('RUC', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            <hr>
                                            {{ Form::label('foto', 'Foto de tu Logo') }}
                                            {{ Form::file('foto', ['class' => 'form-control' . ($errors->has('foto') ? ' is-invalid' : ''), 'placeholder' => 'Foto de su Logo']) }}
                                            {!! $errors->first('foto', '<div class="invalid-feedback">:message</div>') !!}
                                            @if ($compania->foto)
                                                <div class="card">
                                                    <div class="card-body text-center">
                                                        <img src="{{ asset('storage/' . $compania->foto) }}"
                                                            alt="Imagen actual"
                                                            style="max-width: 300px; max-height: 300px;">
                                                    </div>
                                                </div>
                                            @else
                                                <p>Seleccione una imagen</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="box-footer mt20 text-right">
                                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
