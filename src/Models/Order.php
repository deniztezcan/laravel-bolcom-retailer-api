<?php

namespace DenizTezcan\BolRetailer\Models;

class Order extends BaseModel
{
    public $orderId;
    public $dateTimeOrderPlaced;
    public $customerDetails;
    public $orderItems;

    public function validate(): void
    {
        $this->assertType($this->orderId, 'string');
        $this->assertType($this->dateTimeOrderPlaced, 'string');
        $this->assertType($this->customerDetails, 'object');
        $this->assertType($this->orderItems, 'array');
    }
}
