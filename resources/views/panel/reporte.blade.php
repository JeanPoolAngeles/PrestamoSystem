<table>
    <tr>
        <td colspan="2">
            <img src="{{ public_path('/img/logo.png') }}" alt="Logo" height="90" />
        </td>
        <td colspan="7">
            <p>REPORTE DE VENTAS PRODUCTOS/PLATOS</p>
        </td>
    </tr>
</table>

<table style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr style="background-color: #CCCCCC; font-weight: bold;">
            <th style="border: 1px solid #000; width: 200px;">Nombre</th>
            <th style="border: 1px solid #000; width: 100px;">Total Vendido</th>

        </tr>
    </thead>
    <tbody>
        @foreach($masVendidos as $item)
            <tr>
                <td style="border: 1px solid #000; text-align: center">{{ $item->nombre }}</td>
                <td style="border: 1px solid #000; text-align: center">{{ $item->total_vendido }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

