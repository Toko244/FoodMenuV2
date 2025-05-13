<?php

use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/checkPasswordToken', [PasswordController::class, 'checkPasswordToken'])->name('check-password-token');

// Route::get('/{any}', function () {
//     return view('app');
// })->where('any', '(?!api/).*')->name('home');
