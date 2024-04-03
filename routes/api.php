<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\userController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// User Post Request API
Route::post('create-user', [userController::class, 'createUser']);

// now user Get Request API
Route::get('get-users', [userController::class, 'getUsers']);

// Get User Details (yahni indiviual user ka data laana hai, to hame os ki id chaiye hogi, parameter main)
Route::get('get-user-details/{id}', [userController::class, 'getUsersDetails']);

// Update users
Route::put('update-user/{id}', [userController::class, 'updateUser']);

// Delete User
Route::delete('delete-user/{id}', [userController::class, 'deleteUser']);


Route::post('login', [userController::class, 'login']);




