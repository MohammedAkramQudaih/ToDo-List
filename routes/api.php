<?php

use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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

// Route::get('user', [UserController::class,'user'])->name('register')->middleware('auth:sanctum','verified');

// User
Route::post('register', [UserController::class,'register'])->name('register');
Route::post('login', [UserController::class,'login'])->name('login');
Route::get('logout', [UserController::class,'logout'])->name('logout')->middleware('auth:sanctum');
Route::post('changeName', [UserController::class,'changeName'])->name('changeName')->middleware('auth:sanctum');

Route::post('changePassword', [UserController::class,'changePassword'])->name('changePassword')->middleware('auth:sanctum',);
Route::post('forgetPassword', [UserController::class,'forgetPassword'])->name('forgetPassword');
Route::get('resetPassword', [UserController::class,'resetPassword'])->name('password.reset');
Route::post('newPassword', [UserController::class,'newPassword'])->name('newPassword');

Route::get('sendVerificationEmail',[UserController::class,'sendVerificationEmail'])->name('sendVerificationEmail')->middleware('auth:sanctum');
Route::get('sendForgetPasswordEmail',[UserController::class,'sendForgetPasswordEmail'])->name('sendForgetPasswordEmail');
Route::get('verifyEmail',[UserController::class,'verifyEmail'])->name('verification.verify');

// Task
Route::get('tasks', [TaskController::class,'index'])->name('task.index')->middleware('auth:sanctum');
Route::post('addTask', [TaskController::class,'store'])->name('task.store')->middleware('auth:sanctum');
Route::post('updateTask/{id}', [TaskController::class,'update'])->name('task.update')->middleware('auth:sanctum');
Route::post('deleteTask/{id}', [TaskController::class,'destroy'])->name('task.delete')->middleware('auth:sanctum');




// Route::post('/forgot-password', function (Request $request) {
//     $request->validate(['email' => 'required|email']);
 
//     $status = Password::sendResetLink(
//         $request->only('email')
//     );
 
//     return $status === Password::RESET_LINK_SENT
//                 ? back()->with(['status' => __($status)])
//                 : back()->withErrors(['email' => __($status)]);
// })->middleware('guest')->name('password.email');