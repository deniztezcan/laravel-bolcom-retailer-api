<?php

namespace DenizTezcan\BolRetailer\Models;

class Offer extends BaseModel
{
    public $offerId;
    public $ean;
    public $reference;
    public $onHoldByRetailer;
    public $unknownProductTitle;
    public $pricing;
    public $stock;
    public $condition;
    public $notPublishableReasons;
    public $processStatusId;

    public function validate(): void
    {
        $this->assertType($this->offerId, 'string');
        $this->assertType($this->ean, 'string');
        $this->assertType($this->reference, 'string');
        $this->assertType($this->onHoldByRetailer, 'boolean');
        $this->assertType($this->unknownProductTitle, 'string');
        $this->assertType($this->pricing, 'object');
        $this->assertType($this->stock, 'object');
        $this->assertType($this->condition, 'object');
        $this->assertType($this->notPublishableReasons, 'array');
    }
}
