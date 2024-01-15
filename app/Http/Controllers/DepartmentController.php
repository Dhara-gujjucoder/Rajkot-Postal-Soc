<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\LedgerGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class DepartmentController extends Controller
{
    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-department|edit-department|delete-department|view-department', ['only' => ['index','show']]);
        $this->middleware('permission:create-department', ['only' => ['create','store']]);
        $this->middleware('permission:edit-department', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-department', ['only' => ['destroy']]);
        parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();
        $data['departments'] = Department::get();   //orderBy('id','ASC')->
        $data['page_title']= __('View Department');
        return view('department.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.create', [
            'page_title'=> __('Add New Department'),
            'ledger_groups' => LedgerGroup::where('parent_id',0)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'department_name' => 'required|string|max:255|unique:department,department_name',
            'ledger_group_id' => 'required',
        ]);
        Department::create(['ledger_group_id' => $request->ledger_group_id,'department_name' => $request->department_name,'created_by' => Auth::user()->id]);
        return redirect()->route('department.index')
                ->withSuccess(__('New Department is added successfully.'));
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
        $department = Department::findOrFail($id);
        return view('department.edit', [
            'department'=> $department,
            'ledger_groups' => LedgerGroup::where('parent_id',0)->get(),
            'page_title'=> __('Edit Department')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Department $department): RedirectResponse
    {
        $request->validate(['ledger_group_id' => 'required','department_name'=>'required|string|max:255|unique:department,department_name,' . $department->id]);
        $input = $request->only('ledger_group_id','department_name');
        $department->update($input);
        return redirect()->route('department.index')
                ->withSuccess(__('Department updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();
        return redirect()->route('department.index')
                ->withSuccess(__('Department deleted successfully.'));
    }




}
