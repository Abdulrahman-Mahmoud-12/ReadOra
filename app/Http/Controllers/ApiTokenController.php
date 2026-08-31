<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
        ]);

        $plainTextToken = 'readora_'.Str::random(40);
        $expiresAt = now()->addMonths(6);

        $request->user()->apiTokens()->create([
            'name' => $validated['name'],
            'token_hash' => hash('sha256', $plainTextToken),
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'token' => $plainTextToken,
            'expires_at' => $expiresAt->toIso8601String(),
            'message' => 'Store this token securely. It will not be shown again.',
        ], 201);
    }

    public function destroy(Request $request): JsonResponse
    {
        $plainTextToken = $request->bearerToken();

        if ($plainTextToken) {
            $request->user()->apiTokens()
                ->where('token_hash', hash('sha256', $plainTextToken))
                ->delete();
        }

        return response()->json(['message' => 'API token revoked.']);
    }
}
