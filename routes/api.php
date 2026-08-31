<?php

use App\Http\Controllers\ApiTokenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.token', 'throttle:api'])->group(function () {
    Route::get('/me', function (Request $request) {
        return response()->json([
            'id' => $request->user()->id,
            'name' => $request->user()->name,
            'email' => $request->user()->email,
            'role' => $request->user()->role,
        ]);
    })->name('api.me');

    Route::delete('/tokens/current', [ApiTokenController::class, 'destroy'])
        ->name('api.tokens.destroy');
});
