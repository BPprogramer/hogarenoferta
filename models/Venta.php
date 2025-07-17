<?php

namespace Model;

class  Venta extends ActiveRecord {
    protected static $tabla = 'ventas';
    protected static $columnasDB = ['id', 'codigo','total','total_factura', 'recaudo', 'costo', 'descuento', 'metodo_pago', 'pagado', 'fecha', 'nombre_cliente','cedula_cliente','celular_cliente','direccion_cliente','email_cliente',  'vendedor_id', 'caja_id', 'cliente_id'];


    public $id;
    public $codigo;
    public $total;
    public $total_factura;
    public $recaudo;
    public $costo;
    public $descuento;
    public $metodo_pago; //   1 para contado 2 para credito
    public $pagado;
    public $fecha;
    public $nombre_cliente;
    public $cedula_cliente;
    public $celular_cliente;
    public $direccion_cliente;
    public $email_cliente;

    public $vendedor_id;
    public $caja_id;
    public $cliente_id; 



 
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->codigo = $args['codigo'] ?? '';
        $this->total = $args['total'] ?? '';
        $this->total_factura = $args['total_factura'] ?? '';
        $this->recaudo = $args['recaudo'] ?? '';
        $this->costo = $args['costo'] ?? '';
        $this->descuento = $args['descuento'] ?? '';
        $this->metodo_pago = $args['metodo_pago'] ?? '';
        $this->pagado = $args['pagado'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->nombre_cliente = $args['nombre_cliente'] ?? '';
        $this->cedula_cliente = $args['cedula_cliente'] ?? '';
        $this->celular_cliente = $args['celular_cliente'] ?? '';
        $this->direccion_cliente = $args['direccion_cliente'] ?? '';
        $this->email_cliente = $args['email_cliente'] ?? '';

        $this->caja_id = $args['caja_id'] ?? '';
      
    }

    public function formatearDatosFloat(){
      
        $this->recaudo = floatval(str_replace(',','',$this->recaudo));

     
     
    }



}