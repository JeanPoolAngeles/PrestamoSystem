<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Prestamo;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // Datos generales para el dashboard
        $totales = [
            'clients' => Cliente::count(),
        ];

        $montosTotal = [
            'prestamos' => Prestamo::where('estado', [1, 2])->sum('monto'),
        ];

        // Prestamos por mes
        $prestamos = $this->getPrestamosPorMes();

        // Prestamos por semana
        $prestamosPorSemana = $this->getPrestamosPorSemana();

        // Renderiza la vista
        return view('panel.dashboard', compact(
            'prestamos',
            'prestamosPorSemana',
            'totales',
            'montosTotal'
        ));
    }

    private function getPrestamosPorMes()
    {
        $nombresMeses = $this->getMesesEnEspanol();

        $prestamosPorMes = Prestamo::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(monto) as monto')
            ->where('estado', [1, 2])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $prestamos = [];
        foreach ($prestamosPorMes as $prestamo) {
            $year = $prestamo->year;
            $month = $nombresMeses[$prestamo->month];
            $prestamos[$year][$month] = $prestamo->monto;
        }

        return $prestamos;
    }

    private function getPrestamosPorSemana()
    {
        $diasEnEspanol = $this->getDiasEnEspanol();
        $inicioSemana = Carbon::now()->startOfWeek()->toDateString();
        $finSemana = Carbon::now()->endOfWeek()->toDateString();

        return Prestamo::selectRaw('DAYNAME(created_at) as dia, SUM(monto) as monto')
            ->where('estado', [1, 2])
            ->whereBetween('created_at', [$inicioSemana, $finSemana])
            ->groupBy('dia')
            ->get()
            ->map(function ($prestamo) use ($diasEnEspanol) {
                return [
                    'diaEnEspanol' => $diasEnEspanol[ucfirst(strtolower($prestamo->dia))],
                    'monto' => $prestamo->monto,
                ];
            });
    }

    private function getMesesEnEspanol()
    {
        return [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
    }

    private function getDiasEnEspanol()
    {
        return [
            'Sunday' => 'Domingo',
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
        ];
    }
}
