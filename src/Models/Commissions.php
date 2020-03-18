<?php

namespace DenizTezcan\BolRetailerV3\Models;

class Commissions extends BaseModel
{
    public $commissions;

    public function validate(): void
    {
        $this->assertType($this->commissions, 'array');
    }
}
