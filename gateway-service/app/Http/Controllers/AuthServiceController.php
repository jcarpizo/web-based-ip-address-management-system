<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GatewayRequest;
use App\Interface\GatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AuthServiceController extends Controller
{
    /**
     * @var GatewayInterface
     */
    private GatewayInterface $gatewayService;

    /**
     * @param GatewayInterface $gatewayService
     */
    public function __construct(GatewayInterface $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * @param GatewayRequest $gatewayRequest
     * @return JsonResponse
     */
    public function authLogin(GatewayRequest $gatewayRequest): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/auth/login', [
            'email' => $gatewayRequest->email ?? null,
            'password' => $gatewayRequest->password ?? null,
        ]);
    }

    /**
     * @param GatewayRequest $gatewayRequest
     * @return JsonResponse
     */
    public function authRegister(GatewayRequest $gatewayRequest): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/auth/register', [
            'email' => $gatewayRequest->email ?? null,
            'password' => $gatewayRequest->password ?? null,
            'name' => $gatewayRequest->name ?? null,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authVerifyToken(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl() . '/api/auth/verify',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authRefreshToken(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl()  . '/api/auth/refresh',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authLogout(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST', $this->getServiceUrl() . '/api/auth/logout',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function authUserLogs(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET', $this->getServiceUrl() . '/api/auth/logs',[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function authUserById(Request $request, int $userId): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET', $this->getServiceUrl() . '/api/auth/user/'.$userId,[],[
            'Authorization' => 'Bearer ' . $request->bearerToken(),
        ]);
    }

    /**
     * @return string
     */
    private function getServiceUrl(): string
    {
        return Config::get('services.auth.url');
    }
}
