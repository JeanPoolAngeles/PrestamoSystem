<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Prestamo;
use App\Models\Cliente;
use App\Models\Creditocliente;

class CalculoPrestamo extends Component
{
    public $id_cliente;
    public $id_credito;
    public $id_prestamo;
    public $prestamo;
    public $credito;
    public $metodo_pago;
    public $cantidad_meses;
    public $monto;
    public $interes;
    public $total_pagar;
    public $pagos;

    // Escuchar evento para actualizar el crédito
    protected $listeners = ['actualizarCredito' => 'setCredito'];

    public function mount($clienteId, $creditoId = null)
    {
        $this->id_cliente = $clienteId;

        if ($creditoId) {
            $this->setCredito($creditoId);
        } else {
            $this->credito = Creditocliente::where('id_cliente', $clienteId)->first();
            if ($this->credito) {
                $this->setCredito($this->credito->id);
            }
        }
    }

    public function setCredito($creditoId)
    {
        $this->id_credito = $creditoId;
        $this->credito = Creditocliente::with('prestamo')->find($this->id_credito);

        if ($this->credito) {
            $this->prestamo = $this->credito->prestamo;
            $this->id_prestamo = $this->prestamo->id;
            $this->metodo_pago = $this->prestamo->metodo_pago;
            $this->cantidad_meses = $this->prestamo->cantidad;
            $this->monto = $this->prestamo->monto;
            $this->calcularPagos();
        }

        $this->dispatch('refreshComponent');

    }

    public function calcularPagos()
    {
        if (!$this->prestamo) return;

        $interes_mensual = 0.10; // 10% de interés
        $this->interes = $this->monto * $interes_mensual * $this->cantidad_meses;
        $this->total_pagar = $this->monto + $this->interes;

        switch ($this->metodo_pago) {
            case 'dia':
                $dias_por_mes = 26;
                $pagos_totales = $dias_por_mes * $this->cantidad_meses;
                break;
            case 'semana':
                $semanas_por_mes = 4;
                $pagos_totales = $semanas_por_mes * $this->cantidad_meses;
                break;
            case 'quincena':
                $quincenas_por_mes = 2;
                $pagos_totales = $quincenas_por_mes * $this->cantidad_meses;
                break;
            case 'mes':
                $pagos_totales = $this->cantidad_meses;
                break;
            default:
                $pagos_totales = 0;
        }

        $this->pagos = [
            'cantidad_pagos' => $pagos_totales,
            'monto_por_pago' => $pagos_totales > 0 ? round($this->total_pagar / $pagos_totales, 2) : 0
        ];
    }

    public function render()
    {
        return view('livewire.calculo-prestamo', [
            'clientes' => Cliente::all(),
            'creditos' => Creditocliente::where('id_cliente', $this->id_cliente)->get(),
            'prestamos' => Prestamo::where('id_cliente', $this->id_cliente)->get()
        ]);
    }
}
