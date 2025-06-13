<?php

namespace App\Interface;

use Illuminate\Http\JsonResponse;

interface GatewayInterface
{
    public function serverRequest(string $method, string $serverUrl, array $data = [], array $headers = []): JsonResponse;
}
