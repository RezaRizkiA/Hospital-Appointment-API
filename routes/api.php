<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\HospitalSpecialistController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\TransactionController;
use App\Models\HospitalSpecialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('specialists', SpecialistController::class);
Route::apiResource('hospitals', HospitalController::class);
Route::apiResource('doctors', DoctorController::class);

Route::post('hospitals/{hospital}/specialist', [HospitalSpecialistController::class, 'attach']);
Route::delete('hospitals/{hospital}/specialist/{specialist}', [HospitalSpecialistController::class, 'detach']);

Route::apiResource('transactions', TransactionController::class);
Route::patch('transactions/{transaction}/status', [TransactionController::class, 'updateStatus']);
    
