<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Checks if a user is a guest, if so redirects to welcome page else redirects to homepage
Route::get('/', function () {
    if (Auth::guest()) 
        return redirect()->route('welcome');
    else
        return view('home');
});

// Auth routes from laravel
Auth::routes();

// Route home shows the welcome page (for guests)
Route::get('/home', function() {
    return view('welcome');
})->name('welcome');

Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('api')->group(base_path('routes/api.php'));

Route::get('/clear', function() {
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return "Cleared!";
});

Route::get('{path}', [HomeController::class, 'index'])->where('path', '[\/\w\.-]*' );