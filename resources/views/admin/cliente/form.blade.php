<div class="box box-info padding-1">
    <div class="box-body row">
        <div class="form-group col-md-2">
            {{ Form::label('dni', 'DNI') }}
            {{ Form::text('dni', $cliente->dni ?? '', [
                'class' => 'form-control' . ($errors->has('dni') ? ' is-invalid' : ''),
                'placeholder' => 'DNI',
                'id' => 'dni',
                'readonly' => 'readonly'
            ]) }}
            {!! $errors->first('dni', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-8">
            {{ Form::label('nombre', 'Nombre') }}
            {{ Form::text('nombre', $cliente->nombre ?? '', [
                'class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''),
                'placeholder' => 'Nombre',
                'id' => 'nombre',
                'readonly' => 'readonly'
            ]) }}
            {!! $errors->first('nombre', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('telefono', 'Teléfono') }}
            {{ Form::text('telefono', $cliente->telefono ?? '', [
                'class' => 'form-control' . ($errors->has('telefono') ? ' is-invalid' : ''),
                'placeholder' => 'Teléfono',
                'id' => 'telefono'
            ]) }}
            {!! $errors->first('telefono', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('correo', 'Correo Electrónico') }}
            {{ Form::email('correo', $cliente->correo ?? '', [
                'class' => 'form-control' . ($errors->has('correo') ? ' is-invalid' : ''),
                'placeholder' => 'Correo Electrónico',
                'id' => 'correo'
            ]) }}
            {!! $errors->first('correo', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-5">
            {{ Form::label('direccion', 'Dirección') }}
            {{ Form::textarea('direccion', $cliente->direccion ?? '', [
                'class' => 'form-control' . ($errors->has('direccion') ? ' is-invalid' : ''),
                'placeholder' => 'Dirección',
                'rows' => 3,
                'id' => 'direccion'
            ]) }}
            {!! $errors->first('direccion', '<div class="invalid-feedback">:message</div>') !!}
        </div>
        <div class="form-group col-md-4">
            {{ Form::label('fecha_nacimiento', 'Fecha de Nacimiento') }}
            {{ Form::date('fecha_nacimiento', $cliente->fecha_nacimiento ?? '', [
                'class' => 'form-control' . ($errors->has('fecha_nacimiento') ? ' is-invalid' : ''),
                'id' => 'fecha_nacimiento'
            ]) }}
            {!! $errors->first('fecha_nacimiento', '<div class="invalid-feedback">:message</div>') !!}
        </div>
    </div>
    <div class="box-footer mt20 text-right">
        <a href="{{ route('admin.clientes.index') }}" class="btn btn-danger">{{ __('Cancelar') }}</a>
        <button type="submit" class="btn btn-primary">{{ __('Guardar') }}</button>
    </div>
</div>
