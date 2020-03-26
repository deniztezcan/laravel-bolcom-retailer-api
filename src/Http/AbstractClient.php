<?php

namespace DenizTezcan\BolRetailerV3\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Guzzle\Http\Exception\ClientErrorResponseException;

class AbstractClient
{
    public $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    private function getDefaultHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.retailer.v3+json',
        ];
    }

    public function get(
        string $url,
        array $query = [],
        array $headers = []
    ): Response {
        return $this->client->request('GET', $url, [
            'query'   => $query,
            'headers' => array_merge(
                $this->getDefaultHeaders(),
                $headers,
            ),
        ]);
    }

    public function post(
        string $url,
        array $parameters = [],
        array $headers = []
    ): Response {
        return $this->client->request('POST', $url, [
            'body'    => json_encode($parameters),
            'headers' => array_merge(
                $this->getDefaultHeaders(),
                $headers,
            ),
        ]);
    }

    public function put(
        string $url,
        array $parameters = [],
        array $headers = []
    ): Response {
        return $this->client->request('PUT', $url, [
            'body'    => json_encode($parameters),
            'headers' => array_merge(
                $headers,
                $this->getDefaultHeaders(),
            ),
        ]);
    }
}
