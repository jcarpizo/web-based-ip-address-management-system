<?php declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Models\IpAddressAppKey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpManagementServiceApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientKey = $request->header('X-API-KEY');
        if (!$clientKey || !IpAddressAppKey::where('key', $clientKey)->exists()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $next($request);
    }

}
