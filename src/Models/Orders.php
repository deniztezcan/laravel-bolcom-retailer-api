<?php

namespace DenizTezcan\BolRetailer\Models;

class Orders extends BaseModel
{
    public $orders;

    public function validate(): void
    {
        $this->assertType($this->orders, 'array');
    }
}
