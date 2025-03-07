<?php



namespace Controllers;

use Exception;
use Model\Caja;
use Model\Cliente;
use Model\Cuota;
use Model\PagoCuota;
use Model\Producto;
use Model\ProductosVenta;
use Model\Usuario;
use Model\Venta;
use TCPDF;

class ApiVentas
{
    public static function imprimirVenta()
    {

        $id = $_GET['id'];
        $venta = Venta::find($id);
        $vendedor = Usuario::find($venta->vendedor_id);
        $importe = 0;
        // echo "<pre>";
        //     var_dump($venta);
        //     echo "</pre>";

        $productos = ProductosVenta::whereArray(['venta_id' => $venta->id]);
        // echo "<pre>";
        // var_dump($productos);
        // echo "</pre>";
        // foreach($productos as $producto_venta){
        //     $producto = Producto::find($producto_venta->producto_id);
        //     echo "<pre>";
        //     $valor_unitario = number_format($producto_venta->precio);
        //     var_dump($valor_unitario);
        //     $total = number_format( $valor_unitario*$producto_venta->cantidad);

        //     echo "</pre>";
        // }

        // echo "<pre>";
        // var_dump($productos);
        // echo "</pre>";
        $pdf  = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $fecha = substr($venta->fecha, 0, -8);


        $pdf = new TCPDF('P', 'mm', array(80, 190), true, 'UTF-8', false);
        //$pdf = new TCPDF('P', 'mm', 'A7', true, 'UTF-8', false);
        $altura_personalizada = 190; // Altura en mm
        $pdf->setPrintHeader(false); //quitamos el header
        $pdf->setPrintFooter(false);

        // Agregar una página al PDF con la altura personalizada
        $pdf->SetMargins(0, 0, 10, 0);
        $pdf->AddPage();

        // Agregar contenido al PDF
        $pdf->SetFont('helvetica', '', 12);

        $bloque_1 = <<<EOF
           
            <div  style="font-size:10px;width:160px; height:500px; text-align:center; font-size:8px margin-bottom:0;">
                <strong style="font-size:11px">DISTRIHOGAR EL TABLÓN</strong>
                <br>
                Nit: 1087645922-1
                <br>
                Cel: 3216196607
                <br>
                Atendido por: $vendedor->nombre
                <br>
                EL TABLON DE GÓMEZ BARRIO CENTRAL
           
            </div>
            EOF;
         $pdf->writeHTML($bloque_1, false, false, false, false, '');
         $bloque_salto = <<<EOF
               
         <span  style="font-size:10px;width:160px; text-align:center; font-size:8px margin-bottom:0;">
             ************************************************************
            <br>
         </span>
     EOF;

         $pdf->writeHTML($bloque_salto, false, false, false, false, '');
         $pdf->write1DBarcode($venta->codigo, 'C39', 34, '', '', 7, 0.3);
         $bloque_2 = <<<EOF
         
                <div  style="font-size:10px;width:160px; height:500px; text-align:left; font-size:8px margin-bottom:0;"> 
                
               
                    Ticket No: $venta->codigo  
                    <br>
                     Fecha : $fecha
                   
                    <br>
                    Cliente: $venta->nombre_cliente
                    <br>
                    Cedula No: <span style="font-size:7px">$venta->cedula_cliente </span> 
                    <br>
                     Celular <span style="font-size:7px">$venta->celular_cliente </span> 
            
                  
                 
                    <br>
                    Dirección: $venta->direccion_cliente
                    <br>
                    Vendedor: $vendedor->nombre
         
            </div>    
               

        EOF;

        $pdf->writeHTML($bloque_2, false, false, false, false, '');

        $bloque_salto = <<<EOF
               
        <div  style="font-size:10px;width:160px; text-align:center; font-size:8px margin-bottom:0;">
            ************************************************************
            <br>
            <strong>Productos</strong>
           
           
        </div>
    EOF;
        $pdf->writeHTML($bloque_salto, false, false, false, false, '');

        foreach ($productos as $producto_venta) {
            // $total_producto = number_format($producto['precio_producto']*$producto['cantidad'],2);
            // $valor_unitario = number_format($producto['precio_producto'],2);
            // $description = substr($producto['descripcion'], 0 , 15);

            $valor_unitario = number_format($producto_venta->precio);
            $total = number_format($producto_venta->precio * $producto_venta->cantidad);

            $producto = Producto::find($producto_venta->producto_id);
            $importe = $importe + $producto->precio_venta * $producto_venta->cantidad; //aqui calculamos el valor al precio original (sin descuetno)


            $bloque_productos = <<<EOF
      
        <div  style="font-size:10px;width:160px; text-align:left; font-size:8px">
       
            {$producto->nombre}  : {$valor_unitario}X{$producto_venta->cantidad} = {$total}         
        </div>


    EOF;

            $pdf->writeHTML($bloque_productos, false, false, false, false, '');
        }
        $bloque_salto = <<<EOF
               
        <div  style="font-size:10px;width:160px; text-align:center; font-size:8px margin-bottom:0;">
             
            ************************************************************
    
        </div>
    EOF;
        $pdf->writeHTML($bloque_salto, false, false, false, false, '');


        if ($venta->metodo_pago == 1) {
            $descuento = number_format($importe - $venta->total_factura);
            $importe = number_format($importe);
            $total = number_format($venta->total_factura);
            $bloque_correo = <<<EOF
            
                <div  style="font-size:10px;width:160px; text-align:right; font-size:8px margin-bottom:0;">
                    <strong>Metodo de pago </strong> Efectivo  
                   
                    <br>
                    <strong>Importe:</strong> $importe
                    <br>
                    <strong>Descuento :</strong> {$venta->descuento}% = {$descuento}
                    <br>
                 
                        <strong style="font-size:9px">Total:</strong> $total
                 
                 
                    
                </div>
        EOF;
            $pdf->writeHTML($bloque_correo, false, false, false, false, '');
        } else {
            $descuento = number_format($importe - $venta->total_factura);
            $importe = number_format($importe);
            $total = number_format($venta->total_factura);
            $saldo = number_format($venta->total_factura - $venta->recaudo);
            $recaudo = number_format($venta->recaudo);
            $bloque_correo = <<<EOF
            
                <div  style="font-size:10px;width:160px; text-align:right; font-size:8px margin-bottom:0;">
                    <strong>Metodo de pago </strong> Credito  
                    <br>
                    <br>
                    <strong>Importe:</strong> $importe
                    <br>
                    <strong>Descuento :</strong> {$venta->descuento}% = {$descuento}
                    <br>
                    <strong>Total:</strong> $total
                    <br>
                    <strong>Abono:</strong> $recaudo
                    <br>
                    <strong>Saldo Pendiente:</strong> $saldo
                </div>
            EOF;
            $pdf->writeHTML($bloque_correo, false, false, false, false, '');
        }
       
        $bloque_salto = <<<EOF
               
        <span  style="font-size:10px;width:160px; text-align:center; font-size:8px margin-left:0;">
     
            **************************************************************
            <br>
            GRACIAS POR SU VISITA, VUELVA PRONTO 
            <br> 
            **************************************************************
           
        </span>
    EOF;
    $pdf->writeHTML($bloque_salto, false, false, false, false, '');
            
             
        $garantias = <<<EOF
       

            <div  style="font-size:4px; width:160px; height:500px; text-align:center; font-size:6px margin-bottom:0;">
         
            
        
            <span>
                <strong style="font-size:8px">PARA TENER EN CUENTA</strong>
            </span>
            <br>
            <span >
                NO HAY CAMBIOS DE MERCANCIA NI DEVOLUCIÓN DE DINERO 
            </span> 
            <br>
            <span >
                DESPUES DE SALIR DE NUESTRO ALMACEN
            </span> 
            <br>
          
                
                
              
            </div>    
               

        EOF;

        $pdf->writeHTML($garantias, false, false, false, false, '');





        $pdf->Output('example_001.pdf', 'I');
    }
    public static function ventas()
    {
        $ventas = Venta::all();

        $i = 0;

        $datoJson = '{
         "data": [';
        foreach ($ventas as $key => $venta) {
            $i++;
            $caja = Caja::where('id', $venta->caja_id);

            $acciones = "<div class='d-flex' >";
            $acciones .= "<button data-venta-id ='" . $venta->id . "' id='info'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Ver</span><i class='fas fa-search'></i></button>";

            if ($caja->estado == 0) {
                $acciones .= "<button data-venta-id ='" . $venta->id . "' id='editar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
                $acciones .= "<button data-venta-id ='" . $venta->id . "' id='eliminar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio' ><span class='toolMio-text'>Eliminar</span><i class='fas fa-trash' ></i></button>";
            }
            $acciones .= "<button data-venta-id ='" . $venta->id . "' id='imprimir'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Imprimir</span><i class='fas fa-print'></i></button>";
            $acciones .= "</div>";




            $estado = '';
            if ($venta->estado == 0) {
                $estado = "<div class='d-flex justify-content-center' >";
                $estado .= "<button   type='button' class='btn  w-65 btn-inline btn-danger btn-sm ' style='min-width:70px'>Pendiente</button>";
                $estado .= "</div >";
            } else {
                $estado = "<div class='d-flex justify-content-center'>";
                $estado .= "<button   type='button' class='btn w-65 btn-inline bg-success text-white btn-sm' style='min-width:70px'>Pagado</button>";
                $estado .= "</div >";
            }





            $datoJson .= '[
                         "' . $i . '",
                         "' . $venta->codigo . '",
                         "' . number_format($venta->total_factura) . '",
                         "' . number_format($venta->recaudo) . '",
                         "' . $estado . '",
                    
                         "' . $venta->fecha . '",
                         "' . $acciones . '"
                 ]';
            if ($key != count($ventas) - 1) {
                $datoJson .= ",";
            }
        }

        $datoJson .=  ']}';


        echo $datoJson;
    }

