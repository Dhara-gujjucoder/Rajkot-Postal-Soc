<?php

use App\Models\User;
use App\Models\AccountType;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\LedgerEntryController;
use App\Http\Controllers\LedgerGroupController;
use App\Http\Controllers\LoanSettingController;
use App\Http\Controllers\ShareAmountController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\MonthlySavingController;
use App\Http\Controllers\User\UserLoginController;
use App\Http\Controllers\SalaryDeductionController;
use App\Http\Controllers\DepartmentController;


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
    Route::get('/import/member', [App\Http\Controllers\MemberImportController::class, 'create'])->name('member.import');
    Route::post('/import/member', [App\Http\Controllers\MemberImportController::class, 'storewithimage'])->name('member.import');
    Route::get('/import/salary', [App\Http\Controllers\SalaryDeductionController::class, 'salary_import'])->name('salary.import');
    Route::post('/import/salary', [App\Http\Controllers\SalaryDeductionController::class, 'importsalary'])->name('salary.import');


    Route::resource('loaninterest', LoanSettingController::class);
    Route::resource('shareamount', ShareAmountController::class);
    Route::resource('monthlysaving', MonthlySavingController::class);
    /*all masters*/
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'members' => MemberController::class,
        // 'account_type' => AccountTypeController::class,
        'ledger_group' => LedgerGroupController::class,
        'ledger_account' => LedgerAccountController::class,
        'ledger_entries' => LedgerEntryController::class,
        'department' => DepartmentController::class,
        'salary_deduction' => SalaryDeductionController::class,
        'financial_year' => FinancialYearController::class,
    ]);


    /*main setting*/
    Route::get('locale/{locale}', function ($lang) {
        Session::put('locale', $lang);
        app()->setLocale($lang);
        return redirect()->back()->withSuccess(__('Language changes successfully.'));
    })->name('change.locale');

    Route::get('/clear', function () {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        Artisan::call('view:clear');
        return "Cleared!";
    });

    Route::get('cmd/{cmd}', function ($cmd) {
        Artisan::call($cmd);
        return $cmd.'   Done';
    });

    /** Financial Year */
    Route::get('financial_year/change/{id}', function ($id) {
        Session::put('financial_year', $id);
        FinancialYear::query()->update(['is_current' => false]);
        FinancialYear::where('id', $id)->update(['is_current' => true]);
        return redirect()->back()->withSuccess(__('Financial Year changes successfully.'));
    })->name('financial_year.change');
});
