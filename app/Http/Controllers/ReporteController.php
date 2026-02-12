<?php

namespace App\Http\Controllers;

use App\Exports\CajasExport;
use App\Exports\ClientsExport;
use App\Exports\PlatosExport;
use App\Exports\EmpleadosExport;
use App\Exports\GastosExport;
use App\Exports\ProductsExport;
use App\Exports\ProveedorsExport;
use App\Exports\ReporteExport;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Empleado;
use App\Models\Gasto;
use App\Models\Plato;
use App\Models\Producto;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Milon\Barcode\Facades\DNS1DFacade;

class ReporteController extends Controller
{
    public function excelCliente()
    {
        return Excel::download(new ClientsExport, 'clientes.xlsx');
    }

    public function pdfCliente()
    {
        $clientes = Cliente::get();
        // Generar el contenido del ticket en HTML
        $html = View::make('admin.cliente.reporte', compact('clientes'))->render();

        // Generar el PDF utilizando laravel-dompdf con orientación horizontal
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false);

        return $pdf->stream('reporte.pdf');
    }

    public function excelDashboard(Request $request)
    {
        // Llamamos a la lógica que ya tienes en tu homeController
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));
        $dia = $request->input('dia', null); // Día específico
        $diaSemana = $request->input('dia_semana', null); // Día de la semana (lunes, martes, etc.)

        $queryProductos = DB::table('detalleventa')
            ->join('productos', 'detalleventa.id_producto', '=', 'productos.id')
            ->select(DB::raw('productos.producto as nombre'), DB::raw('SUM(detalleventa.cantidad) as total_vendido'))
            ->whereYear('detalleventa.created_at', $anio);

        if ($mes && !$dia) {
            $queryProductos->whereMonth('detalleventa.created_at', $mes);
        }

        if ($dia) {
            $queryProductos->whereDate('detalleventa.created_at', $dia);
        }

        if ($diaSemana) {
            $queryProductos->whereRaw('DAYOFWEEK(detalleventa.created_at) = ?', [$diaSemana]);
        }

        $productos = $queryProductos->groupBy('productos.producto')
            ->orderBy('total_vendido', 'DESC')
            ->get();

        $masVendidos = $productos->sortByDesc('total_vendido')->take(10);

        return Excel::download(new ReporteExport($masVendidos), 'reporte.xlsx');
    }

    public function pdfDashboard(Request $request)
    {
        $mes = $request->input('mes', date('m'));
        $anio = $request->input('anio', date('Y'));
        $dia = $request->input('dia', null);
        $diaSemana = $request->input('dia_semana', null);

        $queryProductos = DB::table('detalleventa')
            ->join('productos', 'detalleventa.id_producto', '=', 'productos.id')
            ->select(DB::raw('productos.producto as nombre'), DB::raw('SUM(detalleventa.cantidad) as total_vendido'))
            ->whereYear('detalleventa.created_at', $anio);

        if ($mes && !$dia) {
            $queryProductos->whereMonth('detalleventa.created_at', $mes);
        }

        if ($dia) {
            $queryProductos->whereDate('detalleventa.created_at', $dia);
        }

        if ($diaSemana) {
            $queryProductos->whereRaw('DAYOFWEEK(detalleventa.created_at) = ?', [$diaSemana]);
        }

        $productos = $queryProductos->groupBy('productos.producto')
            ->orderBy('total_vendido', 'DESC')
            ->get();

        $masVendidos = $productos->sortByDesc('total_vendido')->take(10);

        // Generar el contenido del ticket en HTML
        $html = View::make('panel.reporte', compact('masVendidos', 'mes', 'anio'))->render();

        // Generar el PDF utilizando laravel-dompdf con orientación horizontal
        $pdf = PDF::loadHTML($html)->setPaper('a4', 'landscape')->setWarnings(false);

        return $pdf->stream('reporte.pdf');
    }
}
