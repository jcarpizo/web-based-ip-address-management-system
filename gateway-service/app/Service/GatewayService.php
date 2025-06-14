<?php

namespace App\Service;

use App\Interface\GatewayInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class GatewayService implements GatewayInterface
{
    public function serverRequest(string $method, string $serverUrl, array $data = [], array $headers = []): JsonResponse
    {
        try {
            $client = new Client();
            $response = $client->request($method, $serverUrl, [
                'connect_timeout' => 5,
                'headers' => array_merge($headers, [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Accept-Encoding' => 'gzip',
                    'Accept-Charset' => 'utf-8',
                ]),
                'allow_redirects' => true,
                'query' => $data,
            ]);
            return response()->json(json_decode($response->getBody()->getContents(), true));
        } catch (ClientException $clientException) {
            return response()->json(json_decode($clientException->getResponse()->getBody()->getContents(), true));
        } catch (GuzzleException $guzzleException) {
            return response()->json(json_decode($guzzleException->getMessage()), true);
        }
    }
}
