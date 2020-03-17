<?php

namespace DenizTezcan\BolRetailerV3\Models;

class Orders extends BaseModel
{
	public $orders;

    public function validate(): void
    {
        $this->assertType($this->orders, 'array');
    }
}