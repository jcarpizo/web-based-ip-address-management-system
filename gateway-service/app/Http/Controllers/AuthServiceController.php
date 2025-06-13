<?php

namespace App\Http\Controllers;

use App\Interface\GatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;

class AuthServiceController extends Controller
{
    private GatewayInterface $gatewayService;
    public function __construct(GatewayInterface $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function login(string $username, string $password): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/auth/login', [
            'email' => $username,
            'password' => $password,
        ]);
    }

    public function logout(string $token): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl() . '/auth/logout',[],[
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    public function refreshToken(string $token): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl()  . '/auth/refresh',[],[
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    public function verifyToken(string $token): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl() . 'api/auth/verify',[],[
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    private function getServiceUrl(): string
    {
        return Config::get('services.auth.url');
    }
}
