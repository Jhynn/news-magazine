<?php

use App\Http\Controllers\Api\{
    ArticleController,
    AuthController,
    PermissionController,
    RoleController,
    TopicController,
    UserController
};
use App\Http\Controllers\MediaController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong',
    ]);
});

Route::post('/auth/login', [AuthController::class, 'store']);

Route::middleware('auth:sanctum')->group(function() {
    Route::group([
        'prefix' => 'auth',
        'controller' => AuthController::class,
    ], function () {
        Route::get('/', 'index');
        Route::delete('/logout', 'destroy');
        Route::delete('/revoke-all-tokens', 'revokeAllMyTokens');
    });

    Route::apiResource('/topics', TopicController::class);
    Route::apiResource('/articles', ArticleController::class)->withTrashed(['show', 'update']);

    Route::get('/users/me', [UserController::class, 'me']);
    Route::apiResource('/users', UserController::class);

    Route::apiResource('/roles', RoleController::class);
    Route::apiResource('/permissions', PermissionController::class);
    Route::apiResource('/medias', MediaController::class)->except('update');
    Route::post('/medias/{media}', [MediaController::class, 'update']);
});
