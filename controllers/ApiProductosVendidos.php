<?php

namespace Controllers;

use DateTime;
use Model\Producto;
use Model\Venta;
use Model\ProductosVenta;

class ApiProductosVendidos
{
    public static function productosVendidos()
{
    // Obtener las fechas de los parámetros GET
    $fecha_inicial = $_GET['fecha-inicial'];
    $fecha_final = $_GET['fecha-final'];

    // Convertir las fechas a objetos DateTime
    $fecha_inicial_dt = new DateTime($fecha_inicial);
    $fecha_final_dt = new DateTime($fecha_final);

    // Obtener todos los productos vendidos
    $productos_ventas = ProductosVenta::all();

    // Array para almacenar los productos vendidos dentro del rango de fechas
    $productos_vendidos = [];

    // Filtrar productos vendidos dentro del rango de fechas
    foreach ($productos_ventas as $producto) {
        // Obtener la venta asociada al producto
        $venta = Venta::find($producto->venta_id);

        // Separar la fecha y la hora de la venta
        $fecha = explode(" ", $venta->fecha);

        // Convertir la fecha de la venta a objeto DateTime
        $fecha_dt = new DateTime($fecha[0]);

        // Verificar si la fecha de la venta está dentro del rango
        if ($fecha_dt >= $fecha_inicial_dt && $fecha_dt <= $fecha_final_dt) {
            $producto->fecha = $fecha[0]; // Agregar la fecha al producto
            $productos_vendidos[] = $producto; // Agregar el producto a la lista
        }
    }

    // Array para almacenar los datos de los productos vendidos
    $data = [];

    // Recorrer los productos vendidos filtrados
    foreach ($productos_vendidos as $producto) {
        // Obtener la información del producto
        $info_producto = Producto::find($producto->producto_id);

        // Calcular el total (precio * cantidad)
        $total = $producto->precio * $producto->cantidad;

        // Agregar los datos del producto vendido al array
        $data[] = [
            count($data) + 1, // Índice (empezando desde 1)
            $info_producto->id, // ID del producto
            $info_producto->codigo, // Código del producto
            $info_producto->nombre, // Nombre del producto
            $producto->cantidad, // Cantidad vendida
            '$'.number_format($producto->precio), // Precio unitario
            '$'.number_format($total) // Total (precio * cantidad)
        ];
    }

    // Generar el JSON final
    $datoJson = json_encode(["data" => $data], JSON_UNESCAPED_SLASHES);

    // Imprimir el JSON
    echo $datoJson;
}
}
