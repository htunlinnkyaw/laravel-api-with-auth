<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/item/search', [ItemController::class, 'search'])->middleware('auth:sanctum');
Route::apiResource('item', ItemController::class)->middleware('auth:sanctum');

Route::get('/category/search', [CategoryController::class, 'search'])->middleware('auth:sanctum');
Route::apiResource('category', CategoryController::class)->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
