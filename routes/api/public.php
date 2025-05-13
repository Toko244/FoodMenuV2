<?php

use App\Http\Controllers\Public\CompanyController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/companies/form-data', [CompanyController::class, 'formData']);
    Route::get('/companies', [CompanyController::class, 'index']);
});