    public static function venta()
    {
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
        $venta = Venta::find($id);
        $productos = ProductosVenta::whereArrayJoin(['productos_venta.venta_id' => $venta->id], 'productos', 'id', 'producto_id');
        if ($venta->metodo_pago == 2) {
            $pago_cuotas = PagoCuota::where('venta_id', $venta->id);

            if (!$pago_cuotas) {
                echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
                return;
            }

            echo json_encode(['productos_venta' => $productos, 'venta' => $venta, 'cliente_id' => $pago_cuotas->cliente_id]);
            return;
        }
        echo json_encode(['productos_venta' => $productos, 'venta' => $venta]);
    }
    public static function crear()
    {
        session_start();
        date_default_timezone_set('America/Bogota');


        $caja = Caja::get(1);
        if (!$caja) {
            echo json_encode(['type' => 'error', 'msg' => 'Para realizar ventas debe abrir una caja']);
            return;
        }
        if ($caja->estado == 1) {
            echo json_encode(['type' => 'error', 'msg' => 'Para realizar ventas debe abrir una caja']);
            return;
        }

        $caja->numero_transacciones = $caja->numero_transacciones + 1;

        $venta = new Venta();
        $venta->sincronizar($_POST);



        $venta->formatearDatosFloat();
        $venta->caja_id = $caja->id;

        $venta->fecha = date('Y-m-d H:i:s');
        $venta->vendedor_id = $_SESSION['id'];

        $venta_anterior = Venta::get(1);

        if (!$venta_anterior) {
            $venta->codigo = 1000;
        } else {
            $venta->codigo = $venta_anterior->codigo + 1;
        }

        $db = Venta::getDB();
        $db->begin_transaction();


        try {
            $resultado = $venta->guardar();

            $caja->guardar();

            $productos = json_decode($_POST['productosArray']);
            foreach ($productos as $producto) {
                $producto_actual = Producto::find($producto->id);
                $producto_actual->stock = $producto->stock - $producto->cantidad;
                $producto_actual->ventas = $producto_actual->ventas + $producto->cantidad;
                $producto_actual->guardar();

                $datos = ['cantidad' => $producto->cantidad, 'precio' => $producto->precio, 'precio_factura' => $producto->precio_venta, 'producto_id' => $producto->id, 'venta_id' => $resultado['id']];
                $productos_venta = new ProductosVenta($datos);

                $productos_venta->guardar();
            }

            if ($venta->metodo_pago == 2) {

                $numero_pago = 200000;
                $ultima_cuota = Cuota::get(1);
                if ($ultima_cuota) {
                    $numero_pago = $ultima_cuota->numero_pago + 1;
                }




                $pago_cuotas = new PagoCuota();

                $pago_cuotas->venta_id = $resultado['id'];
                $pago_cuotas->cliente_id = $_POST['cliente_id'];
                $resultado = $pago_cuotas->guardar();

                if ($venta->recaudo != 0) {
                    $cuota = new Cuota();
                    $cuota->monto = $venta->recaudo;
                    $cuota->saldo = $venta->total - $venta->recaudo;
                    $cuota->fecha_pago = $venta->fecha;
                    $cuota->numero_pago = $numero_pago;
                    $cuota->caja_id = $caja->id;
                    $cuota->pago_cuotas_id = $resultado['id'];

                    $cuota->guardar();
                }
            }
            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Venta guardada con Exito']);
            return;
        } catch (Exception $e) {
            debuguear($e);
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }

    //antes de editar revisamos que no hayan pagos asociados de lo contrario no se podra editar la venta


    public static function revisarPagosAsociados()
    {


        if (!is_admin()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }



        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);


        if (!$id) {

            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
        $fiados_asociados = PagoCuota::where('venta_id', $id);

        // if(!$fiados_asociados){
        //     echo json_encode(['type'=>'error', 'msg'=>'Hubo un error, Intenta nuevamente']);
        //     return;
        // }


        $pagos_asociados = [];

        if ($fiados_asociados) {

            $pagos_asociados = Cuota::whereArray(['pago_cuotas_id' => $fiados_asociados->id]);
            if (count($pagos_asociados) > 0) {
                echo json_encode(['type' => 'error', 'msg' => 'Tiene pagos asociados por lo que no puede editar la venta']);
                return;
            }
        }



        echo json_encode(['type' => 'success', 'msg' => 'redireccionando']);
        return;
    }

    public static function editar()
    {
        session_start();
        date_default_timezone_set('America/Bogota');



        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);

        if (!$id) {

            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }

        $venta_actual = Venta::find($id);
        if (!$venta_actual) {

            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
        $db = Venta::getDB();



        // $productosVenta = new ProductosVenta();

        $productos_venta = ProductosVenta::whereArray(['venta_id' => $id]);

        if (!$productos_venta) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
        $db->begin_transaction();
        try {
            foreach ($productos_venta as $producto_venta) {
                $producto = Producto::find($producto_venta->producto_id);
                $producto->stock = intval($producto->stock) + intval($producto_venta->cantidad);
                $producto->ventas = $producto->ventas - $producto_venta->cantidad;
                $producto->guardar();
            }



            $productos_venta = new ProductosVenta();
            $productos_venta->eliminarWhere('venta_id', $id);


            $venta = new Venta();
            $venta->sincronizar($_POST);


            $venta->fecha = date('Y-m-d H:i:s');
            $venta->vendedor_id = $_SESSION['id'];
            $venta->codigo = $venta_actual->codigo;
            $venta->caja_id = $venta_actual->caja_id;
            $venta->formatearDatosFloat();
            $venta->guardar();





            $productos = json_decode($_POST['productosArray']);
            foreach ($productos as $producto) {
                $producto_actual = Producto::find($producto->id);
                $producto_actual->stock = $producto->stock - $producto->cantidad;
                $producto_actual->ventas = $producto_actual->ventas + $producto->cantidad;
                $producto_actual->guardar();

                $datos = ['cantidad' => $producto->cantidad, 'precio' => $producto->precio, 'precio_factura' => $producto->precio_venta, 'producto_id' => $producto->id, 'venta_id' => $id];
                $productos_venta = new ProductosVenta($datos);


                $productos_venta->guardar();
            }



            if ($venta_actual->metodo_pago == 2) {

                $numero_pago = 200000;
                $ultima_cuota = Cuota::get(1);
                if ($ultima_cuota) {
                    $numero_pago = $ultima_cuota->numero_pago + 1;
                }



                $pago_cuotas_actual = PagoCuota::where('venta_id', $venta_actual->id);
                $cuota_actual = Cuota::where('pago_cuotas_id', $pago_cuotas_actual->id);


                if ($cuota_actual) {
                    $cuota_actual->eliminar();
                }

                $pago_cuotas_actual->eliminar();
            }


            if ($venta->metodo_pago == 2) {


                $pago_cuotas = new PagoCuota();

                $pago_cuotas->venta_id = $venta->id;
                $pago_cuotas->cliente_id = $_POST['cliente_id'];
                debuguear($pago_cuotas);
                $resultado = $pago_cuotas->guardar();

                if ($venta->recaudo != 0) {
                    $cuota = new Cuota();
                    $cuota->monto = $venta->recaudo;
                    $cuota->saldo = $venta->total - $venta->recaudo;
                    $cuota->fecha_pago = $venta->fecha;
                    $cuota->numero_pago = $numero_pago;
                    $cuota->caja_id = $venta->caja_id;
                    $cuota->pago_cuotas_id = $resultado['id'];
                    $cuota->guardar();
                }
            }
            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Venta guardada con Exito']);
            return;
        } catch (Exception $e) {
            debuguear($e);
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }



    public static function eliminar()
    {
        session_start();
        date_default_timezone_set('America/Bogota');



        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {

            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }

        $venta = Venta::find($id);


        if (!$venta) {

            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }

        //consultamos pagos asociasdos a la venta que se quiere eliminar
        //si existen pagos no debe dejar eliminar
        if ($venta->metodo_pago == 2) {
            $fiados_asociados = PagoCuota::where('venta_id', $venta->id);
            if (!$fiados_asociados) {
                echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
                return;
            }



            $pagos_asociados = Cuota::whereArray(['pago_cuotas_id' => $fiados_asociados->id]);
            if (count($pagos_asociados) > 0) {
                echo json_encode(['type' => 'error', 'msg' => 'Tiene pagos asociados por lo que no puede eliminar la venta']);
                return;
            }
        }





        $db = Venta::getDB();

        // $productosVenta = new ProductosVenta();

        $productos_venta = ProductosVenta::whereArray(['venta_id' => $id]);
        if (!$productos_venta) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
        $db->begin_transaction();
        try {
            foreach ($productos_venta as $producto_venta) {
                $producto = Producto::find($producto_venta->producto_id);
                $producto->stock = intval($producto->stock) + intval($producto_venta->cantidad);
                $producto->ventas = $producto->ventas - $producto_venta->cantidad;
                $producto->guardar();
            }
            $productos_venta = new ProductosVenta();
            $productos_venta->eliminarWhere('venta_id', $id);


            if ($venta->metodo_pago == 2) {
                $pago_cuotas_actual = PagoCuota::where('venta_id', $venta->id);
                //$cuota_actual = Cuota::find($pago_cuotas_actual->cuota_id);

                $pago_cuotas_actual->eliminar();
                //    $cuota_actual->eliminar();

            }
            $caja = Caja::find($venta->caja_id);
            $caja->numero_transacciones =  $caja->numero_transacciones - 1;
            $caja->guardar();

            $venta->eliminar();

            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Venta eliminada con Exito']);
            return;
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }


    public static  function productos()
    {
        $productos = Producto::all();
        echo json_encode($productos);
    }
    public static  function clientes()
    {
        $clientes_all = Cliente::all();
        
        // $clientes = [];
       
        // foreach( $clientes_all as $cliente){
        //     $fiados= PagoCuota::toDoJoin('ventas','id','venta_id','cliente_id',$cliente->id);
          
          
          
        //     if(!empty($fiados)){
        //         // echo json_encode( $fiados );
        //         // return ;
        //         if($fiados[0]->total!=$fiados[0]->recaudo){
        //             $clientes[]  =  $cliente;
        //         }
              
            
             
        //     }
          
        //     // echo json_encode(['pagos_cuotas'=>$pagos_cuotas, 'fiados'=>$fiados]);
 
        // }

        
   
        echo json_encode( $clientes_all );
    }
    
    public static function clientesFiados(){
         $clientes_all = Cliente::all();
     
        $clientes = [];
       
        foreach( $clientes_all as $cliente){
            $fiados= PagoCuota::toDoJoin('ventas','id','venta_id','cliente_id',$cliente->id);
          
          
          
            if(!empty($fiados)){
                // echo json_encode( $fiados );
                // return ;
                if($fiados[0]->total!=$fiados[0]->recaudo){
                    $clientes[]  =  $cliente;
                }
              
            
             
            }
          
            // echo json_encode(['pagos_cuotas'=>$pagos_cuotas, 'fiados'=>$fiados]);
 
        }

        
        echo json_encode( $clientes );

        
   
    }

    public static function codigoVenta()
    {
        $venta = Venta::get(1);

        if (!$venta) {
            echo json_encode(1000);
        } else {
            $venta->codigo = $venta->codigo + 1;
            echo json_encode($venta->codigo);
        }
    }
}



/* 
 pago_cuotas
    id
    venta_id
    pago_id
    cliente_id

*/

/*  cuotas

    id 
    monto
    fecha_pago
    cuotas_id
    caja_id

*/