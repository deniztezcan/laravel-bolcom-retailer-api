<?php

namespace DenizTezcan\BolRetailer\Models;

class Order extends BaseModel
{
    public $orderId;
    public $orderPlacedDateTime;
    public $orderItems;

    public function validate(): void
    {
        $this->assertType($this->orderId, 'string');
        $this->assertType($this->orderPlacedDateTime, 'string');
        $this->assertType($this->orderItems, 'array');
    }
}
