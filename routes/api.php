<?php

use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', [UserController::class,'register'])->name('register');
Route::post('/login', [UserController::class,'login'])->name('login');
Route::get('/logout', [UserController::class,'logout'])->name('logout')->middleware('auth:sanctum');
Route::post('/change_password', [UserController::class,'changePassword'])->name('changePassword')->middleware('auth:sanctum');
Route::post('/change_name', [UserController::class,'changeName'])->name('changeName')->middleware('auth:sanctum');
Route::post('/forget_password', [UserController::class,'forgetPassword'])->name('forgetPassword');
Route::post('/reset_password', [UserController::class,'resetPassword'])->name('resetPassword');




Route::get('/task', [TaskController::class,'index'])->name('task.index');
Route::post('/task', [TaskController::class,'store'])->name('task.store')->middleware('auth:sanctum');
Route::put('/task/{id}', [TaskController::class,'update'])->name('task.update')->middleware('auth:sanctum');
Route::delete('/task/{id}', [TaskController::class,'destroy'])->name('task.delete')->middleware('auth:sanctum');
Route::get('/task/{id}', [TaskController::class,'show'])->name('task.show');


