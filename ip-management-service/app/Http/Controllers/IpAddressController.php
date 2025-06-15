<?php

namespace App\Http\Controllers;

use App\Http\Requests\IpAddressRequest;
use App\Interfaces\IpAddressInterface;
use Illuminate\Http\JsonResponse;
use Throwable;

class IpAddressController extends Controller
{
    private IpAddressInterface $ipService;

    public function __construct(IpAddressInterface $ipService) {
        $this->ipService = $ipService;
    }

    public function ipCreate(IPAddressRequest $request): JsonResponse
    {
        $ipAddress = $this->ipService->create($request->toArray());
        return response()->json(
            [
                'success' => true,
                'data' => $ipAddress,
                'message' => 'IP Address successfully created'
            ]);
    }

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
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage()
                ],422);
        }
    }

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
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage()
                ]);
        }
    }

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
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage()
                ]);
        }
    }

    public function ipList(?int $currentUserId = null): JsonResponse
    {
        return response()->json(
            [
                'success' => true,
                'ip_address' => $this->ipService->all($currentUserId),
                'message' => 'IP Addresses successfully retrieved'
            ]);
    }
}
