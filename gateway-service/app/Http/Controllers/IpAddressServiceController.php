<?php

namespace App\Http\Controllers;

use App\Interface\GatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class IpAddressServiceController extends Controller
{
    private GatewayInterface $gatewayService;
    public function __construct(GatewayInterface $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function ipCreate(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/ip/create', $request->toArray());
    }

    public function ipGet(int $id): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET',   $this->getServiceUrl() . '/api/ip/get/'. $id);
    }

    public function ipList(?int $userId = null): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET', $this->getServiceUrl() . '/api/ip/list/'. $userId);
    }

    public function ipUpdate(int $id, Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('PUT', $this->getServiceUrl()  . '/api/ip/update/'. $id, $request->toArray());
    }

    public function ipDelete(int $id): JsonResponse
    {
        return $this->gatewayService->serverRequest('DELETE', $this->getServiceUrl() . '/api/ip/delete/'. $id);
    }

    public function ipLogs(): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET', $this->getServiceUrl() . '/api/ip/logs');
    }

    private function getServiceUrl(): string
    {
        return Config::get('services.ip.url');
    }
}
