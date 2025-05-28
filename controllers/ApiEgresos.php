<?php

namespace Controllers;

use Model\Caja;
use Model\Egreso;
use Model\Usuario;

class ApiEgresos
{

    public static function egresos()
    {
        $egresos = Egreso::all();


        $i = 0;
        $data = [];
        foreach ($egresos as $key => $egreso) {
            $usuario = Usuario::where('id', $egreso->usuario_id);
            $caja = Caja::where('id', $egreso->caja_id);
            $i++;

            $acciones = "<div class='d-flex justify-content-center' >";

            if ($egreso->estado == 1 && $caja->estado == 0) {
                $acciones .= "<button data-egreso-id ='" . $egreso->id . "' id='editar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
                $acciones .= "<button data-egreso-id ='" . $egreso->id . "' id='eliminar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio' ><span class='toolMio-text'>Anular</span><i class='fas fa-trash' ></i></button>";
            } else {
                $acciones .= "";
            }


            $acciones .= "</div>";



            $estado = '';
            if ($egreso->estado == 1) {

                $estado = "<div class='d-flex justify-content-center'>";

                $estado .= "<button type='button' data-egreso-id ='' id='cerrar'  class='btn w-75 btn-inline bg-azul text-white btn-sm toolMio' style='min-width:70px'>Activo</button>";
                $estado .= "</div >";
            } else {
                $estado = "<div class='d-flex justify-content-center' >";
                $estado .= "<button  type='button' class='btn  w-75 btn-inline btn-secondary btn-sm ' style='min-width:70px'>Anulado</button>";
                $estado .= "</div >";
            }
            $data[] = [
                $key + 1, // Índice
                $usuario->nombre, // Código del producto
                $egreso->fecha, // Nombre del producto
                number_format($egreso->egreso), // Stock (HTML)
                 $egreso->caja_id+1000,
                $egreso->descripcion,
                $estado, // Precio de venta formateado
                $acciones // Acciones (HTML)
            ];
        
        }


        echo json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);
    }
    public static function egreso()
    {
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta Nuevamente']);
            return;
        }

        $egreso = Egreso::find($id);
        echo json_encode($egreso);
    }

    public static function eliminar()
    {
        $id = $_POST['id'];
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error Intenta Nuevamente']);
            return;
        }
        $egreso = egreso::find($_POST['id']);

        if (!$egreso) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con la egreso']);
            return;
        }
        $caja = Caja::find($egreso->caja_id);
        $caja->numero_transacciones =   $caja->numero_transacciones - 1;
        $db = Egreso::getDB();
        $db->begin_transaction();

        try {

            $caja->guardar();
            $egreso->estado = 0;
            $egreso->guardar();

            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'egreso anulado con exito']);
            return;
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }
    public static function editar()
    {
        $id = $_POST['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta Nuevamente']);
            return;
        }

        $egreso = Egreso::find($id);
        if (!$egreso) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con la egreso']);
            return;
        }
        $egreso->sincronizar($_POST);
        $egreso->formatearDatosFloat();
        $resultado = $egreso->guardar();
        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El egreso ha sido Actualizado con Exito']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
        return;
    }

    public static function crear()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        //pregntamos por una caja actual
        $caja = Caja::get(1);
        if ($caja->estado != 0) {
            echo json_encode(['type' => 'error', 'msg' => 'Para registrar un Egreso debe tener una caja abierta']);
            return;
        }

        $db = Egreso::getDB();

        $db->begin_transaction();
        date_default_timezone_set('America/Bogota');
        try {

            $egreso = new Egreso($_POST);
            $egreso->formatearDatosFloat();
            $egreso->fecha =  date('Y-m-d H:i:s');
            $egreso->estado = 1;
            $egreso->caja_id = $caja->id;
            $egreso->usuario_id = $_SESSION['id'];
            $caja->numero_transacciones = $caja->numero_transacciones + 1;
            $caja->guardar();
            $egreso->guardar();

            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'egreso registrado con exito']);
            return;
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }
}
