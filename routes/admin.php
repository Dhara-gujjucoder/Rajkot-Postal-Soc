<?php

use App\Models\User;
use App\Models\AccountType;
use App\Models\FinancialYear;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BulkEntryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoanMasterController;
use App\Http\Controllers\LedgerEntryController;
use App\Http\Controllers\LedgerGroupController;
use App\Http\Controllers\LoanSettingController;
use App\Http\Controllers\ShareAmountController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\MonthlySavingController;
use App\Http\Controllers\SalaryDeductionController;
use App\Http\Controllers\LedgerShareCapitalController;
use App\Http\Controllers\MemberShareAccountController;
use App\Http\Controllers\LoanCalculationMatrixController;


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
    Route::get('/export/bulk_entries/{id}', [App\Http\Controllers\BulkEntryController::class, 'export'])->name('bulk_entries.export');
    Route::get('/get/member/{member}', [App\Http\Controllers\MemberController::class, 'getmember'])->name('member.get');
    Route::get('/get/guarantor_count/{member}', [App\Http\Controllers\LoanMasterController::class, 'guarantor_count'])->name('guarantor_count.get');

    Route::get('/import/all-share', [App\Http\Controllers\LedgerShareCapitalController::class, 'all_share'])->name('all_share.import');
    Route::post('/import/all-share', [App\Http\Controllers\LedgerShareCapitalController::class, 'import_all_share'])->name('all_share.import');
    /*all modules*/
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'members' => MemberController::class,
        'ledger_group' => LedgerGroupController::class,
        'ledger_account' => LedgerAccountController::class,
        'ledger_entries' => LedgerEntryController::class,
        'bulk_entries' => BulkEntryController::class,
        'loan' => LoanMasterController::class,
        'loan_matrix' => LoanCalculationMatrixController::class,
        'department' => DepartmentController::class,
        'salary_deduction' => SalaryDeductionController::class,
        'financial_year' => FinancialYearController::class,
        'loaninterest'=> LoanSettingController::class,
        'shareamount' => ShareAmountController::class,
        'monthlysaving'=>  MonthlySavingController::class,
        // 'share_account' => MemberShareAccountController::class,
        'ledger_sharecapital' => LedgerShareCapitalController::class,
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
