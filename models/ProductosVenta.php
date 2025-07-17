<?php

namespace Model;

class  ProductosVenta extends ActiveRecord {
    protected static $tabla = 'productos_venta';
    protected static $columnasDB = ['id', 'cantidad','precio','precio_factura', 'producto_id', 'venta_id'];

    public $id;
    public $cantidad;
    public $precio;
    public $precio_factura;
    public $producto_id;
    public $venta_id;




 
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->cantidad = $args['cantidad'] ?? '';
        $this->precio = $args['precio'] ?? ''; //este es el precio de venta con el que el usuario registro la venta
        $this->precio_factura = $args['precio_factura'] ?? ''; //este es el precio al que el usuario vendio el producto ya que puede hacer una rebaja al momento de venderlo
        $this->producto_id = $args['producto_id'] ?? '';
        $this->venta_id = $args['venta_id'] ?? '';
      
    }


}