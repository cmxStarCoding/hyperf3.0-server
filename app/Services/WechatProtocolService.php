<?php

declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Client;

class WechatProtocolService{


    private Client $client;
    private string $route;
    private array $requestParams;
    private string $responseData;
    private string $routePrefix = '/api';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env("BASE_PROTOCOL_URL")
        ]);
    }

    public function setRoute($route)
    {
        $this->route = $this->routePrefix.$route;
        return $this;
    }

    public function setRequestParams($requestParams)
    {
        $this->requestParams = $requestParams;
        return $this;
    }

    public function doRequest()
    {
        $response = $this->client->post($this->route,[
            'json' => $this->requestParams,
        ]);
        $this->responseData = $response->getBody()->getContents();
        return $this;
    }

    public function getResponseData(){
        return $this->responseData;
    }
}

