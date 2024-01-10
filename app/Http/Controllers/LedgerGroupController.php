<?php

namespace App\Http\Controllers;

use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\DataTables;

class LedgerGroupController extends Controller
{
    /*check permission*/
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware('permission:create-ledger_group|edit-ledger_group|delete-ledger_group|view-ledger_group', ['only' => ['index','show']]);
        $this->middleware('permission:create-ledger_group', ['only' => ['create','store']]);
        $this->middleware('permission:edit-ledger_group', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-ledger_group', ['only' => ['destroy']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['ledger_groups'] = LedgerGroup::orderBy('id','ASC')->paginate(10);
        $data['ledgers'] = LedgerGroup::where('parent_id',0)->get();
        $data['page_title']= __('View Ledger Group Types');
        if ($request->ajax()) {
            $data = LedgerGroup::orderBy('id','DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    // $show_btn = '<a href="'.route('ledger_group.show', $row->id).'"
                    // class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i>'.__('Show').'</a>';
                    $edit_btn = '<a href="'.route('ledger_group.edit', $row->id).'"
                    class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>'.__('Edit').'</a>';
                    $delete_btn = '<form action="'.route('ledger_group.destroy', $row->id).'" method="post">'.csrf_field().method_field('DELETE').'<button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('.__('Do you want to delete this salary deduction?').';"><i class="bi bi-trash"></i>'.__('Delete').'</button></form>';
                    $action_btn = '';
                    // (Auth::user()->can('view-member')) ? $action_btn.= $show_btn : '';
                    (Auth::user()->can('edit-member')) ? $action_btn.= $edit_btn : '';
                    (Auth::user()->can('delete-member')) ? $action_btn.= $delete_btn : '';
                    return $action_btn;
                })
                ->filterColumn('parent_id', function($query, $search) {
                        $query->where('parent_id', $search);
                })
                ->editColumn('parent_id', function($row){
                    return $row->ParentLedgerGroup->ledger_group ?? '';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('ledger_group.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ledger_group.create', [
            'ledgers'=> LedgerGroup::where('parent_id',0)->get(),
            'page_title'=> __('Add New Ledger Group')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ledger_group' => 'required|string|unique:ledger_group,ledger_group|max:255'
        ]);
        LedgerGroup::create(['ledger_group' => $request->ledger_group,'parent_id'=>$request->parent_id]);
        return redirect()->route('ledger_group.index')
                ->withSuccess(__('New Ledger Group is added successfully.'));
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
        $ledger_group = LedgerGroup::findOrFail($id);
        return view('ledger_group.edit', [
            'ledgers'=> LedgerGroup::where('parent_id',0)->get(),
            'ledger_group'=> $ledger_group,
            'page_title'=> __('Edit Ledger Group')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,LedgerGroup $ledger_group): RedirectResponse
    {
        $request->validate(['ledger_group'=>'required|string|max:255|unique:ledger_group,ledger_group,' . $ledger_group->id]);
        $input = $request->only('ledger_group','parent_id');
        $ledger_group->update($input);
        return redirect()->route('ledger_group.index')
                ->withSuccess(__('Ledger Group is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LedgerGroup $ledger_group): RedirectResponse
    {
        $ledger_group->delete();
        return redirect()->route('ledger_group.index')
                ->withSuccess(__('Ledger Group is deleted successfully.'));
    }
}
