<?php

namespace Essa\APIToolKit\Api;

use GuzzleHttp\Client;

trait ConsumesExternalServices
{
    public function makeRequest(
        $method,
        $requestUrl,
        $queryParams = [],
        $formParams = [],
        $headers = [],
        $isJsonRequest = false,
        $decode_response = true
    )
    {
        $client = new Client([
            'base_uri' => $this->baseUri,
        ]);

        if (method_exists($this, 'resolveAuthorization') && ! $headers) {
            $this->resolveAuthorization($queryParams, $formParams, $headers);
        }

        $response = $client->request($method, $requestUrl, [
            $isJsonRequest ? 'json' : 'form_params' => $formParams,
            'headers' => $headers,
            'query' => $queryParams,
        ]);

        $response_content = $response->getBody()->getContents();

        return $decode_response ? json_decode($response_content) : $response_content;
    }
}
