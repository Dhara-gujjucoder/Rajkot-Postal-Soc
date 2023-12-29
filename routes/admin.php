<?php

use App\Models\User;
use App\Models\AccountType;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\LoanSettingController;
use App\Http\Controllers\MonthlySavingController;
use App\Http\Controllers\ShareAmountController;
use App\Http\Controllers\User\UserLoginController;


Route::prefix('admin')->group(function () {
    Auth::routes(['register' => false]);
});

Route::prefix('admin')->middleware(['auth:web'])->group(function () {

    /** other routes */
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/general/setting/{setting}', [App\Http\Controllers\SettingController::class, 'create'])->name('setting.create');
    Route::post('/general/setting/{setting}', [App\Http\Controllers\SettingController::class, 'store'])->name('setting.update');
    Route::get('/users/profile/{user}', [App\Http\Controllers\UserController::class, 'profile'])->name('users.profile');
    Route::post('/users/profile/{user}', [App\Http\Controllers\UserController::class, 'updateProfile'])->name('users.profile');
    
    Route::resource('loaninterest', LoanSettingController::class);
    Route::resource('shareamount', ShareAmountController::class);
    Route::resource('monthlysaving', MonthlySavingController::class);
    /*all masters*/
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'members' => MemberController::class,
        'account_type' => AccountTypeController::class,
        'ledger_account' => LedgerAccountController::class,

    ]);


    /*main setting*/
    Route::get('locale/{locale}', function ($lang) {
        Session::put('locale', $lang);
        app()->setLocale($lang);
        return redirect()->back()->withSuccess(__('Language changes successfully.'));
    })->name('change.locale');

    /** Financial Year */
    Route::get('financial_year/change/{id}', function ($id) {
        Session::put('financial_year', $id);
        FinancialYear::query()->update(['is_selected' => 0]);
        FinancialYear::where('id', $id)->update(['is_selected' => 1]);
        return redirect()->back()->withSuccess(__('Financial Year changes successfully.'));
    })->name('financial_year.change');
});
