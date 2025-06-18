<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Interfaces\AuthInterface;
use App\Models\UserLogs;
use Illuminate\Http\JsonResponse;
use Throwable;

class AuthController extends Controller
{
    private AuthInterface $authService;

    /**
     * @param AuthInterface $authService
     */
    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
        $this->middleware('api', ['except' => ['login']]);
    }

    /**
     * @param AuthRegisterRequest $request
     * @return JsonResponse
     */
    public function authRegister(AuthRegisterRequest $request): JsonResponse
    {
        $user = $this->authService->createUser($request->toArray());
        return response()->json(
            [
                'success' => true,
                'user' => $user,
                'message' => 'User successfully registered',
            ]);
    }

    /**
     * @param AuthLoginRequest $request
     * @return JsonResponse
     */
    public function authLogin(AuthLoginRequest $request): JsonResponse
    {
        if (!$token = auth()->attempt($request->toArray())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    /**
     * @param $token
     * @return JsonResponse
     */
    private function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function authLogout(): JsonResponse
    {
        auth()->logout(true);
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @return JsonResponse
     */
    public function authVerify(): JsonResponse
    {
        $token = auth()->user();
        if (empty($token)) {
            return response()->json(
                [
                    'message' => 'token has already expired',
                    'error' => 'Unauthorized'
                ], 401);
        }
        return response()->json($token);
    }

    /**
     * @return JsonResponse
     */
    public function authRefresh(): JsonResponse
    {
        try {
            return $this->respondWithToken(auth('api')->refresh());
        } catch (Throwable) {
            return response()->json(
                [
                    'message' => 'token has already expired',
                    'error' => 'Unauthorized'
                ], 401);
        }
    }

    public function authUserLogs(): JsonResponse
    {
        $token = auth()->user();
        if (empty($token)) {
            return response()->json(
                [
                    'message' => 'token has already expired',
                    'error' => 'Unauthorized'
                ], 401);
        }
        return response()->json(
            [
                'success' => true,
                'user_logs' => UserLogs::with('user')
                    ->orderBy('updated_at', 'desc')
                    ->get(),
                'message' => 'User Logs successfully retrieved'
            ]);
    }

    public function authUserById(int $userId): JsonResponse
    {
        $token = auth()->user();
        if (empty($token)) {
            return response()->json(
                [
                    'message' => 'token has already expired',
                    'error' => 'Unauthorized'
                ], 401);
        }
        return response()->json(
            [
                'success' => true,
                'user' => $this->authService->getUserById($userId),
                'message' => 'User successfully retrieved'
            ]);
    }
}
