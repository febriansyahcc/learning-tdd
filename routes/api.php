<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/auth/register', [AuthController::class, 'register'] );
Route::post('/auth/login', [AuthController::class, 'login'] );

Route::middleware('auth:sanctum')->get('/protected-route', function(){
    return response()->json([
        'message' => 'You have accessed a protected route'
    ], 200);
});