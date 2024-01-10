<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Member;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;

class MemberController extends Controller
{
    public $dirPath = '/images';
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-member|edit-member|delete-member|view-member', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-member', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-member', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-member', ['only' => ['destroy']]);
        $this->middleware('permission:view-member', ['only' => ['show', 'index']]);
        parent::__construct();
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data['page_title'] = __('View Members');
        $data['departments'] = 1;
        $data['departments'] = Department::get();
        $data['members'] = Member::orderBy('uid', 'DESC')->get();
        if ($request->ajax()) {
            $data = User::usermember()->with('member')->orderBy('id','DESC');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $show_btn = '<a href="'.route('members.show', $row->member->id).'"
                    class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i>'.__('Show').'</a>';
                    $edit_btn = '<a href="'.route('members.edit', $row->member->id).'"
                    class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-square"></i>'.__('Edit').'</a>';
                    $delete_btn = '<form action="'.route('members.destroy', $row->member->id).'" method="post">'.csrf_field().method_field('DELETE').'<button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('.__('Do you want to delete this salary deduction?').';"><i class="bi bi-trash"></i>'.__('Delete').'</button></form>';
                    $action_btn = '';
                    (Auth::user()->can('view-member')) ? $action_btn.= $show_btn : '';
                    (Auth::user()->can('edit-member')) ? $action_btn.= $edit_btn : '';
                    (Auth::user()->can('delete-member')) ? $action_btn.= $delete_btn : '';
                    return $action_btn;
                })
                ->filterColumn('name', function($query, $search) {
                    $query->where('id',$search);
                })
                ->filterColumn('registration_no', function($query, $search) {
                    $query->whereHas('member',function($q) use ($search) {
                        $q->where('registration_no',$search);
                    });
                })
                ->filterColumn('department_id', function($query, $search) {
                    $query->whereHas('member',function($q) use ($search) {
                        $q->where('department_id',$search);
                    });
                })
                ->orderColumn('registration_no', function($query, $order) {
                    // $sql = "CONCAT(users.first_name,'-',users.last_name)  like ?";
                    $query->whereHas('member',function($q) use ($order) {
                        $q->orderBy('registration_no', $order);
                    });
                })

                ->editColumn('registration_no', function($row){
                    return $row->member->registration_no;
                })
                ->editColumn('department_id', function($row){
                    return $row->member->department->department_name ?? '';
                })
                ->addColumn('roles', function($row){
                    $roles = '';
                     foreach ($row->getRoleNames()->all() as $key => $value) {
                        $roles.= '<span class="badge bg-primary">'.$value.'</span>';
                     }
                     return $roles;
                })
                ->rawColumns(['action','roles'])
                ->make(true);
        }
        return view('member.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('member.create', [
            'departments' => Department::get(),
            'roles' => Role::pluck('name')->all(),
            'page_title' => __('Add Member')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['is_member'] = '1';
        $files = ['aadhar_card','profile_picture', 'pan_card',' department_id_proof','signature','witness_signature'];
        foreach ($files as $key => $value) {
            if ($request->has($value)) {
                $image = $request->{$value};
                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($this->dirPath).'/'.$value, $image_name);
                $input[$value] = $this->dirPath .'/'.$value.'/'.$image_name;
            }
        }
        $user = User::create($input);
        $user->assignRole(['user']);
        $input['user_id'] = $user->id;
        $input['uid'] = Member::latest()->pluck('uid')->first() ? Member::latest()->pluck('uid')->first()+1 : 1;
        $input['status'] = 1;
        unset($input['name'],$input['email'],$input['search_terms'],$input['password'],$input['is_member']);
        Member::create($input);

        return redirect()->route('members.index')
            ->withSuccess(__('New member is added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,Member $member): View
    {
        if($request->ajax()){
            Member::find($request->input('pk'))->update([$request->input('name') => $request->input('value')]);
            return response()->json(['success' => true]);
        }


        return view('member.show', [
            'user' => $member,
            'page_title' => __('View Member')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member): View
    {
        // Check Only Super Admin can update his own Profile
        $user = $member->user;
        if ($user->hasRole('Super Admin')) {
            if ($user->id != auth()->user()->id) {
                abort(403, __('USER DOES NOT HAVE THE RIGHT PERMISSIONS'));
            }
        }

        return view('member.edit', [
            'departments' => Department::get(),
            'user' => $member,
            'roles' => Role::pluck('name')->all(),
            'memberRoles' => $user->roles->pluck('name')->all(),
            'page_title' => __('Edit Member')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMemberRequest $request, Member $member): RedirectResponse
    {
        $input = $request->all();

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            $input = $request->except('password');
        }
        $files = ['aadhar_card', 'pan_card','profile_picture','department_id_proof','signature','witness_signature'];
        foreach ($files as $key => $value) {
            if ($request->has($value)) {
                $image = $request->{$value};
                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($this->dirPath).'/'.$value, $image_name);
                $input[$value] = $this->dirPath .'/'.$value.'/'.$image_name;
            }
        }
        $member->user->update($input);
        unset($input['name'],$input['email'],$input['search_terms'],$input['password'],$input['is_member']);
        $member->update($input);
        // $member->syncRoles(['member']);

        return redirect()->route('members.index')
            ->withSuccess(__('Member is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {
        // About if user is Super Admin or User ID belongs to Auth User
        $user = $member->user;
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id) {
            abort(403, __('USER DOES NOT HAVE THE RIGHT PERMISSIONS'));
        }

        $user->syncRoles([]);
        $member->delete();
        $user->delete();
        return redirect()->route('members.index')
            ->withSuccess(__('Member is deleted successfully.'));
    }
}
