<?php

namespace App\Repositories\Logic;

use App\Repositories\Repository\TablaAmortizacionInterface;

class TablaAmortizacion implements TablaAmortizacionInterface
{

    private $montoTotal;
    private $interes;
    private $plazo;

    public function __construct()
    {
    }

    public function Amortizacion($montoTotal, $interes, $plazo)
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

        // 
        for ($i=1; $i <= $this->plazo; $i++) { 

            $capital = $this->CalcularCapital($total);
            $interes = $this->CalcularInteres($total, $capital);

            array_push($tabla['tablaAmortizacion'], array(
                'id' => $i,
                'saldo' => $this->montoTotal,
                'interes' => $interes, 
                'capital' => $capital,
                'totalMes' => $total,
                'fecha' => date('d/m/Y')
            ));

            $this->montoTotal -= $capital;
            // echo $this->montoTotal;
            // echo '</br>';
            $this->montoTotal = round($this->montoTotal, 2);

            $tabla['totalCapital'] += $capital;
            $tabla['totalCapital'] = round($tabla['totalCapital'], 2);

            $tabla['totalInteres'] += $interes;
            $tabla['totalInteres'] = round($tabla['totalInteres'], 2);

            $tabla['totalPagado'] += $total;
            $tabla['totalPagado'] = round($tabla['totalPagado'], 2);
        }

        return $tabla;
    }

    private function CalcularCapital($total)
    {
        $capital = $total - ($this->montoTotal * $this->InteresCalculado()); 
        return round($capital, 2);
    }

    private function CalcularInteres($total,$capital)
    {   
        $interes = $total - $capital;
        return round($interes, 2);
    }

    private function TotalMes()
    {
        $calculo = pow((1 + $this->InteresCalculado()), $this->plazo);
        $resultado = ($calculo - 1) / ($this->InteresCalculado() * $calculo);
        $total = $this->montoTotal / $resultado;
        return round($total, 2);
    }

    private function InteresCalculado()
    {
        return ($this->interes / 100) / 12;
    }
}