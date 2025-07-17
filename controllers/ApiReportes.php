<?php 

namespace Controllers;

use Model\Caja;
use Model\Cuota;
use Model\Venta;
use Model\Cliente;
use Model\Producto;

class ApiReportes{
    public static function info(){
        if(!is_auth()){
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        date_default_timezone_set('America/Bogota');

        $fecha = $_POST['fecha']." 00:00:00";
      
       
        //ingresos a la fecha por ventas en efectivo

        $ventas_all = Venta::all();
        $total= 0;
        $costo = 0;
    

        $total = 0;

        
        foreach($ventas_all as $venta){
   
            if( (strtotime($venta->fecha) >= strtotime($fecha))){
               
                $total = $total+ $venta->recaudo;
                // if($venta->estado==1){
                    $costo = $costo + $venta->costo;
                // }
               
              
            }
        }


        $ganancias = $total - $costo;
      
    


     
    
        $numero_ventas = Venta::contarPorFecha('pagado',1, 'fecha', $fecha);
 

        $numero_fiados = Venta::contarPorFecha('pagado',0, 'fecha', $fecha);
        $numero_pagos = Cuota::contarPorFecha(null,null, 'fecha_pago', $fecha);
        $numero_cajas = Caja::contarPorFecha(null,null, 'fecha_apertura', $fecha);
        $numero_productos = Producto::contar();
        $numero_clientes  = Cliente::contar();

        $inventario = Producto::total('stock*precio_compra');
        $total_inventario  = 0;
        if($inventario){
            $total_inventario = $inventario['total'];
        }
 
   
        $informacion = [
        
            'ingresos_totales'=>'$'.number_format($total),
            'costos'=>'$'.number_format($costo),
            'ganancias'=>'$'.number_format($ganancias),
            'total_inventario'=>'$'.number_format($total_inventario),
         
       
            'numero_ventas'=>number_format($numero_ventas['total']),
            'numero_fiados'=>number_format($numero_fiados['total']),
            'numero_pagos'=>number_format($numero_pagos['total']),
            'numero_cajas'=>number_format($numero_cajas['total']),
            'numero_productos'=>number_format($numero_productos['total']),
            'numero_clientes'=>number_format($numero_clientes['total'])

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