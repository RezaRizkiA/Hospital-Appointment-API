<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\HospitalSpecialistController;
use App\Http\Controllers\MyOrderController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('token-login', [AuthController::class, 'tokenLogin']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);
});

Route::apiResource('specialists', SpecialistController::class);
Route::apiResource('hospitals', HospitalController::class);
Route::apiResource('doctors', DoctorController::class);

Route::post('hospitals/{hospital}/specialists', [HospitalSpecialistController::class, 'attach']);
Route::delete('hospitals/{hospital}/specialists/{specialist}', [HospitalSpecialistController::class, 'detach']);

Route::apiResource('transactions', TransactionController::class);
Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus']);
    
Route::get('/doctors-filter', [DoctorController::class, 'filterBySpecialistAndHospital']);
Route::get('/doctors/{doctorId}/available-slots', [DoctorController::class, 'availableSlots']);

Route::get('my-orders', [MyOrderController::class, 'index']);
Route::post('my-orders', [MyOrderController::class, 'store']);
Route::get('my-orders/{id}', [MyOrderController::class, 'show']);