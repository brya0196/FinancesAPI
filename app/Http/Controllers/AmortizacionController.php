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

    public function index()
    {
        // return $this->tablaAmortizacion->Amortizacion(475515.00, 15.95, 60);

        return $this->tablaAmortizacion->Amortizacion(1002500.00, 9.30, 84);
    }
}
