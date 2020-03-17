<?php

namespace DenizTezcan\BolRetailerV3\Models;

class Commission extends BaseModel
{
    public $ean;
    public $condition;
    public $price;
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
        $this->assertType($this->totalCost, 'double');

        if (gettype($this->reductions) == 'array')
        {
            $this->reductions = Reduction::manyFromResponse($this->reductions);
        }
    }
}