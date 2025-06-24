<?php

namespace App\Http\Middleware;

use App\Http\Controllers\AuthServiceController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IPAuthTokenService
{
    protected AuthServiceController $authService;

    public function __construct(AuthServiceController $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $auth = $this->authService->authVerifyToken($request);
        $authResponse = json_decode($auth->getContent(), true);

        if (isset($authResponse['success']) && $authResponse['success'] === false) {
            return response()->json([
                'success' => false,
                'message' => $authResponse['message'] ?? 'Unauthorized',
            ], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
