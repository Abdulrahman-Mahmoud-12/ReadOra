<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $plainTextToken = $request->bearerToken();

        if (! $plainTextToken) {
            return response()->json(['message' => 'A valid bearer token is required.'], Response::HTTP_UNAUTHORIZED);
        }

        $token = ApiToken::query()
            ->where('token_hash', hash('sha256', $plainTextToken))
            ->first();

        if (! $token || $token->isExpired()) {
            return response()->json(['message' => 'The bearer token is invalid or expired.'], Response::HTTP_UNAUTHORIZED);
        }

        $token->forceFill(['last_used_at' => now()])->saveQuietly();
        $request->setUserResolver(fn () => $token->user);

        return $next($request);
    }
}
