<?php

use App\Events\Leads;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\FollowupController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class
])->group(function() {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    // LEAD ACTIONS
    Route::post('/leads/event', [LeadsController::class, 'event']);
    Route::post('/leads', [LeadsController::class, 'create']);
    Route::post('/leads/{lead}', [LeadsController::class, 'update']);
    Route::get('/leads/{lead}', [LeadsController::class, 'destroy']);
    
    
    // SEARCH LEADS
    Route::get('/query/{query}', [LeadsController::class, 'query'])->name('query');
    Route::get('/query/{user}/{query}', [LeadsController::class, 'user_query']);
    
    // GET FOLLOWUP DETAILS
    Route::get('/followups/{id}', [FollowupController::class, 'get_followups']);
    
    // USERS
    Route::get('/users', [UsersController::class, 'users']);
    Route::get('/users/all', [UsersController::class, 'all_users']);
    Route::patch('/users', [UsersController::class, 'update']);
    Route::get('/users/{user}', [UsersController::class, 'destroy']);
    
    // GET LEADS
    Route::get('/{status}/today', [LeadsController::class, 'get_api_today']);
    Route::get('/{status}/today/{user}', [LeadsController::class, 'get_api_user_today']);
    Route::get('/{status}/all', [LeadsController::class, 'get_api_all']);
    Route::get('/{status}/all/{user}', [LeadsController::class, 'get_api_user_all']);
    Route::get('/all', [LeadsController::class, 'get_all_leads']);
    Route::get('/all/{user}', [LeadsController::class, 'get_users_all_leads']);
    
    // GET CHART DATA
    Route::get('/count', [ChartsController::class, 'get_count']);
    Route::get('/count/m/{status}', [ChartsController::class, 'get_count_month']);
    Route::get('/count/w/{status}', [ChartsController::class, 'get_count_week']);
    Route::get('/count/d/{status}', [ChartsController::class, 'get_count_day']);
});