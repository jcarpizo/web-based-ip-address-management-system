<?php declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Http\Requests\IpAddressRequest;
use App\Interfaces\IpAddressInterface;
use App\Interfaces\IpAddressLogsInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class IpAddressController extends Controller
{
    /**
     * @var IpAddressInterface
     */
    private IpAddressInterface $ipService;

    /**
     * @var IpAddressLogsInterface
     */
    private IpAddressLogsInterface $ipLogsService;

    /**
     * @param IpAddressInterface $ipService
     * @param IpAddressLogsInterface $ipLogsService
     */
    public function __construct(IpAddressInterface $ipService, IpAddressLogsInterface $ipLogsService) {
        $this->middleware('ip.apikey');
        $this->ipService = $ipService;
        $this->ipLogsService = $ipLogsService;

    }

    /**
     * @param IpAddressRequest $request
     * @return JsonResponse
     */
    public function ipCreate(IPAddressRequest $request): JsonResponse
    {
        $ipAddress = $this->ipService->create($request->toArray());
        return response()->json(
            [
                'success' => true,
                'ip_address' => $ipAddress,
                'message' => 'IP Address successfully created'
            ]);
    }

    /**
     * @param int $id
     * @param IpAddressRequest $request
     * @return JsonResponse
     */
    public function ipUpdate(int $id, IpAddressRequest $request): JsonResponse
    {
        try {
            $ipAddress = $this->ipService->update($id, $request->toArray());
            return response()->json(
                [
                    'success' => true,
                    'ip_address' => $ipAddress,
                    'message' => 'IP Address successfully updated'
                ]);
        } catch (Throwable $exception) {
            return $this->ipErrorResponse($exception);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function ipDelete(int $id): JsonResponse
    {
        try {
            $ipAddress = $this->ipService->delete($id);
            return response()->json(
                [
                    'success' => true,
                    'ip_address' => $ipAddress,
                    'message' => 'IP Address successfully deleted'
                ]);
        } catch (Throwable $exception) {
            return $this->ipErrorResponse($exception);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function ipGet(int $id): JsonResponse
    {
        try {
            $ipAddress = $this->ipService->find($id);
            return response()->json(
                [
                    'success' => true,
                    'ip_address' => $ipAddress,
                    'message' => 'IP Addresses successfully retrieved'
                ]);
        } catch (Throwable $exception) {
            return $this->ipErrorResponse($exception);
        }
    }

    /**
     * @param int|null $currentUserId
     * @return JsonResponse
     */
    public function ipList(?int $currentUserId = null): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'ip_address' => $this->ipService->all($currentUserId),
                'message' => 'IP Addresses successfully retrieved'
            ]);
    }

    /**
     * @return JsonResponse
     */
    public function ipLogList(): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'ip_address_logs' => $this->ipLogsService->all(),
                'message' => 'IP Addresses Logs successfully retrieved'
            ]);
    }

    /**
     * @param Throwable $exception
     * @return JsonResponse
     */
    public function ipErrorResponse(Throwable $exception): JsonResponse
    {
        return response()->json(
            [
                'success' => false,
                'message' => $exception->getMessage(),
            ]
        );
    }
}
