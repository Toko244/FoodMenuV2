<?php

use App\Http\Controllers\Auth\AmbassadorRegistrationController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\UserRegistrationController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);

    Route::post('/checkRegistrationToken', [UserRegistrationController::class, 'checkRegistrationToken']);
    Route::post('/registration', [UserRegistrationController::class, 'signUp'])->name('signup');
    Route::post('/complete-user-registration', [UserRegistrationController::class, 'completeRegistration']);
    Route::post('/complete-ambassador-registration', [AmbassadorRegistrationController::class, 'completeRegistration']);
    Route::get('/complete-ambassador-registration/formData', [AmbassadorRegistrationController::class, 'formData']);

    Route::get('/auth/google', [SocialiteController::class, 'redirectToGoogle']);
    Route::get('/auth/google/callback', [SocialiteController::class, 'handleGoogleCallback']);
    Route::get('/auth/facebook', [SocialiteController::class, 'redirectToFacebook']);
    Route::get('/auth/facebook/callback', [SocialiteController::class, 'handleFacebookCallback']);
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
