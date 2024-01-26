<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\AccountType;
use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\LedgerAccount;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LedgerAccountController extends Controller
{
    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-ledger_account|edit-ledger_account|delete-ledger_account|view-ledger_account', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-ledger_account', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-ledger_account', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-ledger_account', ['only' => ['destroy']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data['ledger_accounts'] = LedgerAccount::get();
        $data['members'] = Member::withTrashed()->orderBy('uid', 'ASC')->get();
        $data['ledger_group'] = LedgerGroup::get();
        $data['page_title'] = __('View Ledger Accounts');
        if ($request->ajax()) {
            $data = LedgerAccount::where('year_id', $this->current_year->id)->orderBy('id', 'DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    // $show_btn = '<a href="'.route('ledger_account.show', $row->id).'"
                    // class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i>'.__('Show').'</a>';
                    $edit_btn = '<a href="' . route('ledger_account.edit', $row->id) . '"
                    class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>' . __('Edit') . '</a>';
                    $delete_btn = '<form action="' . route('ledger_account.destroy', $row->id) . '" method="post"><button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm(' . __('Do you want to delete this salary deduction?') . ';"><i class="bi bi-trash"></i>' . __('Delete') . '</button></form>';
                    $action_btn = '';
                    // (Auth::user()->can('view-ledger_account')) ? $action_btn.= $show_btn : '';
                    (Auth::user()->can('edit-ledger_account')) ? $action_btn .= $edit_btn : '';
                    (Auth::user()->can('delete-ledger_account')) ? $action_btn .= $delete_btn : '';
                    return $action_btn;
                })
                ->filterColumn('user_id', function ($query, $search) {
                    $query->where('user_id', $search);
                })
                ->editColumn('user_id', function ($row) {
                    return $row->user->fullname;
                })
                ->editColumn('ledger_group_id', function ($row) {
                    return $row->LedgerGroupId->ledger_group;
                })
                ->orderColumn('name', function ($query, $order) {
                    $query->whereHas('user', function ($q) use ($order) {
                        $q->orderBy('name', $order);
                    });
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('ledger_account.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ledger_account.create', [
            'page_title' => __('Add New Ledger Account'),
            'members' => Member::get(),
            'ledger_groups' => LedgerGroup::get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'account_name' => 'required|string|max:255|unique:ledger_accounts,account_name',
            'ledger_group_id' => 'required',
            'opening_balance' => 'required',
            'type' => 'required'
        ]);
        $input = $request->all();
        $input['created_by'] = Auth::user()->id;
        $input['year_id'] = FinancialYear::where('is_current', 1)->pluck('id')->first();
        LedgerAccount::create($input);
        return redirect()->route('ledger_account.index')
            ->withSuccess(__('New Ledger Account is added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ledger_account = LedgerAccount::findOrFail($id);
        return view('ledger_account.edit', [
            'ledger_account' => $ledger_account,
            'members' => Member::get(),
            'ledger_groups' => LedgerGroup::get(),
            'page_title' => __('Edit Ledger Account')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LedgerAccount $ledger_account): RedirectResponse
    {
        $request->validate([
            'ledger_group_id' => 'required',
            'ledger_group_id' => 'required',
            'opening_balance' => 'required',
            'type' => 'required',
            'account_name' => 'required|string|max:255|unique:ledger_accounts,account_name,' . $ledger_account->id
        ]);
        $input = $request->all();
        $ledger_account->update($input);
        return redirect()->route('ledger_account.index')
            ->withSuccess(__('Ledger Account is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerAccount $ledger_account): RedirectResponse
    {
        $ledger_account->delete();
        return redirect()->route('ledger_account.index')
            ->withSuccess(__('Ledger Account is deleted successfully.'));
    }
}
