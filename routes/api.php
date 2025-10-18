<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    Room\RoomTypeController,
    Room\RoomController,
    Room\RoomTypeRateController,
    Room\OccupiedRoomController,
    Room\RoomStatusController,
    Transaction\TransactionController,
    AvailabilityCalendar\AvailabilityCalendarController,
    Amenity\AmenityController,
    DataResetController,
    Enum\EnumController,
    Guest\GuestController,
    ReportGeneration\ReportGenerationController,
    Addon\AddonController,
    Discount\DiscountController,
    CashierSession\CashierSessionController,
    Voucher\VoucherController,
    CashierSession\UserCashierController,
    TestController
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

Route::apiResource('test', TestController::class);

Route::group([
    'prefix' => 'user'
], function ($route) {
    $route->post('/admin/login',  [AuthController::class, 'login'])->middleware('throttle:login');
    $route->post('/guest/login',  [AuthController::class, 'guestLogin'])->middleware('throttle:login');;
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'user'
], function ($route) {
    $route->get('/logout', [AuthController::class, 'logout']);
});

Route::post('user/register', [AuthController::class, 'register']);


Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'room-type'
], function ($route) {
    $route->group([
        'prefix' => 'rate'
    ], function ($route) {
        $route->get('/', [RoomTypeRateController::class, 'index']);
        $route->get('/{roomTypeReferenceNumber}', [RoomTypeRateController::class, 'show']);
        $route->put('/regular/update/{referenceNumber}', [RoomTypeRateController::class, 'updateRegular']);
        $route->post('/special/create', [RoomTypeRateController::class, 'createSpecial']);
        $route->get('/special/archived/{roomTypeReferenceNumber}', [RoomTypeRateController::class, 'archivedSpecial']);
        $route->put('/special/update/{referenceNumber}', [RoomTypeRateController::class, 'updateSpecial']);
        $route->delete('/special/archive/{referenceNumber}', [RoomTypeRateController::class, 'archiveSpecial']);
        $route->patch('/special/restore/{referenceNumber}', [RoomTypeRateController::class, 'restoreSpecial']);
    });
    $route->get('/', [RoomTypeController::class, 'index']);
    $route->post('/create', [RoomTypeController::class, 'create']);
    $route->get('/{referenceNumber}', [RoomTypeController::class, 'show']);
    $route->post('/update/{referenceNumber}', [RoomTypeController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [RoomTypeController::class, 'delete']);
});

Route::get('/room', [RoomController::class, 'index']);

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'room'
], function ($route) {
    $route->post('/create', [RoomController::class, 'create']);
    $route->put('/update/{referenceNumber}', [RoomController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [RoomController::class, 'delete']);
});


// Public guest hotel room
Route::group([
    // 'middleware' => 'auth:sanctum',
    'prefix' => 'hotel-room'
], function ($route) {
    $route->get('/', [RoomController::class, 'hotelRoom']);
    $route->get('/search', [RoomController::class, 'searchHotelRoom']);
    $route->get('/{referenceNumber}', [RoomController::class, 'roomInfo']);
});

Route::get('/room/{referenceNumber}', [RoomController::class, 'show']);


Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'room-status'
], function ($route) {
    $route->get('/', [RoomStatusController::class, 'index']);
    $route->get('/{referenceNumber}', [RoomStatusController::class, 'show']);
    $route->put('/update/{referenceNumber}', [RoomStatusController::class, 'update']);
});


Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'transaction'
], function ($route) {
    // MISCELLANEOUS
    $route->get('/form/{referenceNumber}',                                      [TransactionController::class, 'showFormTransaction']);
    $route->delete('/reservation/delete/{status}/{referenceNumber}',            [TransactionController::class, 'deleteReservation']);

    // TRANSACTION
    $route->get('/',                                                            [TransactionController::class, 'index']);
    // Route::get('/', [TransactionController::class, 'guestIndex']);

    $route->get('/show/{referenceNumber}',                                      [TransactionController::class, 'show']);
    $route->put('/update',                                                      [TransactionController::class, 'update']);
});



// PUBLIC
Route::get('/availability-calendar', [AvailabilityCalendarController::class, 'index']);

Route::post('/transaction/create',  [TransactionController::class, 'create'])->middleware('optional.auth');

// To check availability of rooms
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'availability-calendar'
], function ($route) {
    $route->post('/create', [AvailabilityCalendarController::class, 'create']);
    $route->put('/update/{referenceNumber}', [AvailabilityCalendarController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [AvailabilityCalendarController::class, 'delete']);
});

Route::get('/availability-calendar/{referenceNumber}', [AvailabilityCalendarController::class, 'show']);

Route::get('/addon', [AddonController::class, 'index']);

