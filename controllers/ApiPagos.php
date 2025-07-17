<?php

namespace Controllers;

use DateTime;
use Model\Caja;
use Model\Cuota;
use Model\Payment;
use Model\Venta;
use Model\ProductosVenta;
use Model\Usuario;

class ApiPagos
{
    public static function pagos()
    {
        $payments = Payment::all();


        $data = []; // Array para almacenar los datos de los productos

        foreach ($payments as $key => $payment) {

            $sale = Venta::find($payment->sale_id);


            if ($payment->first_payment == 0) {


                $data[] = [
                    $key + 1,
                    $payment->payment_number,
                    $sale->codigo,
                    $payment->sale_box_id + 3000000,
                    number_format($payment->payment_amount),
                    number_format($payment->remaining_balance),

                    $payment->date,

                ];
            }
        }

        // Generar el JSON final
        $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

        echo $datoJson;
    }

    public static function pagosPorVenta()
    {
        session_start();
        date_default_timezone_set('America/Bogota');
        $venta_id = $_GET['venta_id'];

        $venta_id = filter_var($venta_id, FILTER_VALIDATE_INT);

        if (!$venta_id) {

            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
        $payments  = Payment::whereAll('sale_id', $venta_id, 'id');
        if (!$payments) {
            echo json_encode(['type' => 'error', 'msg' => 'Esta venta no tienen nigun pago asociado']);
            return;
        }
        foreach ($payments as $payment) {
            $payment->responsible = Usuario::find($payment->user_id);
        }
        echo json_encode(['type' => 'success', 'data' => $payments]);
        return;
    }
}
