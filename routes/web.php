<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\callback;


Route::get("/", [HomeController::class, "index"])->name("home");
Route::get("/welcome", [HomeController::class, "welcome"])->name("welcome");
Route::get("/jobs", [JobsController::class, "index"])->name("jobs");
Route::get("/jobs/detail/{id}", [JobsController::class, "detail"])->name("jobDetail");


// Route::post("/account/process-login",[AccountController::class,"processLogin"])->name("account.processLogin");

Route::group(['account'], function () {

    // Guest Routes
    Route::group(['middleware' => 'guest'], function () {

        Route::get("/account/register", [AccountController::class, "registration"])->name("account.registration");
        Route::post("/account/process-register", [AccountController::class, "processRegistration"])->name("account.processRegistration");
        Route::get("/account/login", [AccountController::class, "login"])->name("account.login");
        Route::post("/account/authenticate", [AccountController::class, "authenticate"])->name("account.authenticate");
        
    });

    // Authenticated Routes
    Route::group(['middleware' => 'auth'], function () {

        Route::get("/account/profile", [AccountController::class, "profile"])->name("account.profile");
        Route::get("/account/logout", [AccountController::class, "logout"])->name("account.logout");
        Route::post('/account/profile/{id}/update',[AccountController::class,'update'])->name('account.profile.update');
        Route::post('/account/update-profile-pic',[AccountController::class,'updateProfilePic'])->name('account.profilepic.update');
        Route::get("/account/create-job", [AccountController::class, "createJob"])->name("account.createJob");
        Route::post("/account/save-job", [AccountController::class, "saveJob"])->name("account.saveJob");
        Route::get("/account/my-jobs", [AccountController::class, "myJobs"])->name("account.myjobs");
        Route::get("/account/my-jobs/edit/{jobId}", [AccountController::class, "editJob"])->name("account.editJob");
        Route::post("/account/my-jobs/edit/{jobId}", [AccountController::class, "updateJob"])->name("account.updateJob");
        // Route::get("/account/delete-job/{jobId}", [AccountController::class, "destroy"])->name("account.deleteJob");
        Route::post("/account/delete-job", [AccountController::class, "deleteJob"])->name("account.deleteJob");
        Route::get("/account/my-job-applications", [AccountController::class, "myJobApplications"])->name("myJobApplications");
        Route::post("/account/remove-job", [AccountController::class, "removeJobs"])->name("account.removeJob");
        Route::get("/account/saved-jobs", [AccountController::class, "savedJobs"])->name("account.savedJobs");
        Route::post("/apply-job", [JobsController::class, "applyJob"])->name("applyJob");
        Route::post("/saved-job", [JobsController::class, "savedJob"])->name("savedJob");
        Route::post("/account/remove-saved-job", [AccountController::class, "removeSavedJob"])->name("account.removeSavedJob");
        Route::post("/update-password", [AccountController::class, "updatePassword"])->name("account.updatePassword");
    });
});
