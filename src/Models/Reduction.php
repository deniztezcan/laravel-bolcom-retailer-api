<?php

namespace DenizTezcan\BolRetailerV3\Models;

class Reduction extends BaseModel
{
    public $startDate;
    public $endDate;
    public $ean;
    public $maximumPrice;
    public $costReduction;

    public function validate(): void
    {
        $this->assertType($this->startDate, 'string');
        $this->assertType($this->endDate, 'string');
        $this->assertType($this->maximumPrice, 'float');
        $this->assertType($this->costReduction, 'float');
        $this->startDate = Carbon::parse($this->startDate);
        $this->endDate = Carbon::parse($this->endDate);
    }
}
