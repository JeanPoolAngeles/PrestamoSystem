<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReporteExport implements FromView
{
    protected $masVendidos;

    public function __construct($masVendidos)
    {
        $this->masVendidos = $masVendidos;
    }

    public function view(): View
    {
        return view('panel.reporte', [
            'masVendidos' => $this->masVendidos,
        ]);
    }
}
