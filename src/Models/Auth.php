<?php

namespace DenizTezcan\BolRetailerV3\Models;

class Auth extends BaseModel
{
	public $expires_in;
	public $access_token;
	public $scope;
	public $token_type;

    public function validate(): void
    {
        $this->assertType($this->access_token, 'string');
        $this->assertType($this->expires_in, 'integer');
        $this->assertType($this->scope, 'string');
        $this->assertType($this->token_type, 'string');
    }
}