<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-role|edit-role|delete-role|view-role', ['only' => ['index','show']]);
        $this->middleware('permission:create-role', ['only' => ['create','store']]);
        $this->middleware('permission:edit-role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = Auth::user();
        // dd($user->can('create-role'));
        return view('roles.index', [
            'roles' => Role::orderBy('id','DESC')->paginate(10),
            'page_title'=> __('View Roles')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('roles.create', [
            'permissions' => Permission::get(),
            'page_title'=> __('Add New Role')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::create(['name' => $request->name]);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
       
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
                ->withSuccess(__('New role is added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role): View
    {
        $rolePermissions = Permission::join("role_has_permissions","permission_id","=","id")
            ->where("role_id",$role->id)
            ->select('name')
            ->get();
        return view('roles.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions,
            'page_title'=> __('View Role')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role): View
    {
        if($role->name=='Super Admin'){
            abort(403, __('SUPER ADMIN ROLE CAN NOT BE EDITED'));
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$role->id)
            ->pluck('permission_id')
            ->all();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions,
            'page_title'=> __('Edit Role')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        $input = $request->only('name');

        $role->update($input);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();

        $role->syncPermissions($permissions);    
        
        return redirect()->back()
                ->withSuccess(__('Role is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role): RedirectResponse
    {
        if($role->name=='Super Admin'){
            abort(403, __('SUPER ADMIN ROLE CAN NOT BE DELETED'));
        }
        if(auth()->user()->hasRole($role->name)){
            abort(403, __('CAN NOT DELETE SELF ASSIGNED ROLE'));
        }
        $role->delete();
        return redirect()->route('roles.index')
                ->withSuccess(__('Role is deleted successfully.'));
    }
}
