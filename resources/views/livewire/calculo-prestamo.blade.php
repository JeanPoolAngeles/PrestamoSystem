<div>
    @if ($prestamo)
        <div class="card">
            <div class="card-header bg-primary">
                <div class="card-body text-center text-white">
                    <h3>Detalle del Préstamo</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-success"><strong>Monto Prestado:</strong>
                            {{ number_format($prestamo->monto, 2) }}
                        </li>
                        <li class="list-group-item list-group-item-secondary"><strong>Interés Total:</strong>
                            {{ number_format($interes, 2) }}</li>
                        <li class="list-group-item list-group-item-primary"><strong>Método de Pago:</strong>
                            {{ ucfirst($prestamo->metodo_pago) }}
                        </li>
                        <li class="list-group-item list-group-item-warning"><strong>Plazo del Préstamo:</strong>
                            {{ $cantidad_meses }} Meses
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-warning"><strong>Total a Pagar:</strong>
                            {{ number_format($total_pagar, 2) }}</li>
                        <li class="list-group-item list-group-item-secondary"><strong>Cantidad de Pagos:</strong>
                            {{ $pagos['cantidad_pagos'] }} - {{ ucfirst($prestamo->metodo_pago) }}</li>
                        <li class="list-group-item list-group-item-success"><strong>Monto por Pago:</strong>
                            {{ number_format($pagos['monto_por_pago'], 2) }}</li>
                    </ul>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">
            <strong>No se encontró préstamo para este cliente.</strong>
        </div>
    @endif
</div>
