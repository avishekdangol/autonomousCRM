<?php

use App\Http\Controllers\LicenseController;
use App\Http\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::get('/domain/check/{domain}', [TenantController::class, 'check_domain']);

// CRUD routes for tenant
Route::prefix('/tenant')->group(function() {
    // create new tenant
    Route::post('/', [TenantController::class, 'create']);

    // get all the clients, or recent clients, or clients that needs renewal
    Route::get('/get', [TenantController::class, 'get']);
    Route::get('/recent/get', [TenantController::class, 'getRecent']);
    Route::get('/renew/get', [TenantController::class, 'getDue']);

    // update the clients' name
    Route::patch('/', [TenantController::class, 'update']);

    // delete a client or bulk delete clients
    Route::delete('/{id}', [TenantController::class, 'delete']);
    Route::delete('/', [TenantController::class, 'bulkDelete']);

    // renew a client or bulk renew clients
    Route::get('/renew/{id}', [TenantController::class, 'renew']);
    Route::post('/renew', [TenantController::class, 'bulkRenew']);
});

// Routes for licenses
Route::prefix('/licenses')->group(function() {
    // get all the licenses
    Route::get('/get', [LicenseController::class, 'get']);
});