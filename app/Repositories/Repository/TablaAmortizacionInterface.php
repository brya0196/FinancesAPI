<?php

namespace App\Repositories\Repository;

interface TablaAmortizacionInterface
{
    public function Amortizacion($montoTotal, $interes, $plazo, $fecha);
}