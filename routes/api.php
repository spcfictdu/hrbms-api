<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    Room\RoomTypeController,
    Room\RoomController,
    Transaction\TransactionController,
    AvailabilityCalendar\AvailabilityCalendarController,
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
    $route->delete('/delete/{referenceNumber}', [RoomTypeController::class, 'delete']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'room'
], function ($route) {
    $route->get('/', [RoomController::class, 'index']);
    $route->post('/create', [RoomController::class, 'create']);
    $route->get('/{referenceNumber}', [RoomController::class, 'show']);
    $route->put('/update/{referenceNumber}', [RoomController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [RoomController::class, 'delete']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'transaction'
], function ($route) {
    // $route->get('/booking',                             [TransactionController::class, 'bookIndex']);
    // $route->get('/booking/{referenceNumber}',           [TransactionController::class, 'bookShow']);
    // $route->get('/booking/edit/{referenceNumber}',      [TransactionController::class, 'bookEdit']);
    // $route->put('/booking/update',                      [TransactionController::class, 'bookUpdate']);
    $route->get('/',                                    [TransactionController::class, 'index']);
    $route->get('/show/{referenceNumber}',              [TransactionController::class, 'show']);
    $route->post('/create',                             [TransactionController::class, 'create']);
    $route->put('/update',                              [TransactionController::class, 'update']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'availability-calendar'
], function ($route) {
    $route->get('/', [AvailabilityCalendarController::class, 'index']);
    $route->post('/create', [AvailabilityCalendarController::class, 'create']);
    $route->get('/{referenceNumber}', [AvailabilityCalendarController::class, 'show']);
    $route->put('/update/{referenceNumber}', [AvailabilityCalendarController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [AvailabilityCalendarController::class, 'delete']);
});
