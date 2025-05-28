<?php

namespace Controllers;

use DateTime;
use Model\Caja;
use Model\Cuota;

use Model\Venta;
use Model\ProductosVenta;

class ApiPagos
{
    public static function pagos()
    {
        $pagos = Cuota::all();


        $data = []; // Array para almacenar los datos de los productos

        foreach ($pagos as $key => $pago) {

            if (!$pago->cuota_inicial) {


                $data[] = [
                    $key + 1,
                    $pago->numero_pago, // Código del producto
                    number_format($pago->monto), // Nombre del producto
                    number_format($pago->saldo), // Stock (HTML)
                    $pago->caja_id + 1000, // Precio de compra formateado
                    $pago->fecha_pago,

                ];
            }
        }

        // Generar el JSON final
        $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

        echo $datoJson;
    }
}
