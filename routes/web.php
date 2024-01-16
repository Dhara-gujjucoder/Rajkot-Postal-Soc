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

Route::get('/', [UserLoginController::class, 'showLoginForm'])->middleware(['guest:users','blockIP']);

Route::get('/home', [UserLoginController::class, 'comingsoon'])->name('front.home');

/*users routes*/
Route::prefix('user')->name('user.')->middleware(['guest:users','blockIP'])->group(function () {
        Route::get('/login', [UserLoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [UserLoginController::class, 'login'])->name('login');
});
Route::prefix('user')->name('user.')->middleware(['auth:users','blockIP'])->group(function () {
    Route::get('/home', [UserLoginController::class, 'index'])->name('home');
    Route::get('/profile', [UserLoginController::class, 'profile'])->name('profile');
    Route::get('/loan/account', [LoanController::class, 'apply'])->name('loan.apply');
    Route::get('/loan/calculator', [LoanController::class, 'calculator'])->name('loan.calculator');
    Route::post('/loan-mailed', [LoanController::class, 'LoanMailSend'])->name('loan.sendmail');
    Route::post('/logout', [UserLoginController::class, 'logout'])->name('logout');

    Route::get('locale/{locale}', function ($lang) {
        Session::put('locale', $lang);
        app()->setLocale($lang);
        return redirect()->back();
        // dump(app()->getLocale($lang));
        // return redirect()->route('user.home')->withSuccess(__('Language changes successfully.'));
    })->name('change.locale');
});


include('admin.php');
