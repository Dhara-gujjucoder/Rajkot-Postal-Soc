<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\UserLoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('front.login');
});

/*users routes*/
Route::prefix('user')->name('user.')->middleware(['guest:users'])->group(function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login');
});
Route::prefix('user')->name('user.')->middleware(['auth:users'])->group(function () {
    Route::get('/home', [UserLoginController::class, 'index'])->name('home');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
});


include('admin.php');