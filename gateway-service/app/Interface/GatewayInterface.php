<?php

namespace App\Interface;

use Illuminate\Http\JsonResponse;

interface GatewayInterface
{
    /**
     * @param string $method
     * @param string $serverUrl
     * @param array $data
     * @param array $headers
     * @return JsonResponse
     */
    public function serverRequest(string $method, string $serverUrl, array $data = [], array $headers = []): JsonResponse;
}
