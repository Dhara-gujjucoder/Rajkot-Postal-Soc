<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\LoanController;
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
})->middleware(['guest:users']);

/*users routes*/
Route::prefix('user')->name('user.')->middleware(['guest:users'])->group(function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login');
});
Route::prefix('user')->name('user.')->middleware(['auth:users'])->group(function () {
    Route::get('/home', [UserLoginController::class, 'index'])->name('home');
    Route::get('/profile', [UserLoginController::class, 'profile'])->name('profile');
    Route::get('/loan/apply', [LoanController::class, 'apply'])->name('loan.apply');
    Route::get('/loan/calculator', [LoanController::class, 'calculator'])->name('loan.calculator');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');
    
    Route::get('locale/{locale}', function ($lang) {
        Session::put('locale', $lang);
        app()->setLocale($lang);
        // dump(app()->getLocale($lang));
        return redirect()->route('user.home')->withSuccess(__('Language changes successfully.'));
    })->name('change.locale');
});


include('admin.php');