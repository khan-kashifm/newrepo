<?php

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobsController;
use App\Http\Controllers\FormControlller;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\admin\JobController;
use App\Http\Controllers\admin\UserController;

Route::post('/processRegistration',[AccountController::class,'processRegistration'])->withoutMiddleware('auth:api');
Route::post('/authenticate', [AccountController::class, 'authenticate'])->name("account.auth");
Route::get('/index', [JobsController::class, 'index']);
Route::get('/detail/{id}', [JobsController::class, 'detail'])->withoutMiddleware('auth:api');
Route::get('/user/{id}', [JobsController::class, 'detail'])->withoutMiddleware('auth:api');

Route::get('/show', [UserController::class,'show'])->name("show");

Route::get('/edit/{id}', [UserController::class, 'edit']);

Route::post('validate-token',  [UserController::class, 'validateToken']);


// Route::post('/logout', [AccountController::class, 'logout']);

Route::delete("/delete-user/{id}", [UserController::class, "deleteUser"])->name("admin.deleteUser");

Route::post("/deleteJob/{id}", [JobController::class, 'deleteJob']);

Route::post('/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.updateUser');

Route::post('/submit-data', [FormControlller::class, 'store']);




Route::middleware('auth:api')->group(function(){

    // Route::get('/show', [UserController::class,'show'])->name("show");

    Route::get('/user/{id}', [AccountController::class, 'getname']);

    Route::post('/logout', [AccountController::class, 'logout']);
    
  

    Route::post('/applyJob', [JobsController::class, 'applyJob']);

    Route::post('/saveJob', [AccountController::class, 'saveJob']);

    Route::post('/updateJob/{id}', [AccountController::class, 'updateJob']);

    // Route::post('/update-user/{id}', [UserController::class, 'updateUser'])->name('admin.updateUser');

    
    
    Route::post('/updatePassword', [AccountController::class, 'updatePassword']);



});

