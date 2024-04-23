<?php 

    namespace Controllers;

use Model\Caja;
use Model\Cuota;
use Model\Venta;
use Model\Cliente;
use Model\PagoCuota;
use Model\ProductosVenta;

    class ApiFiados{
        public static function pagosCuotas(){
   
            if(!is_admin()){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }

         
            $cliente_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
         
            if(!$cliente_id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }
        
            // $pago_cuotas = PagoCuota::whereArray(['cliente_id'=> $cliente_id]);
          
         
            //$pagos_cuotas = PagoCuota::toDoJoin('pago_cuotas','id','pago_cuotas_id','cliente_id',$cliente_id);
            $pagos_cuotas = Cuota::toDoJoin('pago_cuotas','id','pago_cuotas_id','cliente_id',$cliente_id);
            $fiados= PagoCuota::toDoJoin('ventas','id','venta_id','cliente_id',$cliente_id);
            echo json_encode(['pagos_cuotas'=>$pagos_cuotas, 'fiados'=>$fiados]);
  

            
        }

        public static function productosFiados(){
            if(!is_admin()){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            } 
            $venta_id = filter_var($_GET['venta-id'], FILTER_VALIDATE_INT);
         
            if(!$venta_id){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }

    
            $productos = ProductosVenta::toDoJoin('productos','id', 'producto_id', 'venta_id', $venta_id);
            if(!$productos){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }

            echo json_encode($productos);

           
            
        }

        public static function eliminarPago(){
            if(!is_admin()){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            } 
            $numero_pago = filter_var($_POST['numero_pago'], FILTER_VALIDATE_INT);
         
            if(!$numero_pago){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }

            $cuota = Cuota::where('numero_pago', $numero_pago);
            $caja = Caja::where('id', $cuota->caja_id);
            if(!$caja){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }
            if($caja->estado==1){
                echo json_encode(['type'=>'error', 'msg'=>'la caja asociada a este pago ya ha sido cerrada']);
                return;
            }
            $pago_cuotas = PagoCuota::find($cuota->pago_cuotas_id);
            $venta  = Venta::find($pago_cuotas->venta_id);

            $venta->recaudo = $venta->recaudo - $cuota->monto;
            $venta->estado = 0;

            $db = Venta::getDB();
            $db->begin_transaction();
            
            $caja->numero_transacciones = $caja->numero_transacciones - 1;
            try {
                $venta->guardar();
                $cuota->eliminar();
                $caja->guardar();
               
                $db->commit();
                echo json_encode(['type' => 'success', 'msg' => 'Pago eliminado exitosamente']);
                return;
            } catch (Exception $e) {
                debuguear($e);
                $db->rollback();
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
                return;
            }


            echo json_encode( $venta);
          

        }

        public static function pagar(){
            if(!is_admin()){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            } 

            date_default_timezone_set('America/Bogota');
            $caja = Caja::get(1);
            if(!$caja){
                echo json_encode(['type'=>'error', 'msg'=>'Para realizar pagos debe abrir una caja']);
                return;
            }
            if($caja->estado == 1){
                echo json_encode(['type'=>'error', 'msg'=>'Para realizar pagos debe abrir una caja']);
                return;
            }
 
            $db = PagoCuota::getDB();
            $pago_ventas = PagoCuota::toDoJoin('ventas', 'id', 'venta_id', 'cliente_id', $_POST['cliente_id']);
            $pago_ventas = array_reverse($pago_ventas);

            if(!$pago_ventas){
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, porfavor intente nuevamente']);
                return;
            }

            //si todos los fiados fueron pagados no podemos pagarlos otra vez
            $existe_fiados = array_filter($pago_ventas,function($pago_venta){
                if($pago_venta->estado == 0){
                    return $pago_venta;
                }
               
            });
        

            if(count($existe_fiados)==0){
                echo json_encode(['type'=>'error', 'msg'=>'ya todo se encuentra pago']);
                return;
            }

     

            
            $db->begin_transaction();
            

            try {

                $monto = $_POST['monto'];
                $sobrante = $monto;
            
             
                foreach ($existe_fiados as $pago_venta) {

                    if($sobrante>0){
                        $venta = new Venta();
                        $venta->sincronizar($pago_venta); //venta que se va a editar el recuado ya que se le va a añadir un pago
                        $saldo = $venta->total - $venta->recaudo;

                        $numero_pago = 200000;
                        $ultima_cuota = Cuota::get(1);
                        if($ultima_cuota){
                            $numero_pago = $ultima_cuota->numero_pago + 1;
                        }
                        $cuota = new Cuota();
                        $cuota->fecha_pago = date('Y-m-d H:i:s');
                        $cuota->numero_pago = $numero_pago;
                        $cuota->caja_id = $caja->id;
                       
                        $pago_cuotas = PagoCuota::where('venta_id',$pago_venta->id); //nos traemos pago cuotas para concer el id 
    
                        $cuota->pago_cuotas_id = $pago_cuotas->id;
                        

                       
                        
    
                        if($sobrante>=$saldo){
                            $pago = $saldo; //monto del pago
                            $sobrante = $sobrante - $pago;
                            $venta->recaudo = $venta->total;
                            $venta->estado = 1;
                            

                            $cuota->saldo = 0;
                            $cuota->monto = $pago;

                       
                            // $venta->guardar();
                            
                        }else{ //si el monto es menor debemos sumar ese monto al saldo y salir del for
                            $pago = $sobrante;
                            $sobrante = 0;
                            $venta->recaudo = $venta->recaudo + $pago;
                            $cuota->saldo = $venta->total - $venta->recaudo;
                            $cuota->monto = $pago;
                        }
    
                        $caja->numero_transacciones = $caja->numero_transacciones + 1;
                        $venta->fecha = date('Y-m-d H:i:s');
                        $venta->guardar();
                        $cuota->guardar();
                        $caja->guardar();
                    }
                 
                    
 
                }
    
               
                $db->commit();
                echo json_encode(['type' => 'success', 'msg' => 'Pago creado exitosamente']);
                return;
            } catch (Exception $e) {
             
                $db->rollback();
                echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
                return;
            }

        }
    }