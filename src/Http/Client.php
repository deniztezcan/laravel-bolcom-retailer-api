<?php

namespace DenizTezcan\BolRetailerV3\Http;

use DenizTezcan\BolRetailerV3\Support\Serialize;
use DenizTezcan\BolRetailerV3\Models\Auth;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Response;
use Carbon\Carbon;
use Exception;

class Client extends AbstractClient
{
	private $client_id 		= '';
    private $client_secret 	= '';

    private $expiration;
    private $access_token 	= '';
    private $token_type 	= 'Bearer';
    private $scope 			= 'RETAILER';

    private $token_base 	= 'https://login.bol.com/token';
    private $api_base 		= 'https://api.bol.com/';
    private $demo			= false;

	public function __construct(
		string $client_id, 
		string $client_secret
	){
		parent::__construct();
		$this->setClientId($client_id);
		$this->setClientSecret($client_secret);
	}

	public function authenticate(): void{
		try {
			$response = $this->client->request('POST', $this->token_base, [
                'form_params' => array(
                    'client_id' => $this->getClientId(),
                    'client_secret' => $this->getClientSecret(),
                    'grant_type' => 'client_credentials',
                )
            ]);
		} catch(ClientException $e) {
			throw new Exception('Client not authorized.');
		}

		$deserialized = Serialize::deserialize((string)$response->getBody());
        $model = Auth::fromResponse($deserialized);

        $this->access_token = $model->access_token;
        $this->scope = $model->scope;
        $this->token_type = $model->token_type;
        $this->expiration = Carbon::now()->addSeconds($model->expires_in);
	}

	public function authenticatedRequest(
		string $method, 
		string $endpoint, 
		array $parameters = [], 
		array $headers = []
	):Response
	{
		if ($this->isAuthenticated()) $this->authenticate();

		$parameters = array_filter($parameters);
        $headers['Authorization'] = "{$this->token_type} {$this->access_token}";

        switch ($method) {
        	case 'GET':
                return $this->get($this->getApiURL($endpoint), $parameters, $headers);
                break;
            case 'POST':
                return $this->post(
                    $this->getApiURL($endpoint),
                    $parameters,
                    array_merge(
                        array('Content-Type' => 'application/vnd.retailer.v3+json'),
                        $headers
                    )
                );
                break;
            case 'PUT':
                return $this->put(
                    $this->getApiURL($endpoint),
                    $parameters,
                    array_merge(
                        array('Content-Type' => 'application/vnd.retailer.v3+json'),
                        $headers
                    ),
                );
                break;
        }
	}

	public function isAuthenticated(): bool
    {
        return $this->access_token != '' && Carbon::now()->isBefore($this->expiration);
    }

    public function setDemoMode(bool $is_demo):void
    {
    	$this->demo = $is_demo;
    }

    public function getDemoMode(): bool
    {
    	return $this->demo;
    }

	public function setClientId($client_id): void
	{
		$this->client_id = $client_id;
	}

	public function getClientId(): string
	{
		return $this->client_id;
	}

	public function setClientSecret($client_secret): void
	{
		$this->client_secret = $client_secret;
	}

	public function getClientSecret(): string
	{
		return $this->client_secret;
	}

	public function getApiURL($endpoint): string{
		if ($this->getDemoMode()) {
			return $this->api_base . 'retailer-demo/' . $endpoint;
		}

		return $this->api_base . 'retailer/' . $endpoint;
	}
}