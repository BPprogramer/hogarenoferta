<?php

namespace Model;

class  Cuota extends ActiveRecord {
    protected static $tabla = 'cuotas';
    protected static $columnasDB = ['id','numero_pago', 'monto', 'saldo','fecha_pago', 'caja_id', 'pago_cuotas_id', 'cuota_inicial'];


    public $id;
    public $monto;
    public $numero_pago;
    public $saldo;
    public $fecha_pago;
    public $caja_id;
    public $pago_cuotas_id;
 
    public $cuota_inicial;
 

 
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->monto = $args['monto'] ?? '';
        $this->numero_pago = $args['numero_pago'] ?? '';
        $this->saldo = $args['saldo'] ?? '';
        $this->fecha_pago = $args['fecha_pago'] ?? '';
        $this->caja_id = $args['caja_id'] ?? '';
        $this->pago_cuotas_id = $args['pago_cuotas_id'] ?? '';
        $this->cuota_inicial = $args['cuota_inicial'] ?? 0;
    
    }


}