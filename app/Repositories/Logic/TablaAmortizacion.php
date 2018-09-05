<?php

namespace App\Repositories\Logic;

use App\Repositories\Repository\TablaAmortizacionInterface;

class TablaAmortizacion implements TablaAmortizacionInterface
{

    private $montoTotal;
    private $interes;
    private $plazo;
    private $fecha;

    public function __construct()
    {
    }

    public function Amortizacion($montoTotal, $interes, $plazo, $fecha)
    {   
        if (!is_numeric($montoTotal) && is_string($montoTotal))
            throw new Exception("The value must be a integer");

        if (!is_numeric($interes) && is_string($interes))
            throw new Exception("The value must be a integer");

        if (!is_numeric($plazo) && is_string($plazo))
            throw new Exception("The value must be a integer");
        
        $this->montoTotal = $montoTotal;
        $this->interes = $interes;
        $this->plazo = $plazo;
        $this->fecha = $fecha;
        $tabla = array();

        $tabla = $this->Calcular();

        return $tabla;
    }

    private function Calcular()
    {
        $tabla = array(
            'totalCapital' => 0,
            'totalInteres' => 0,
            'totalPagado' => 0,
            'tablaAmortizacion' => array()
        );
        $total = $this->TotalMes();

        for ($i=1; $i <= $this->plazo; $i++) { 

            $capital = $this->CalcularCapital($total);
            $interes = $this->CalcularInteres($total, $capital);
            
            $this->NuevaFecha();

            array_push($tabla['tablaAmortizacion'], array(
                'noCuota' => $i,
                'saldo' =>  round($this->montoTotal, 2, PHP_ROUND_HALF_DOWN),
                'interes' => round($interes, 2, PHP_ROUND_HALF_DOWN), 
                'capital' => round($capital, 2, PHP_ROUND_HALF_DOWN),
                'total' => round($total, 2, PHP_ROUND_HALF_DOWN),
                'fecha' => $this->fecha
            ));

            $this->montoTotal -= $capital;

            $sumaCapital = $tabla['totalCapital'] + $capital;
            $tabla['totalCapital'] = $sumaCapital;

            $sumaInteres = $tabla['totalInteres'] + $interes;
            $tabla['totalInteres'] = $sumaInteres;

            $sumaPagos = $tabla['totalPagado'] + $total;
            $tabla['totalPagado'] = $sumaPagos;
        }

        $tabla['totalCapital'] = number_format($tabla['totalCapital'], 2, '.', '');
        $tabla['totalInteres'] = number_format($tabla['totalInteres'], 2, '.', '');
        $tabla['totalPagado'] = number_format($tabla['totalPagado'], 2, '.', '');

        return $tabla;
    }

    private function CalcularCapital($total)
    {
        $capital = $total - ($this->montoTotal * $this->InteresCalculado()); 
        return $capital;
    }

    private function CalcularInteres($total,$capital)
    {   
        $interes = $total - $capital;
        return $interes;
    }

    private function TotalMes()
    {
        $calculo = pow((1 + $this->InteresCalculado()), $this->plazo);
        $resultado = ($calculo - 1) / ($this->InteresCalculado() * $calculo);
        $total = $this->montoTotal / $resultado;
        return $total;
    }

    private function InteresCalculado()
    {
        return ($this->interes / 100) / 12;
    }

    private function NuevaFecha()
    {
        $nuevaFecha = date_create($this->fecha);
        $nuevaFecha = date_modify($nuevaFecha, '+1 month');
        $this->fecha = date_format($nuevaFecha, 'd-m-Y');
    }
}