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

Route::post('login', [App\Http\Controllers\ApiAuthController::class, 'login']);
Route::post('register', [App\Http\Controllers\ApiAuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('logout', [App\Http\Controllers\ApiAuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('user', [App\Http\Controllers\UserController::class, 'index']);


Route::middleware('auth:sanctum')->match(['get', 'post'], 'books', [\App\Http\Controllers\BookController::class, 'handleBooks']);
