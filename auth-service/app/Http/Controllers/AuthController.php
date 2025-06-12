<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Interfaces\AuthInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthInterface $authService;

    /**
     * @param AuthInterface $authService
     */
    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param AuthRegisterRequest $request
     * @return JsonResponse
     */
    public function register(AuthRegisterRequest $request): JsonResponse
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
    public function login(AuthLoginRequest $request): JsonResponse
    {
        if (!$token = auth('api')->attempt($request->toArray())) {
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
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @return JsonResponse
     */
    public function verify(): JsonResponse
    {
        return response()->json(auth()->user());
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
