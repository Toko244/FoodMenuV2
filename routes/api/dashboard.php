<?php

use App\Http\Controllers\Auth\OrganizationController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\CompanyController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\TagController;
use App\Http\Controllers\Dashboard\VenueCategoryController;
use App\Http\Requests\Dashboard\CheckCompanySubDomainRequest;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'dashboard', 'middleware' => 'auth:sanctum'], function () {
    //Company Routes -----------------------------------------------------------
    Route::get('/companies/form-data', [CompanyController::class, 'formData']);
    Route::apiResource('/companies', CompanyController::class);
    Route::get('/check-company-sub-domain', function(CheckCompanySubDomainRequest $request) {
        return response(['message' => __('company.company_sub_domain_available')]);
    });

    //Tag Routes -----------------------------------------------------------
    Route::get('/tags/form-data', [TagController::class, 'formData']);
    Route::apiResource('/tags', TagController::class);

    //Profile Routes -----------------------------------------------------------
    Route::get('/profile/user', [ProfileController::class, 'user']);
    Route::put('/profile/user/data', [ProfileController::class, 'updateData']);
    Route::patch('/profile/user/password', [ProfileController::class, 'updatePassword']);
    Route::get('/profile/companies', [ProfileController::class, 'companies']);
    Route::get('/profile/current-company', [ProfileController::class, 'getCurrentCompany']);
    Route::patch('/profile/current-company/{company}', [ProfileController::class, 'setCurrentCompany']);

    //Category Routes -----------------------------------------------------------
    Route::post('/categories/sort', [CategoryController::class, 'sort']);
    Route::get('/categories/form-data', [CategoryController::class, 'formData']);
    Route::apiResource('/categories', CategoryController::class);

    //Product Routes -----------------------------------------------------------
    Route::get('/products/form-data', [ProductController::class, 'formData']);
    Route::apiResource('/products', ProductController::class);

    //Ambassador Invitation -----------------------------------------------------------
    Route::post('/send-invitation', [OrganizationController::class, 'inviteUsers']);

    //VenueCategory Routes -----------------------------------------------------------
    Route::apiResource('/venue-categories', VenueCategoryController::class)->middleware('role:admin');
});
