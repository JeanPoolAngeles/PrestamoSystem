<?php

namespace App\Exports;

use App\Models\Compania;
use App\Models\Prestamo;
use App\Models\Venta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PrestamoExport implements FromView
{

    public function view(): View
    {
        $query = Prestamo::with(['user', 'cliente', 'formapago']);

        return view('admin.prestamos.reporte', [
            'ventas' => $query->get()
        ]);
    }
}
