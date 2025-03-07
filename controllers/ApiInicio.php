<?php 

namespace Controllers;

use Model\Caja;
use Model\Cuota;
use Model\Venta;
use Model\Cliente;
use Model\Producto;



class ApiInicio{
    public static function index(){
        if(!is_admin()){
            echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
            return;
        }
   
        // $info = [];
        // $ingresos = Venta::total('recaudo');
        // if($ingresos){
        //     $info['ingresos'] = $ingresos['total'];
        // }else{
        //     $info['ingresos'] = 0;
        // }
        // $costos = Venta::total('costo');
        // if($costos){
        //     $info['costos'] = $costos['total'];
        // }else{
        //     $info['costos'] = 0;
        // }

        //  $info['ganancias'] = $ingresos['total'] - $costos['total'];

        //  $inventario = Producto::total('stock*precio_compra');
        //  if($inventario){
        //      $info['inventario'] = $inventario['total'];
        //  }else{
        //      $info['inventario'] = 0;
        //  }

        // $ingresos_reales = Venta::total('recaudo');
        // if($ingresos_reales){
        //     $info['ingresos_reales'] = $ingresos_reales['total'];
        // }else{
        //     $info['ingresos_reales'] = 0;
        // }
        // $mercado_libre = Venta::whereArray(['metodo_pago'=>3]);
        // $dinero_mercado_libre = 0;
        // $dinero_pendiente_mercado_libre = 0;
        // if($mercado_libre){
        //     foreach($mercado_libre as $venta){
        //         if($venta->estado==1){
        //             $dinero_mercado_libre = $dinero_mercado_libre+$venta->recaudo;
        //         }else{
        //             $dinero_pendiente_mercado_libre = $dinero_pendiente_mercado_libre+$venta->total;
        //         }
        //     }
        //     $info['dinero_mercado_libre'] = $dinero_mercado_libre;
        //     $info['dinero_pendiente_mercado_libre'] = $dinero_pendiente_mercado_libre;
           
        // }else{
        //     $info['dinero_mercado_libre'] = 0;
        //     $info['dinero_pendiente_mercado_libre'] = 0;
        // }

        // $info['ganancias_reales'] = $info['ingresos_reales'] -  $info['costos'];
        // $info['fiados'] = $info['ingresos'] -  $info['ingresos_reales'];

        $ventas = Venta::all();

        $total = 0;
        $costo = 0; //costos de todas las ventas 
  
        
        
        foreach($ventas as $venta){
            $total = $total + $venta->recaudo;
            // if($venta->estado==0){
            //     $costo_fiados = $costo + $venta->costo;
            // }

            $costo = $costo + $venta->costo;
        }

        $ganancia = $total - $costo;
        $inventario = Producto::total('stock*precio_compra');

        $numero_ventas = Venta::contar('estado',1);
        $numero_fiados = Venta::contar('estado',0);
        $numero_pagos = Cuota::contar();
        $numero_cajas = Caja::contar();
        $numero_productos = Producto::contar();
        $numero_clientes  = Cliente::contar();

 

        $informacion = [
            'ingresos'=>'$'.number_format($total),
            'costos'=>'$'.number_format($costo),
            'ganancias'=>'$'.number_format($ganancia),
            'inventario'=>number_format($inventario['total']),
            'numero_ventas'=>$numero_ventas['total'],
            'numero_fiados'=>$numero_fiados['total'],
            'numero_pagos'=>$numero_pagos['total'],
            'numero_cajas'=>$numero_cajas['total'],
            'numero_productos'=>$numero_productos['total'],
            'numero_clientes'=>$numero_clientes['total'],
           
       
            

        ];

   



        


        

        
  
         echo json_encode($informacion);
    


    }
}