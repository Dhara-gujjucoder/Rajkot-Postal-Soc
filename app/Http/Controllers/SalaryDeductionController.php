<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\View\View;
use App\Models\AccountType;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use App\Models\SalaryDeduction;
use App\Imports\SalaryDedImport;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreSalaryDeductionRequest;
use App\Http\Requests\UpdateSalaryDeductionRequest;

class SalaryDeductionController extends Controller
{
    public $dirPath = '/images';
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-salary_deduction|edit-salary_deduction|delete-salary_deduction|view-salary_deduction', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-salary_deduction', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-salary_deduction', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-salary_deduction', ['only' => ['destroy']]);
        $this->middleware('permission:view-salary_deduction', ['only' => ['show', 'index']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        // dd($request->query('search'));
        $data['page_title'] = __('View Salary Deduction');
        // $data['departments'] = AccountType::get();
        $data['ledgers'] = LedgerAccount::get();
        $data['members'] = Member::orderBy('uid', 'ASC')->get();
        if ($request->ajax()) {
            $data = SalaryDeduction::where('year_id',$this->current_year->id)->orderBy('id','DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $show_btn = '<a href="'.route('salary_deduction.show', $row->id).'"
                    class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i>'.__('Show').'</a>';
                    $edit_btn = '<a href="'.route('salary_deduction.edit', $row->id).'"
                    class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>'.__('Edit').'</a>';
                    $delete_btn = '<form action="'.route('salary_deduction.destroy', $row->id).'" method="post"><button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('.__('Do you want to delete this salary deduction?').';"><i class="bi bi-trash"></i>'.__('Delete').'</button></form>';
                    $action_btn = '';
                    (Auth::user()->can('view-salary_deduction')) ? $action_btn.= $show_btn : '';
                    (Auth::user()->can('edit-salary_deduction')) ? $action_btn.= $edit_btn : '';
                    (Auth::user()->can('delete-salary_deduction')) ? $action_btn.= $delete_btn : '';
                    return $action_btn;
                })
                ->filterColumn('name', function($query, $search) {
                    $query->where('user_id',$search);
                })
                ->editColumn('name', function($row){
                    return $row->user->fullname;
                })
                ->editColumn('month', function($row){
                    $month = explode('-',$row->month);
                    // $rr = \Carbon\CarbonPeriod::create($month[0].'-'.$month[1]);
                    // return $rr;
                    return  date('M-Y',strtotime('01-'.$month[0].'-'.$month[1]));
                })
            
                ->orderColumn('name', function($query, $order) {
                    $query->whereHas('user',function($q) use ($order) {
                        $q->orderBy('name', $order);
                    });
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('salary_deduction.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('salary_deduction.create', [
            // 'departments' => AccountType::get(),
            'ledgers' => LedgerAccount::get(),
            'members' => Member::orderBy('uid', 'DESC')->get(),
          
            'page_title' => __('Add New')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSalaryDeductionRequest $request)
    {
        $input = $request->all();

        unset($input['search_terms']);
        $member = Member::where('user_id',$request->user_id)->first();
        $input['uid'] = $member->uid;
        $input['year_id'] = $this->current_year->id;
        $input['month'] = $request->month;
        $total = 0;
        $total = $request->interest+$request->principal+$request->fixed;
        if($total<=0){
            $errors = [];
            if(!$request->fixed>0){
                $errors = ['total_amount' => __('Please enter one of three amount Principal, Interest or Fixed Monthly Saving')];
            }
            return redirect()->back()->withInput()->withErrors($errors);
        }  
        $input['total_amount'] =  $total;
        SalaryDeduction::create($input);

        return redirect()->route('salary_deduction.index')
            ->withSuccess(__('New Salary Deduction is added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('salary_deduction.show', [
            'salary_deduction' => SalaryDeduction::FindOrFail($id),
            'page_title' => __('View Salary Deduction')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('salary_deduction.edit', [
            // 'departments' => AccountType::get(),
            'ledgers' => LedgerAccount::get(),
            'members' => Member::orderBy('uid', 'DESC')->get(),
            'salary_deduction' => SalaryDeduction::findOrFail($id),
            'page_title' => __('Edit Salary Deduction')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSalaryDeductionRequest $request, string $id)
    {
      
        $input = $request->all();
        // dd($input);
        $salary_deduction = SalaryDeduction::where('id',$id)->first();
        unset($input['search_terms']);
        $member = Member::where('user_id',$request->user_id)->first();
        $input['uid'] = $member->uid;
        $input['month'] = explode('-',$request->month)[0];
        //$input['year'] = explode('-',$request->month)[1];
        $input['year_id'] = $this->current_year->id;
        $total = 0;
        $total = $request->interest+$request->principal+$request->fixed;
        if($total<=0){
            $errors = [];
            if(!$request->interest>0){
                $errors = ['interest' => __('Please enter proper amount')];
            }
            if(!$request->principal>0){
                $errors = ['principal' => __('Please enter proper amount')];
            }
            if(!$request->fixed>0){
                $errors = ['fixed' => __('Please enter proper amount')];
            }
            return redirect()->back()->withInput()->withErrors($errors);
        }  
        $input['total_amount'] =  $total;
        $salary_deduction->update($input);
        return redirect()->route('salary_deduction.index')
            ->withSuccess(__('Salary Deduction is edited successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SalaryDeduction $salary_deduction): RedirectResponse
    {
        $salary_deduction->delete();
        return redirect()->route('salary_deduction.index')
                ->withSuccess(__('Salary Deduction is deleted successfully.'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function importsalary(Request $request): RedirectResponse
    {
        $import = new SalaryDedImport;
        //      $import = new UsersImport();

        Excel::import($import, $request->file('salary_import'));
        // Excel::import($import,  $request->file('salary_import'));
        dd($import->un);

        return redirect()->route('salary_deduction.index')
            ->withSuccess(__('Salary Deduction Imported successfully.'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function salary_import(): View
    {

        return view('import.salary_import', [
            'page_title' => __('Import Salary')
        ]);
    }
}
