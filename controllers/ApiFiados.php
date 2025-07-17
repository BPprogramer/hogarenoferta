<?php

namespace Controllers;

use Exception;
use Model\Caja;
use Model\Cuota;
use Model\Venta;
use Model\Cliente;
use Model\PagoCuota;
use Model\Payment;
use Model\ProductosVenta;

class ApiFiados
{

    /* consultamos las ventas que el cliente ha sacado fiadas sin importar si ya las pago o no  */
    public static function ventasFiadas()
    {

        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }


        $cliente_id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

        if (!$cliente_id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $ventas_fidas = Venta::whereArray(['cliente_id' => $cliente_id, 'metodo_pago' => 2]);
        // $pago_cuotas = PagoCuota::whereArray(['cliente_id'=> $cliente_id]);

        echo json_encode(['ventas_fiadas' =>  $ventas_fidas]);
    }

    public static function productosFiados()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $venta_id = filter_var($_GET['venta_id'], FILTER_VALIDATE_INT);

        if (!$venta_id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }


        $productos = ProductosVenta::toDoJoin('productos', 'id', 'producto_id', 'venta_id', $venta_id);
        if (!$productos) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        echo json_encode($productos);
    }

    public static function eliminarPago()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $payment_id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

        if (!$payment_id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        $payment = Payment::find($payment_id);

        $caja = Caja::where('id', $payment->sale_box_id);
        $venta  = Venta::find($payment->sale_id);

        if (!$caja) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        if ($caja->estado == 1) {
            echo json_encode(['type' => 'error', 'msg' => 'la caja asociada a este pago ya ha sido cerrada, por lo que no se puede eliminar']);
            return;
        }



        $venta->recaudo = $venta->recaudo - $payment->payment_amount;
  
        $venta->pagado = 0;
        $caja->numero_transacciones = $caja->numero_transacciones - 1;

        $db = Venta::getDB();
        $db->begin_transaction();


        try {
            $venta->guardar();
            $payment->eliminar();
            $caja->guardar();

            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Pago eliminado exitosamente']);
            return;
        } catch (Exception $e) {
            debuguear($e);
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }


        echo json_encode($venta);
    }

    public static function pagar()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        date_default_timezone_set('America/Bogota');
        $caja = Caja::get(1);
        if (!$caja) {
            echo json_encode(['type' => 'error', 'msg' => 'Para realizar pagos debe abrir una caja']);
            return;
        }
        if ($caja->estado == 1) {
            echo json_encode(['type' => 'error', 'msg' => 'Para realizar pagos debe abrir una caja']);
            return;
        }

        $db = Payment::getDB(); //para empezar con la transaccion me traigo una estancia de la base de datos
        $venta = Venta::find($_POST['venta_id']); //Consulto la venta


        $payments = Payment::where('sale_id', $venta->id); //consultamos si existe por lo menos un primer pago

        /* las siguemtes 3 lineas de codigo las hago por si no existe ni el primer pago en toda la tabla de pagos esto para el numero de pago */
        $payment_number = 2000000;

        $last_payment = Payment::get(1);
        if ($last_payment) {
            $payment_number = $last_payment->payment_number + 1;
        }
        $installment_number = 1; //numero de cuota
        if ($payments) {
            //si tenemos un $installment_number debe sumarse a 1 porque es el siguiente ago
            $installment_number  =  $payments->installment_number + 1;
        }

        $payment = new Payment();
        $payment->payment_number =  $payment_number;
        $payment->payment_amount =  $_POST['monto'];
        /* para calcular el restante al total de la factura le resto lo que ya esta recaudado y tambien le resto el nuevo monto de pago */
        $payment->remaining_balance =  $venta->total_factura - $venta->recaudo  -  $_POST['monto'];
        $payment->date = date('Y-m-d H:i:s');
        $payment->installment_number = $installment_number;
        $payment->sale_id = $venta->id;
        $payment->sale_box_id = $caja->id;

        $payment->user_id = $_SESSION['id'];

        // a la venta en la columna recaudo le sumamos el nuevo monto 
        $venta->recaudo =    $venta->recaudo +  $_POST['monto'];
        if ($venta->recaudo  == $venta->total_factura) {
            $venta->pagado = 1;
        }

        $caja->numero_transacciones = $caja->numero_transacciones + 1;
        $db->begin_transaction();
        try {

            $payment->guardar();
            $venta->guardar();
            $caja->guardar();
            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Pago creado exitosamente']);
            return;
        } catch (Exception $e) {

            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }
}
