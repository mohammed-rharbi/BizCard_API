<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CardController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::apiResource('/card' , CardController::class)->middleware('auth:sanctum');

Route::post('/update/{id}', [CardController::class , 'update'])->middleware('auth:sanctum')->name('update');
Route::delete('/delete/{id}', [CardController::class , 'delete'])->middleware('auth:sanctum')->name('delete');




Route::get('log', function(){
    return view('login');
})->name('log');


Route::get('reg', function(){
    return view('register');
})->name('reg');


