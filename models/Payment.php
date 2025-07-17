<?php

namespace Model;

class Payment extends ActiveRecord
{
    protected static $tabla = 'payments';
    protected static $columnasDB = [
        'id',
        'payment_number',
        'payment_amount',
        'remaining_balance',
        'date',
        'installment_number',
        'sale_id',
        'user_id',
        'state',
        'sale_box_id',
        'first_payment'
    ];

    public $id;
    public $payment_number;
    public $payment_amount;
    public $remaining_balance;
    public $date;
    public $installment_number; // es el numero de del pago es decir si es primera cuota segunda o tercera 
    public $sale_id;
    public $user_id;
    public $state;
    public $sale_box_id;
    public $first_payment; //este es cuando el primer pago biene junto con la venta es decirq que no es un pago sino mas bien un abono inicial

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->payment_number = $args['payment_number'] ?? '';
        $this->payment_amount = $args['payment_amount'] ?? '';
        $this->remaining_balance = $args['remaining_balance'] ?? '';
        $this->date = $args['date'] ?? '';
        $this->installment_number = $args['installment_number'] ?? 1;
        $this->sale_id = $args['sale_id'] ?? '';
        $this->user_id = $args['user_id'] ?? '';
        $this->state = $args['state'] ?? 1;
        $this->sale_box_id = $args['sale_box_id'];
        $this->first_payment = $args['first_payment']??0;
    }
}
