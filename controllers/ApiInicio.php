<?php

namespace Controllers;

use Model\Caja;
use Model\Cuota;
use Model\Venta;
use Model\Cliente;
use Model\Payment;
use Model\Producto;



class ApiInicio
{
    public static function index()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        $ventas = Venta::all();
        $total_factura = 0; //total de la venta sin importar si es fiado o no (ventas)
        $total_recaudos = 0; // lo que se ha recaudado como tal  (ingresos)
        $costo = 0; //costos de todas las ventas 



        foreach ($ventas as $venta) {
            $total_factura = $total_factura + $venta->total_factura;
            $total_recaudos = $total_recaudos + $venta->recaudo;
            $costo = $costo + $venta->costo;
        }


        $ganancia_no_realizada = $total_factura - $costo; //ganancias netas sin importar si ´pagaron o no
        $ganancia_realizada = $total_recaudos- $costo; //ganancias real 

        $inventario = Producto::total('stock*precio_compra');

        $numero_ventas = Venta::contar('pagado', 1);

        $numero_fiados = Venta::contar('pagado', 0);

        $numero_pagos = Payment::contar();
        $numero_cajas = Caja::contar();
        $numero_productos = Producto::contar();
        $numero_clientes  = Cliente::contar();
        $valor_formateado = '$' . number_format(abs($ganancia_realizada));
        $ganancia_realizada = $ganancia_realizada < 0 ? '-' . $valor_formateado : $valor_formateado;


        $informacion = [
            'total_ventas' => '$' . number_format($total_factura),
            'total_recaudos' => '$' . number_format($total_recaudos),
            'costos' => '$' . number_format($costo),
            'ganancia_no_realizada' =>  '$' . number_format($ganancia_no_realizada),
            'ganancia_realizada' => $ganancia_realizada ,
            'inventario' => number_format($inventario['total']),
            'numero_ventas' => $numero_ventas['total'],
            'numero_fiados' => $numero_fiados['total'],
            'numero_pagos' => $numero_pagos['total'],
            'numero_cajas' => $numero_cajas['total'],
            'numero_productos' => $numero_productos['total'],
            'numero_clientes' => $numero_clientes['total'],




        ];

    

        echo json_encode($informacion);
    }
}
