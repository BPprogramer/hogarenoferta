<?php



namespace Controllers;

use Exception;
use Model\Venta;
use Model\Caja;




class ApiMercadoLibre
{
    public static function index()
    {
        $ventas = Venta::whereArray(['metodo_pago' => 3]);

        $i = 0;

        $datoJson = '{
             "data": [';
        foreach ($ventas as $key => $venta) {
            $i++;


            $acciones = "<div class='d-flex' >";
            $acciones .= "<button data-venta-id ='" . $venta->id . "' id='info'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Ver</span><i class='fas fa-search'></i></button>";
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
                             "' . number_format($venta->total) . '",
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

    public static function pagosAuto()
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

        $ventas_mercado_libre = Venta::whereArray(['metodo_pago' => 3, 'estado' => 0]);
        $db = Venta::getDB();
        if ($ventas_mercado_libre) {
            try {

                foreach ($ventas_mercado_libre as $venta) {
                    // if($venta==0){
                        $diferenciaEnDias = floor((strtotime(date("Y-m-d H:i:s")) - strtotime($venta->fecha)) / (60 * 60 * 24));
                        
                        if ($diferenciaEnDias >= 19) {
                            $venta->caja_id = $caja->id;
                            $venta->recaudo = $venta->total;
                            $venta->estado = 1;
                            $venta->fecha = date('Y-m-d H:i:s');
                            $venta->guardar();
                    
                        }
                    // }
                    
                }
                $db->commit();
                return;
            } catch (Exception $e) {
                $db->rollback();
                echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
                return;
            }
        }
    }
}
