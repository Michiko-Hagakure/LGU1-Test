<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
|--------------------------------------------------------------------------
| Facility Reservation API
|--------------------------------------------------------------------------
| Base URL: https://facilities.local-government-unit-1-ph.com
|
| Available Endpoints:
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/facilities
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/equipment
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/check-availability
| GET  https://facilities.local-government-unit-1-ph.com/api/facility-reservation/status/{reference}
| POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation
|
| All endpoints are public - no API key required.
*/
Route::prefix('facility-reservation')->group(function () {
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/facilities
    Route::get('/facilities', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'listFacilities']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/equipment
    Route::get('/equipment', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'listEquipment']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/check-availability
    Route::get('/check-availability', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkAvailability']);
    
    // GET https://facilities.local-government-unit-1-ph.com/api/facility-reservation/status/{reference}
    Route::get('/status/{reference}', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'checkStatus']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation
    Route::post('/', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'store']);
    
    // POST https://facilities.local-government-unit-1-ph.com/api/facility-reservation/payment-complete
    Route::post('/payment-complete', [\App\Http\Controllers\Api\FacilityReservationApiController::class, 'paymentComplete']);
});

