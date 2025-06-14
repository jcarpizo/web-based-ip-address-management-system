<?php

namespace App\Http\Controllers;

use App\Http\Requests\GatewayRequest;
use App\Interface\GatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthServiceController extends Controller
{
    private GatewayInterface $gatewayService;
    public function __construct(GatewayInterface $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    public function authLogin(GatewayRequest $gatewayRequest): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/auth/login', [
            'email' => $gatewayRequest->email ?? null,
            'password' => $gatewayRequest->password ?? null,
        ]);
    }

    public function authRegister(GatewayRequest $gatewayRequest): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/auth/register', [
            'email' => $gatewayRequest->email ?? null,
            'password' => $gatewayRequest->password ?? null,
            'name' => $gatewayRequest->name ?? null,
        ]);
    }

    public function authVerifyToken(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl() . '/api/auth/verify',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    public function authRefreshToken(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl()  . '/api/auth/refresh',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    public function authLogout(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl() . '/api/auth/logout',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    private function getServiceUrl(): string
    {
        return Config::get('services.auth.url');
    }
}
