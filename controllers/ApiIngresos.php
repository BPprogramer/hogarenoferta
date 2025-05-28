<?php

namespace Controllers;

use Model\Caja;
use Model\Ingreso;
use Model\Usuario;

class ApiIngresos
{

    public static function ingresos()
    {
        $ingresos = Ingreso::all();
        $data = [];

        foreach ($ingresos as $key => $ingreso) {
            $usuario = Usuario::find($ingreso->usuario_id);
            $caja = Caja::find($ingreso->caja_id);

            // Acciones disponibles
            $acciones = "<div class='d-flex justify-content-center'>";
            if ($ingreso->estado == 1 && $caja && $caja->estado == 0) {
                $acciones .= "<button data-ingreso-id='" . $ingreso->id . "' id='editar' type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
                $acciones .= "<button data-ingreso-id='" . $ingreso->id . "' id='eliminar' type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Anular</span><i class='fas fa-trash'></i></button>";
            }
            $acciones .= "</div>";

            // Estado del ingreso
            $estado = "<div class='d-flex justify-content-center'>";
            if ($ingreso->estado == 1) {
                $estado .= "<button type='button' class='btn w-75 btn-inline bg-azul text-white btn-sm' style='min-width:70px'>Activo</button>";
            } else {
                $estado .= "<button type='button' class='btn w-75 btn-inline btn-secondary btn-sm' style='min-width:70px'>Anulado</button>";
            }
            $estado .= "</div>";

            // Agregar datos al array
            $data[] = [
                $key + 1, // Índice
                $usuario ? $usuario->nombre : 'Desconocido', // Nombre del usuario
                $ingreso->fecha, // Fecha del ingreso
                number_format($ingreso->ingreso), // Monto del ingreso formateado
                $ingreso->caja_id + 1000,
                $ingreso->descripcion, // Descripción del ingreso
                $estado, // Estado (HTML)
                $acciones // Acciones (HTML)
            ];
        }

        // Generar el JSON
        echo json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);
    }

    public static function ingreso()
    {
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta Nuevamente']);
            return;
        }

        $ingreso = Ingreso::find($id);
        echo json_encode($ingreso);
    }

    public static function eliminar()
    {
        $id = $_POST['id'];
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error Intenta Nuevamente']);
            return;
        }
        $ingreso = Ingreso::find($_POST['id']);

        if (!$ingreso) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con la ingreso']);
            return;
        }
        $caja = Caja::find($ingreso->caja_id);
        $caja->numero_transacciones =   $caja->numero_transacciones - 1;
        $db = Ingreso::getDB();
        $db->begin_transaction();

        try {

            $caja->guardar();
            $ingreso->estado = 0;
            $ingreso->guardar();

            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Ingreso anulado con exito']);
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

        $ingreso = Ingreso::find($id);
        if (!$ingreso) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con la ingreso']);
            return;
        }
        $ingreso->sincronizar($_POST);
        $ingreso->formatearDatosFloat();
        $resultado = $ingreso->guardar();
        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El ingreso ha sido Actualizado con Exito']);
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
            echo json_encode(['type' => 'error', 'msg' => 'Para registrar un ingreso debe tener una caja abierta']);
            return;
        }

        $db = Ingreso::getDB();

        $db->begin_transaction();
        date_default_timezone_set('America/Bogota');
        try {

            $ingreso = new Ingreso($_POST);
            $ingreso->formatearDatosFloat();
            $ingreso->fecha =  date('Y-m-d H:i:s');
            $ingreso->estado = 1;
            $ingreso->caja_id = $caja->id;
            $ingreso->usuario_id = $_SESSION['id'];
            $caja->numero_transacciones = $caja->numero_transacciones + 1;
            $caja->guardar();
            $ingreso->guardar();

            $db->commit();
            echo json_encode(['type' => 'success', 'msg' => 'Ingreso registrado con exito']);
            return;
        } catch (Exception $e) {
            $db->rollback();
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
            return;
        }
    }
}
