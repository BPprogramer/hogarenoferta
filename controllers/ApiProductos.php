<?php

namespace Controllers;


use Model\Producto;
use Model\Proveedor;

class ApiProductos
{
    public static function crear()
    {

        $producto = new Producto($_POST);
        $producto->formatearDatosFloat();
        // if($producto->codigo == ""){
        //     $producto->codigo = null;
        // }
        // $producto->ventas = 0;

        $resultado = $producto->guardar();

        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El Producto ha sido registrado exitosamente']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
        return;
    }
    public static function editar()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $producto = Producto::find($_POST['id']);
        if (!$producto) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con el Producto']);
            return;
        }
        $producto->sincronizar($_POST);

        $producto->formatearDatosFloat();



        $resultado = $producto->guardar();

        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El Producto ha sido actualizado exitosamente']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
        return;
    }
    public static function editarStock()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }

        $producto = Producto::find($_POST['id']);
        if (!$producto) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con el Producto']);
            return;
        }

        $stock_actual = $producto->stock;
        $precio_compra_actual = $producto->precio_compra;
        $stock_adquirido = $_POST['stock'];
        $precio_compra_adquirido =  floatval(str_replace(',', '', $_POST['precio_compra']));

        $stock = $stock_actual + $stock_adquirido;
        $precio_compra = ($stock_actual * $precio_compra_actual + $stock_adquirido * $precio_compra_adquirido) / $stock;
        $producto->stock = $stock;
        $producto->precio_compra = $precio_compra;
        $resultado = $producto->guardar();

        if ($resultado) {
            echo json_encode(['type' => 'success', 'msg' => 'El Producto ha sido actualizado exitosamente']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
        return;
    }
    public static function eliminar()
    {
        if (!is_auth()) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, porfavor intente nuevamente']);
            return;
        }
        $id = $_POST['id'];
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error Intenta Nuevamente']);
            return;
        }
        $producto = Producto::find($_POST['id']);
        if (!$producto) {
            echo json_encode(['type' => 'error', 'msg' => 'Hay un Problema con el producto']);
            return;
        }
        $resultado = $producto->eliminar();
        if ($resultado['status']) {
            echo json_encode(['type' => 'success', 'msg' => 'El producto ha sido Eliminado con Exito']);
            return;
        }
        echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta nuevamente']);
        return;
    }

    public static function productos()
    {
        $productos = Producto::all();
        $i = 0;
        $datoJson = '{
             "data": [';
        foreach ($productos as $key => $producto) {
            $i++;

            $acciones = "<div class='d-flex justify-content-center' >";
            $acciones .= "<button data-producto-id ='" . $producto->id . "' id='editar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Editar</span><i class='fas fa-pen'></i></button>";
            $acciones .= "<button data-producto-id ='" . $producto->id . "' id='info'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio'><span class='toolMio-text'>Ver</span><i class='fas fa-search'></i></button>";
            $acciones .= "<button data-producto-id ='" . $producto->id . "' id='eliminar'  type='button' class='btn btn-sm bg-hover-azul mx-2 text-white toolMio' ><span class='toolMio-text'>Eliminar</span><i class='fas fa-trash' ></i></button>";
            $acciones .= "</div>";



            $stock = '';
            if ($producto->stock <= $producto->stock_minimo) {
                $stock = "<div class='d-flex justify-content-center' >";
                $stock .= "<button data-producto-id ='" . $producto->id . "' id='agregar_stock'  type='button' class='btn  w-65 btn-inline btn-danger btn-sm ' style='min-width:70px'>" . $producto->stock . "</button>";
                $stock .= "</div >";
            } else {
                $stock = "<div class='d-flex justify-content-center'>";
                $stock .= "<button data-producto-id ='" . $producto->id . "' id='agregar_stock'  type='button' class='btn w-65 btn-inline bg-success text-white btn-sm' style='min-width:70px'>" . $producto->stock . "</button>";
                $stock .= "</div >";
            }


            if (!$producto->codigo) {
                $producto->codigo = "";
            }


            $datoJson .= '[
                             "' . $i . '",
                             "' . $producto->codigo . '",
                             "' . $producto->nombre . '",
                             "' . $stock . '",
                             "' . number_format($producto->precio_compra) . '",
                             "' . number_format($producto->precio_venta) . '",
                             "' . $acciones . '"
                     ]';
            if ($key != count($productos) - 1) {
                $datoJson .= ",";
            }
        }

        $datoJson .=  ']}';


        echo $datoJson;
    }

    public static function consultarProducto()
    {
        $id = $_GET['id'];
        $id = filter_var($id, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(['type' => 'error', 'msg' => 'Hubo un error, Intenta Nuevamente']);
            return;
        }

        $producto = Producto::find($id);
        echo json_encode($producto);
    }

    public static function avastecimiento()
    {
        $productos_todos = Producto::all();
        $productos = array_filter($productos_todos, function($producto){
            if($producto->stock <= $producto->stock_minimo ){
                return $producto;
            }
      
        });
        
        $i=0;
        $datoJson = '{
         "data": [';
             foreach($productos as $producto){
                $i++;

                $proveedor = Proveedor::find($producto->proveedor_id);

                $stock = "<div class='d-flex justify-content-center' >";
                $stock .= "<button data-producto-id ='" . $producto->id . "' id='agregar_stock'  type='button' class='btn  w-65 btn-inline btn-danger btn-sm ' style='min-width:70px'>" . $producto->stock . "</button>";
                $stock .= "</div >";

                 

               
                 
                 $datoJson.= '[
                        "'.$i.'",
                        "'.$producto->nombre.'",
                        "'.$stock.'",
                      
                      
                        "'.$producto->stock_minimo.'",
                        "'.number_format($producto->precio_compra).'",
                        "'.$proveedor->nombre.'",
                        "'.$proveedor->celular.'"
                 ]';
                 if($i!= count($productos)){
                     $datoJson.=",";
                 }
             }


     
    
         $datoJson.=  ']}';
        echo $datoJson;
    }
}

// array(2) {
//     [0]=>
//     object(Model\Producto)#28 (11) {
//       ["id"]=>
//       string(2) "20"
//       ["nombre"]=>
//       string(8) "BUCHANAS"
//       ["codigo"]=>
//       string(6) "522001"
//       ["stock"]=>
//       string(1) "0"
//       ["stock_minimo"]=>
//       string(2) "20"
//       ["precio_compra"]=>
//       string(9) "120000.00"
//       ["precio_venta"]=>
//       string(9) "170000.00"
//       ["porcentaje_venta"]=>
//       string(6) "141.67"
//       ["ventas"]=>
//       string(2) "30"
//       ["categoria_id"]=>
//       string(2) "32"
//       ["proveedor_id"]=>
//       string(1) "7"
//     }
//     [2]=>
//     object(Model\Producto)#21 (11) {
//       ["id"]=>
//       string(2) "14"
//       ["nombre"]=>
//       string(9) "WINDERMAN"
//       ["codigo"]=>
//       string(7) "2503350"
//       ["stock"]=>
//       string(1) "6"
//       ["stock_minimo"]=>
//       string(2) "20"
//       ["precio_compra"]=>
//       string(8) "12000.00"
//       ["precio_venta"]=>
//       string(8) "15000.00"
//       ["porcentaje_venta"]=>
//       string(6) "125.00"
//       ["ventas"]=>
//       string(2) "24"
//       ["categoria_id"]=>
//       string(2) "29"
//       ["proveedor_id"]=>
//       string(1) "9"
//     }
//   }
