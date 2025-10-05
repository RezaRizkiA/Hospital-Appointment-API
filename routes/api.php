<?php

use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('specialists', SpecialistController::class);
Route::apiResource('hospitals', HospitalController::class);
Route::apiResource('doctors', DoctorController::class);
