<?php

namespace App\Http\Controllers;

use App\Repositories\Logic\TablaAmortizacion;
use Illuminate\Http\Request;

class AmortizacionController extends Controller
{
    protected $tablaAmortizacion;

    public function __construct()
    {
        $this->tablaAmortizacion = new TablaAmortizacion();
    }

    public function index(Request $request)
    {
        $monto = (float)$request->monto;
        $tasa = (float)$request->tasa;
        $plazo = (float)$request->plazo;
        $fecha = $request->fecha;

        return $this->tablaAmortizacion->Amortizacion($monto, $tasa, $plazo, $fecha);
    }
}
