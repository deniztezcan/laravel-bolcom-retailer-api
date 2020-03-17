<?php

namespace DenizTezcan\BolRetailerV3\Models;

use Carbon\Carbon;

class Event extends BaseModel
{
	public $id;
	public $entityId;
	public $eventType;
	public $description;
	public $status;
	public $createTimestamp;
	public $links;

    public function validate(): void
    {
        $this->assertType($this->id, 'integer');
        $this->assertType($this->entityId, 'string');
        $this->assertType($this->eventType, 'string');
        $this->assertType($this->description, 'string');
        $this->assertType($this->status, 'string');
        $this->assertType($this->createTimestamp, 'string');
        $this->assertType($this->links, 'array');

        $this->createTimestamp = Carbon::parse($this->createTimestamp);
    }
}