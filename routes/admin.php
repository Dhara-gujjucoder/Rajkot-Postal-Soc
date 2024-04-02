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
use App\Http\Controllers\BulkEntryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LoanMasterController;
use App\Http\Controllers\TempReportController;
use App\Http\Controllers\DoubleEntryController;
use App\Http\Controllers\LedgerEntryController;
use App\Http\Controllers\LedgerGroupController;
use App\Http\Controllers\LedgerShareController;
use App\Http\Controllers\LoanSettingController;
use App\Http\Controllers\MemberShareController;
use App\Http\Controllers\ShareAmountController;
use App\Http\Controllers\ShareLedgerController;
use App\Http\Controllers\TarijReportController;
use App\Http\Controllers\FinancialYearController;
use App\Http\Controllers\JournelReportController;
use App\Http\Controllers\LedgerAccountController;
use App\Http\Controllers\MonthlySavingController;
use App\Http\Controllers\SalaryDeductionController;
use App\Http\Controllers\LedgerFixedSavingController;
use App\Http\Controllers\MemberFixedSavingController;
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
    Route::post('/import/member', [App\Http\Controllers\MemberImportController::class, 'store'])->name('member.import');
    Route::get('/import/salary', [App\Http\Controllers\SalaryDeductionController::class, 'salary_import'])->name('salary.import');
    Route::post('/import/salary', [App\Http\Controllers\SalaryDeductionController::class, 'importsalary'])->name('salary.import');
    Route::get('/export/bulk_entries/{id}', [App\Http\Controllers\BulkEntryController::class, 'export'])->name('bulk_entries.export');
    Route::get('/get/member/{member}', [App\Http\Controllers\MemberController::class, 'getmember'])->name('member.get');
    Route::get('/get/guarantor_count/{member}', [App\Http\Controllers\LoanMasterController::class, 'guarantor_count'])->name('guarantor_count.get');

    //***** Excel *****
    Route::get('/import/saving', [App\Http\Controllers\MemberFixedSavingController::class, 'all_share'])->name('all_share.import');            //******//
    Route::post('/import/saving', [App\Http\Controllers\MemberFixedSavingController::class, 'import_all_share'])->name('all_share.import');    //******//

    Route::get('/import/member-share', [App\Http\Controllers\MemberShareController::class, 'member_share'])->name('member_share.import');
    Route::post('/import/member-share', [App\Http\Controllers\MemberShareController::class, 'import_member_share'])->name('member_share.import');

    Route::get('/member_share_export', [TempReportController::class, 'member_share'])->name('tempreport.member_share');
    Route::get('/member_share-export', [TempReportController::class, 'member_share_export'])->name('share_export');

    Route::get('/tempreport', [TempReportController::class, 'create'])->name('tempreport.create');
    Route::get('/fixed-saving-export', [TempReportController::class, 'fixed_saving_export'])->name('fixed_saving_export');

    Route::get('/ledger-fixed-saving', [LedgerFixedSavingController::class, 'index'])->name('ledger_reports.fixed_saving.index');
    Route::get('/ledger-fixed-saving-export/{id}',[LedgerFixedSavingController::class,'fixed_saving_export'])->name('ledger_fixed_saving_export');
    Route::post('/ledger-all-fixed-saving-export',[LedgerFixedSavingController::class,'all_fixed_saving_export'])->name('all_fixed_saving_export');

    Route::get('/ledger-share', [ShareLedgerController::class, 'index'])->name('ledger_reports.share_ledger.index');
    Route::post('/ledger-share-export',[ShareLedgerController::class,'all_share_ledger_export'])->name('all_share_ledger_export');

    Route::get('/journel-report', [JournelReportController::class, 'index'])->name('ledger_reports.journel_report.index');
    Route::post('/journel-report-export',[JournelReportController::class,'journel_report_export'])->name('journel_report_export');

    Route::get('/tarij-report', [TarijReportController::class, 'index'])->name('ledger_reports.tarij_report.index');
    Route::post('/tarij-report-export',[TarijReportController::class,'tarij_report_export'])->name('tarij_report_export');

    //***** END Excel *****/

    Route::get('/get/member/history/{member}', [App\Http\Controllers\MemberController::class, 'getmember_history'])->name('member.history.get');
    Route::post('/member/resign/{member}',[MemberController::class,'resign'])->name('member.resign');

    Route::post('/double_entries/confirm',[DoubleEntryController::class,'confirm'])->name('double_entries.confirm');
    Route::post('/loan/partial_pay/{loan}',[LoanMasterController::class,'partial_pay'])->name('loan.partial_pay');

    
    /*all modules*/
    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'members' => MemberController::class,
        'ledger_group' => LedgerGroupController::class,
        'ledger_account' => LedgerAccountController::class,
        'double_entries' => DoubleEntryController::class,
        'bulk_entries' => BulkEntryController::class,
        'loan' => LoanMasterController::class,
        'loan_matrix' => LoanCalculationMatrixController::class,
        'department' => DepartmentController::class,
        'salary_deduction' => SalaryDeductionController::class,
        'financial_year' => FinancialYearController::class,
        'loaninterest'=> LoanSettingController::class,
        'shareamount' => ShareAmountController::class,
        'monthlysaving'=>  MonthlySavingController::class,
        'member_fixed_saving' => MemberFixedSavingController::class,
        'member_share' => MemberShareController::class,
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
