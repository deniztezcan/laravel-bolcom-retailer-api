<?php

namespace DenizTezcan\BolRetailer\Models;

class Commission extends BaseModel
{
    public $ean;
    public $condition;
    public $unitPrice;
    public $fixedAmount;
    public $percentage;
    public $totalCost;
    public $totalCostWithoutReduction;
    public $reductions;

    public function validate(): void
    {
        $this->assertType($this->ean, 'string');
        $this->assertType($this->condition, 'string');
        $this->assertType($this->fixedAmount, 'double');
        $this->assertType($this->percentage, 'integer');

        if (gettype($this->reductions) == 'array') {
            $this->reductions = Reduction::manyFromResponse($this->reductions);
        }
    }
}
