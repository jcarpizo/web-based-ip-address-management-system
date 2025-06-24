<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Interface\GatewayInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class IpAddressServiceController extends Controller
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
        $this->middleware('ip.auth');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ipCreate(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('POST',   $this->getServiceUrl() . '/api/ip/create', $request->toArray(),[
            'X-API-KEY' => $request->header('X-API-KEY'),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function ipGet(Request $request, int $id): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET',   $this->getServiceUrl() . '/api/ip/get/'. $id, [], [
            'X-API-KEY' => $request->header('X-API-KEY'),
        ]);
    }

    /**
     * @param Request $request
     * @param int|null $userId
     * @return JsonResponse
     */
    public function ipList(Request $request, ?int $userId = null): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET', $this->getServiceUrl() . '/api/ip/list/'. $userId, [], [
            'X-API-KEY' => $request->header('X-API-KEY'),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function ipUpdate(Request $request, int $id): JsonResponse
    {
        return $this->gatewayService->serverRequest('PUT', $this->getServiceUrl()  . '/api/ip/update/'. $id, $request->toArray(), [
            'X-API-KEY' => $request->header('X-API-KEY'),
        ]);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function ipDelete(Request $request, int $id): JsonResponse
    {
        return $this->gatewayService->serverRequest('DELETE', $this->getServiceUrl() . '/api/ip/delete/'. $id, [], [
            'X-API-KEY' => $request->header('X-API-KEY'),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function ipLogs(Request $request): JsonResponse
    {
        return $this->gatewayService->serverRequest('GET', $this->getServiceUrl() . '/api/ip/logs', [], [
            'X-API-KEY' => $request->header('X-API-KEY'),
        ]);
    }

    /**
     * @return string
     */
    private function getServiceUrl(): string
    {
        return Config::get('services.ip.url');
    }
}