Route::get('/discount', [DiscountController::class, 'index']);

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'addon'
], function ($route) {
    // $route->get('/', [AddonController::class, 'index']);
    $route->post('/create', [AddonController::class, 'create']);
    $route->get('/{referenceNumber}', [AddonController::class, 'show']);
    $route->put('/update/{referenceNumber}', [AddonController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [AddonController::class, 'delete']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'voucher'
], function ($route) {
    $route->get('/', [VoucherController::class, 'index']);
    $route->post('/create', [VoucherController::class, 'create']);
    $route->get('/{referenceNumber}', [VoucherController::class, 'show']);
    $route->put('/update/{referenceNumber}', [VoucherController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [VoucherController::class, 'delete']);
});
// For enums or dropdowns
Route::group([
    // 'middleware' => 'auth:sanctum',
    'prefix' => 'enum'
], function ($route) {
    $route->get('/room', [EnumController::class, 'roomEnum']);
    $route->get('/rooms/available-options-for-adding', [EnumController::class, 'availableRoomOptionsForAddingEnum']);
    $route->get('/room-type', [EnumController::class, 'roomTypeEnum']);
    $route->get('/room-number', [EnumController::class, 'roomNumberEnum']);
    $route->get('/room-type-amenity', [EnumController::class, 'roomTypeAmenityEnum']);
    $route->get('/room-type-rate', [EnumController::class, 'roomTypeRateEnum']);
    $route->get('/guest', [EnumController::class, 'guestEnum']);
    $route->get('/guest/available-room-numbers', [EnumController::class, 'guestAvailableRoomNumbersEnum']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'guest'
], function ($route) {
    $route->group([
        'prefix' => 'hotel-room'
    ], function ($route) {
        //        $route->get('/',                                    [GuestController::class, 'indexDashboard']);
    });

    $route->group([
        'prefix' => 'account'
    ], function ($route) {
        $route->get('/',                                    [GuestController::class, 'accountDetails']);
        $route->get('/edit-details',                        [GuestController::class, 'editDetails']);
        $route->put('/update-details',                      [GuestController::class, 'updateDetails']);
        $route->put('/change-password',                     [GuestController::class, 'changePassword']);
    });

    $route->get('/', [GuestController::class, 'index']);
    $route->get('/{id}', [GuestController::class, 'show']);
    $route->delete('/delete/{id}', [GuestController::class, 'delete']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'amenity'
], function ($route) {
    $route->get('/', [AmenityController::class, 'index']);
    $route->post('/create', [AmenityController::class, 'create']);
    $route->get('/{referenceNumber}', [AmenityController::class, 'show']);
    $route->put('/update/{referenceNumber}', [AmenityController::class, 'update']);
    $route->delete('/delete/{referenceNumber}', [AmenityController::class, 'delete']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'report'
], function ($route) {
    // $route->post('/revenue', [ReportGenerationController::class, 'revenueReport']);
    // $route->post('/payment', [ReportGenerationController::class, 'paymentReport']);
    // $route->post('/check',   [ReportGenerationController::class, 'checkInOutReport']);
    // $route->get('/payment-summary',   [ReportGenerationController::class, 'paymentSummary']);
    // $route->get('/guest-history/{guest_id}',   [ReportGenerationController::class, 'guestHistory']);
    // $route->get('/top-guests',   [ReportGenerationController::class, 'topGuests']);
    // $route->get('/guest-frequency',   [ReportGenerationController::class, 'guestFrequency']);
    // $route->get('/guest-demographics',   [ReportGenerationController::class, 'guestDemographics']);
    $route->get('/flights-report', [ReportGenerationController::class, 'flightsReport']);
    $route->get('/daily-reservations',   [ReportGenerationController::class, 'dailyReservations']);
    $route->get('/room-occupancy',   [ReportGenerationController::class, 'roomOccupancy']);
    $route->get('/daily-cashier',   [ReportGenerationController::class, 'dailyCashier']);
    $route->get('/guest-billing/{referenceNumber}', [ReportGenerationController::class, 'guestBilling']);
});

Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'cashier-session'
], function ($route) {
    $route->get('/show-cashiers', [UserCashierController::class, 'showCashiers']);
    $route->post('/start/{id}', [UserCashierController::class, 'startSession']);
    $route->post('/close/{id}', [UserCashierController::class, 'closeSession']);
    $route->get('/', [CashierSessionController::class, 'index']);
    $route->get('/{id}/show-history', [UserCashierController::class, 'showHistory']);
    // $route->get('/{id}/toggle', [CashierSessionController::class, 'toggle']);
    // $route->post('/', [CashierSessionController::class, 'create']);
    // $route->get('/{id}', [CashierSessionController::class, 'show']);
    // $route->put('/{id}', [CashierSessionController::class, 'update']);
    // $route->delete('/{id}', [CashierSessionController::class, 'delete']);
});

Route::group([
    'prefix' => 'transaction',
    'middleware' => 'auth:sanctum',
], function ($route) {
    $route->post('/{referenceNumber}/flight', [TransactionController::class, 'createFlight']);
    $route->get('/{referenceNumber}/flight', [TransactionController::class, 'indexFlight']);
    $route->get('/flight/show', [TransactionController::class, 'showFlight']);
    $route->put('/flight/update', [TransactionController::class, 'updateFlight']);
    $route->delete('/flight/delete', [TransactionController::class, 'deleteFlight']);
});

Route::get('/transaction/payment/show', [TransactionController::class, 'showPayment'])->middleware('auth:sanctum');
Route::put('/transaction/folio/update', [TransactionController::class, 'updateFolio'])->middleware('auth:sanctum');
Route::get('/bank', [TransactionController::class, 'indexBank'])->middleware('auth:sanctum');

// This route has authentication check inside the controller
// When I put the middleware (auth:sanctum), the roles.guard_name would be sanctum when I migrate:fresh and seed
// Instead of web, which is the default guard_name
Route::post('admin/system/reset-data', [DataResetController::class, 'resetData']);

// Would use for later
Route::group([
    'middleware' => 'auth:system',
    'prefix' => 'admin/system'
], function ($route) {
});
