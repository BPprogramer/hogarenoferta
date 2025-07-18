<?php

namespace Controllers;

use Model\Caja;
use Model\Cuota;
use Model\Venta;
use Model\Cliente;
use Model\Producto;

class ApiReportes
{
    public static function info()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        date_default_timezone_set('America/Bogota');

        $fecha = $_POST['fecha'] . " 00:00:00";


        //ingresos a la fecha por ventas en efectivo

        $ventas = Venta::all();
        $total_factura = 0; //total de la venta sin importar si es fiado o no (ventas)
        $total_recaudos = 0; // lo que se ha recaudado como tal  (ingresos)
        $costo = 0; //costos de todas las ventas 

        foreach ($ventas as $venta) {

            if ((strtotime($venta->fecha) >= strtotime($fecha))) {

                $total_factura = $total_factura + $venta->total_factura;
                $total_recaudos = $total_recaudos + $venta->recaudo;
                $costo = $costo + $venta->costo;
            }
        }


        $ganancia_no_realizada = $total_factura - $costo; //ganancias netas sin importar si ´pagaron o no
        $ganancia_realizada = $total_recaudos - $costo; //ganancias real 
        $inventario = Producto::total('stock*precio_compra');







        $numero_ventas = Venta::contarPorFecha('pagado', 1, 'fecha', $fecha);
        $numero_fiados = Venta::contarPorFecha('pagado', 0, 'fecha', $fecha);
        $numero_pagos = Cuota::contarPorFecha(null, null, 'fecha_pago', $fecha);
        $numero_cajas = Caja::contarPorFecha(null, null, 'fecha_apertura', $fecha);
        $numero_productos = Producto::contar();
        $numero_clientes  = Cliente::contar();


        $inventario = Producto::total('stock*precio_compra');

        $valor_formateado = '$' . number_format(abs($ganancia_realizada));
        $ganancia_realizada = $ganancia_realizada < 0 ? '-' . $valor_formateado : $valor_formateado;

        $informacion = [
            'total_ventas' => '$' . number_format($total_factura),
            'total_recaudos' => '$' . number_format($total_recaudos),
            'costos' => '$' . number_format($costo),
            'ganancia_no_realizada' =>  '$' . number_format($ganancia_no_realizada),
            'ganancia_realizada' => $ganancia_realizada,
            'inventario' => '$' . number_format($inventario['total']),
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





// namespace Controllers;

// use Model\Caja;
// use Model\Cuota;
// use Model\Venta;
// use Model\Cliente;
// use Model\Producto;

// class ApiReportes{
//     public static function info(){
//         if(!is_auth()){
//             echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
//             return;
//         }

//         $fecha = $_POST['fecha']." 00:00:00";
      
//         $info = [];
//         $ingresos = Venta::total('recaudo', 'fecha',$fecha);
//         if($ingresos){
//             $info['ingresos'] = $ingresos['total'];
//         }else{
//             $info['ingresos'] = 0;
//         }
//         $costos = Venta::total('costo', 'fecha',$fecha);
//         if($costos){
//             $info['costos'] = $costos['total'];
//         }else{
//             $info['costos'] = 0;
//         }

//          $info['ganancias'] = $ingresos['total'] - $costos['total'];

//          $inventario = Producto::total('stock*precio_compra');
//          if($inventario){
//              $info['inventario'] = $inventario['total'];
//          }else{
//              $info['inventario'] = 0;
//          }

//         $ingresos_reales = Venta::total('recaudo', 'fecha',$fecha);
//         if($ingresos_reales){
//             $info['ingresos_reales'] = $ingresos_reales['total'];
//         }else{
//             $info['ingresos_reales'] = 0;
//         }

//         $info['ganancias_reales'] = $info['ingresos_reales'] -  $info['costos'];
//         $info['fiados'] = $info['ingresos'] -  $info['ingresos_reales'];

//         $numero_ventas = Venta::contarPorFecha('estado',1, 'fecha', $fecha);
 

//         $numero_fiados = Venta::contarPorFecha('estado',0, 'fecha', $fecha);
//         $numero_pagos = Cuota::contarPorFecha(null,null, 'fecha_pago', $fecha);
//         $numero_cajas = Caja::contarPorFecha(null,null, 'fecha_apertura', $fecha);
//         $numero_productos = Producto::contar();
//         $numero_clientes  = Cliente::contar();

 

//         $informacion = [
//             'ingresos'=>'$'.number_format($info['ingresos']),
//             'costos'=>'$'.number_format($info['costos']),
//             'ganancias'=>'$'.number_format($info['ganancias']),
//             'inventario'=>'$'.number_format($info['inventario']),
//             'ingresos_reales'=>'$'.number_format($info['ingresos_reales']),
//             'ganancias_reales'=>'$'.number_format($info['ganancias_reales']),
//             'fiados'=>'$'.number_format($info['fiados']),
//             'numero_ventas'=>number_format($numero_ventas['total']),
//             'numero_fiados'=>number_format($numero_fiados['total']),
//             'numero_pagos'=>number_format($numero_pagos['total']),
//             'numero_cajas'=>number_format($numero_cajas['total']),
//             'numero_productos'=>number_format($numero_productos['total']),
//             'numero_clientes'=>number_format($numero_clientes['total'])

//         ];


   



//          echo json_encode($informacion);
//     }
// }