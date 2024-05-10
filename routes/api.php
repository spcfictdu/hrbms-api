<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    Room\RoomTypeController
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'user'
], function ($route) {
    $route->post('/login', [AuthController::class, 'login']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'user'
], function ($route) {
    $route->post('/register', [AuthController::class, 'register']);
    $route->get('/logout', [AuthController::class, 'logout']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'room-type'
], function ($route) {
    $route->get('/', [RoomTypeController::class, 'index']);
    $route->post('/create', [RoomTypeController::class, 'create']);
    $route->get('/{referenceNumber}', [RoomTypeController::class, 'show']);
    $route->put('/update/{referenceNumber}', [RoomTypeController::class, 'update']);
});
