<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Member;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\LoanMaster;
use App\Models\AccountType;
use App\Models\LedgerGroup;
use App\Models\MemberShare;
use App\Models\ShareAmount;
use App\Models\BalanceSheet;
use App\Models\MemberResign;
use Illuminate\Http\Request;
use App\Models\LedgerAccount;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use App\Exports\AllMemberExport;
use Yajra\DataTables\DataTables;
use App\Models\MemberShareDetail;
use App\Traits\UpdateMemberShare;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Validation\Rules\Exists;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\StoreMemberRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateMemberRequest;

class MemberController extends Controller
{
    public $dirPath = '/images';
    use UpdateMemberShare;
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

    public function getmember_history(Member $member)
    {
        $data['member'] = $member;
        return response()->json(['success' => true, 'history' => view('member.history', $data)->render()]);
    }

    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // ************* import member images *****************
        // $members = Member::get();
        // // $notexist = [];
        // foreach ($members as $m) {
        //     $filename = $m->uid . '-P.jpg';
        //     if (file_exists(public_path() . '/Photo/' .  $filename)) {
        //         // dump();
        //         $m->profile_picture = $this->dirPath . '/profile_picture/' . $filename;
        //         $m->save();
        //     } else {
        //         $notexist = $m->name . '_' . $m->uid;
        //         // dump($notexist);
        //     }
        // }
        // ************* import member signature *************
        // $members = Member::get();
        // foreach ($members as $m) {
        //     $filename = $m->uid . '-S.jpg';
        //     if (file_exists(public_path() . '/Signature/' .  $filename)) {
        //         $m->signature = $this->dirPath . '/signature/' . $filename;
        //         $m->save();
        //     } else {
        //         $notexist = $m->name . '(M.no->' . $m->uid . ')';
        //         // dump($notexist);
        //     }
        // }
        //END
        // die();

        // dd($request->all());
        $data['page_title'] = __('View Members');
        // $data['departments'] = 1;
        $data['departments'] = Department::get();
        $data['members'] = Member::orderBy('uid', 'DESC')->get();

        if ($request->ajax()) {
            //   dd($request->all());
            $data = User::usermember()->with('member'); //->orderBy('id', 'DESC')
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $show_btn = '<a href="' . route('members.show', $row->member->id) . '"
                    class="btn btn-outline-info btn-sm"><i class="bi bi-eye-fill"></i> ' . __('Show') . '</a>';

                    $edit_btn = '&nbsp;<a href="' . route('members.edit', $row->member->id) . '"
                    class="btn btn-outline-warning btn-sm"><i class="bi bi-pencil-fill"></i> ' . __('Edit') . '</a>';

                    $delete_btn = '&nbsp;<form action="' . route('members.destroy', $row->member->id) . '" method="post">' . csrf_field() . method_field('DELETE') . '<button type="submit" class="btn btn-outline-danger btn-sm"
                    onclick="return confirm(`' . __('Do you want to delete this user?') . '`);"><i class="bi bi-trash-fill"></i> ' . __('Delete') . '</button></form>';

                    $change_pass_btn  = '&nbsp;<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePassword" onclick="set_member_id(' . $row->id . ')"><i class="bi bi-key"></i> ' . __('Password') . '</button>';                                  //$row->id

                    $resign_btn = ($row->member->getRawOriginal('status') == 1 ? '&nbsp;<button type="button" class="btn btn-outline-primary btn-sm" onclick="load_member_details(' . $row->member->id . ')" data-bs-toggle="modal" data-bs-target="#loan_settle"><i class="bi bi-r-circle-fill"></i> ' . __('Resign') . '</button>' : '');

                    $action_btn = '';

                    (Auth::user()->can('view-member')) ? $action_btn .= $show_btn : '';
                    (Auth::user()->can('edit-member') && ($row->member->getRawOriginal('status') == 1)) ? $action_btn .= $edit_btn : '';
                    (Auth::user()->can('delete-member')) ? $action_btn .= $delete_btn . $resign_btn : '';
                    (Auth::user()->can('edit-member')) ? $action_btn .= $change_pass_btn : '';
                    return $action_btn;
                })
                ->filterColumn('name', function ($query, $search) {
                    $query->where('id', $search);
                })
                ->filterColumn('registration_no', function ($query, $search) {
                    $query->whereHas('member', function ($q) use ($search) {
                        $q->where('registration_no', $search);
                    });
                })
                ->filterColumn('department_id', function ($query, $search) {
                    $query->whereHas('member', function ($q) use ($search) {
                        $q->where('department_id', $search);
                    });
                })
                // ->orderColumn('share_total_price', function ($query, $order) {
                //     $query->whereHas('member', function ($q) use ($order) {
                //         $q->orderBy('share_total_price', $order);
                //     });
                // })
                ->orderColumn('registration_no', function ($query, $order) {
                    $query->whereHas('member', function ($q) use ($order) {
                        $q->orderBy('registration_no', $order);
                    });
                })
                ->orderColumn('department_id', function ($query, $order) {
                    $query->whereHas('member', function ($q) use ($order) {
                        $q->orderBy('department_id', $order);
                    });
                })
                // ->editColumn('share_total_price', function ($row) {
                //     return $row->member->share_total_price;
                // })
                ->addColumn('uid', function ($row) {
                    return $row->member->uid;
                })
                ->filterColumn('uid', function ($query, $search) {
                    $query->whereHas('member', function ($q) use ($search) {
                        // dd($search);
                        $q->where('uid', 'like', $search);
                    });
                })
                ->editColumn('registration_no', function ($row) {
                    return $row->member->registration_no;
                })
                ->editColumn('department_id', function ($row) {
                    return $row->member->department->department_name ?? '';
                })
                ->addColumn('roles', function ($row) {
                    $roles = '';
                    foreach ($row->getRoleNames()->all() as $key => $value) {
                        $roles .= '<span class="badge bg-primary">' . $value . '</span>';
                    }
                    return $roles;
                })
                ->rawColumns(['action', 'roles'])
                ->make(true);
        }
        return view('member.index', $data);
    }

    public function create(): View
    {
        return view('member.create', [
            'departments' => Department::whereNotIn('id', ['5'])->get(),
            'minimum_share' => ShareAmount::where('is_active', 1)->pluck('minimum_share')->first(),
            'roles' => Role::pluck('name')->all(),
            'page_title' => __('Add Member')
        ]);
    }

    public function store(StoreMemberRequest $request): RedirectResponse
    {
        // dump($request->all());
        $input = $request->all();
        $input['minimum_share'] = $request->minimum_share;
        $input['password'] = Hash::make($request->password);
        $input['is_member'] = '1';
        $input['is_member'] = '1';
        $files = ['aadhar_card', 'profile_picture', 'pan_card', 'department_id_proof', 'signature', 'witness_signature'];

        foreach ($files as $key => $value) {
            if ($request->has($value)) {
                $image = $request->{$value};
                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($this->dirPath) . '/' . $value, $image_name);
                $input[$value] = $this->dirPath . '/' . $value . '/' . $image_name;
            }
        }

        $user = User::create($input);
        $user->assignRole(['user']);
        $input['user_id'] = $user->id;
        $input['uid'] = Member::latest()->pluck('uid')->first() ? Member::latest()->pluck('uid')->first() + 1 : 1;
        // dd($input['uid']);
        // die();

        $input['status'] = 1;

        if ($input['payment_type'] == 'cheque') {
            $input['payment_type_status'] =  bank_ledger_name() . '(Fee+Share). cheque-' . $input['cheque_no'];
            $input['ledger_group_id'] = 10;
            $ledger_account = LedgerAccount::where('ledger_group_id', 10)->first();
            $ledger_account->update(['current_balance' => ($ledger_account->current_balance + $input['total'])]);
        } else {
            $input['payment_type_status'] =  'Cash(Fee+Share)';
            $input['ledger_group_id'] = 4;
            $ledger_account = LedgerAccount::where('ledger_group_id', 4)->first();
            $ledger_account->update(['current_balance' => ($ledger_account->current_balance + $input['total'])]);
        }



        unset($input['name'], $input['email'], $input['search_terms'], $input['password'], $input['is_member']);
        $member = Member::create($input);

        for ($i = 1; $i <= 3; $i++) {
            $group = LedgerGroup::where('id', $i)->first();
            $ledger_entry = LedgerAccount::where('member_id', $member->id)->where('ledger_group_id', $i)->first();
            if (!$ledger_entry) {
                $ledger_entry = new LedgerAccount();
                $ledger_entry->ledger_group_id = $group->id;
                $ledger_entry->account_name = $user->name . '-' . $group->ledger_group;
                $ledger_entry->member_id =  $member->id;
                $ledger_entry->opening_balance = 0;
                $ledger_entry->type  = 'DR';
                $ledger_entry->year_id = currentYear()->id;
                $ledger_entry->created_by = 1;
                $ledger_entry->status = 1;
                $ledger_entry->save();
            }
        }

        $this->update_member_share($member, $member->total_share);

        return redirect()->route('members.index')
            ->withSuccess(__('New member is added successfully.'));
    }

    // public function member_share($member, $no_of_share)
    // {
    //     $exist_share = MemberShare::where('member_id', $member->id)->where('status', 1)->count();

    //     $new_share = $no_of_share - $exist_share;
    //     for ($i = 1; $i <= $new_share; $i++) {

    //         $count = MemberShare::count() + 1;
    //         $no = str_pad($count, 6, 0, STR_PAD_LEFT);
    //         // $no .= $count > 0 ? $count + 1 : 1;

    //         $share_entry = new MemberShare();
    //         $share_entry->ledger_account_id = $member->share_ledger_account->id ?? 0;
    //         $share_entry->member_id = $member->id;
    //         $share_entry->share_code = $no;
    //         $share_entry->share_amount = current_share_amount()->share_amount;
    //         $share_entry->year_id = $this->current_year->id;
    //         $share_entry->status = 1;
    //         $share_entry->purchase_on = date('Y-m-d');
    //         $share_entry->save();
    //     }
    //     // $share_total_price = MemberShare::where('member_id', $member->id)->where('status', 1)->first();

    //     // $member->share_total_price =  $member->share_total_price + ($new_share * current_share_amount()->share_amount);   //$share_total_price;
    //     $member->save();
    // }


    public function show(Request $request, Member $member): View
    {
        // opening balance set purpose
        // $member1 = Member::where('uid',267)->get()->first();
        //     // dd($members);
        //     for ($i = 1; $i <= 3; $i++) {
        //         $group = LedgerGroup::where('id', $i)->first();
        //         $ledger_entry = LedgerAccount::where('member_id', $member1->id)->where('ledger_group_id', $i)->first();
        //         if (!$ledger_entry) {
        //             $ledger_entry = new LedgerAccount();
        //             $ledger_entry->ledger_group_id = $group->id;
        //             $ledger_entry->account_name = $member1->user->name . '-' . $group->ledger_group;
        //             $ledger_entry->member_id =  $member1->id;
        //             $ledger_entry->opening_balance = 0;
        //             $ledger_entry->type  = 'DR';
        //             $ledger_entry->year_id = 1;
        //             $ledger_entry->created_by = 1;
        //             $ledger_entry->status = 1;
        //             $ledger_entry->save();
        //         }
        //     }
        //     $members = Member::where('uid',267)->get()->all();
        //     foreach ($members as $key => $member1) {
        //         if($member1->share_ledger_account){

        //             $member1->share_ledger_account->update([ 'opening_balance' =>$member1->share_total_price ?? '']);
        //         }else{
        //             dump($member);
        //         }
        //     }
        // end
        // $user = new User();
        // // $user->id = $row[1];
        // $user->name = 'BABARIYA NANJIBHAI R';
        // $user->email = 'BABARIYA NANJIBHAI R'.rand(11,99);
        // $user->password = Hash::make('rajkotpostalsoc12#');
        // $user->save();
        // $user->assignRole('User');

        // $member = new Member();
        // $member->user_id = $user->id;
        // $member->department_id = 1;
        // $member->registration_no = '11111111';
        // $member->uid =  '267';
        // $member->mobile_no =  11111111;
        // $member->whatsapp_no = 11111111;
        // $member->aadhar_card_no =  11111111;
        // $member->status = 0;
        // $member->save();

        if ($request->ajax()) {
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
            'shares' => MemberShare::where('member_id', $member->id)->get(),
            // dd(MemberShare::where('member_id',$member->id)->get()),
            'departments' => Department::whereNotIn('id', ['5'])->get(),
            'minimum_share' => ShareAmount::where('is_active', 1)->pluck('minimum_share')->first(),

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
        $files = ['aadhar_card', 'pan_card', 'profile_picture', 'department_id_proof', 'signature', 'witness_signature'];
        foreach ($files as $key => $value) {
            if ($request->has($value)) {
                $image = $request->{$value};
                $image_name = rand() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($this->dirPath) . '/' . $value, $image_name);
                $input[$value] = $this->dirPath . '/' . $value . '/' . $image_name;
            }
        }
        $member->user->update($input);
        unset($input['name'], $input['email'], $input['search_terms'], $input['password'], $input['is_member']);
        $member->update($input);
        // $member->syncRoles(['member']);
        $this->update_member_share($member, $member->total_share);

        // dd($member->share_total_price);

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

    /**
     * Display the specified resource.
     */

    public function resign(Request $request, Member $member)
    {
        // dd($request->all());

        // dd( $member->share_ledger_account());

        $request->validate([
            'total_amount' => 'required|integer|gt:0',
        ], [
            'total_amount.gt' => 'Note : You did not resign in a negative amount.',
        ]);

        $resign = new MemberResign();
        $resign->share_ledger_account_id = $member->share_ledger_account->id;
        $resign->fixed_ledger_account_id = $member->fixed_saving_ledger_account->id;
        $resign->loan_ledger_account_id = $member->loan_ledger_account->id;
        $resign->member_id = $member->id;
        $resign->principal_amount = $member->loan->principal_amt ?? 0;
        $resign->remaining_loan_amount = $member->loan_remaining_amount;
        $resign->share_amount = $member->share_ledger_account->current_balance;
        $resign->total_fixed_saving = $member->fixed_saving_ledger_account->current_balance;
        $resign->share_amount_used = isset($request->share_amount_check) ? 1 : 0;
        $resign->fixed_saving_used = isset($request->fixed_saving_check) ? 1 : 0;
        $resign->total_amount = $request->total_amount;
        $resign->payment_type = $request->payment_type;
        $resign->cheque_no = $request->cheque_no;

        if ($request->payment_type == 'cheque') {
            $resign->payment_type_status = bank_ledger_name() . 'cheque-' . $resign->cheque_no;
            $resign->ledger_group_id = 10;
            $resign->save();
            // $ledger_account = LedgerAccount::where('ledger_group_id', 10)->first();
            // $ledger_account->update(['current_balance' => ($ledger_account->current_balance +  $resign->total_amount)]);
        } else {
            $resign->payment_type_status = 'Cash';
            $resign->ledger_group_id = 4;
            $resign->save();
            // $ledger_account = LedgerAccount::where('ledger_group_id', 4)->first();
            // $ledger_account->update(['current_balance' => ($ledger_account->current_balance +  $resign->total_amount)]);
        }
        $resign->save();



        if (isset($request->share_amount_check)) {
            $shares = $member->shares()->get();
            foreach ($shares as $share) {

                $share->status = 0;
                $share->save();

                $share_detail_entry = new MemberShareDetail();
                $share_detail_entry->member_share_id = $share->id;
                $share_detail_entry->member_id = $share->member_id;
                $share_detail_entry->year_id = currentYear()->id;
                $share_detail_entry->is_sold = 1;
                $share_detail_entry->save();
            }
            $member->update(['total_share' => 0]);
            $member->share_ledger_account()->update(['current_balance' => 0]);

            $old_loan = LoanMaster::where('member_id', $member->id)->active()->first();
            if ($old_loan) {

                $old_loan->update(['status' => 3, 'loan_settlment_month' => date('Y-m-d')]);
                $old_loan->loan_emis()->where('status', 1)->update(['status' => 3]);
                $member->loan_ledger_account->update(['current_balance' => 0]);
            }
        }




        $member->update(['status' => 2]);
        return response()->json(['success' => true, 'member' => $member, 'message' => __('Member Resigned SuccessFully')]);
    }

    /**
     * Display the specified resource.
     */

    public function getmember(Member $member)
    {
        $member = Member::where('id', $member->id)->with(['loan', 'shares', 'loan.loan_emiss', 'loan.loan_emis' => function ($query) {
            $query->paid();
        }])->get()->first();
        $member->member_fixed_saving = $member->member_fixed;
        $member->loan_remaining_amount = $member->loan_remaining_amount;
        return response()->json(['success' => true, 'member' => $member]);
    }


    // public function all_member_export(Request $request)
    // {
    //     dd($request->all());
    //     return Excel::download(new AllMemberExport(), 'Member '.$this->current_year->title.'.xlsx');
    // }


    public function all_member_export(Request $request)
    {
        $query = Member::orderBy('uid', 'ASC');
        // dd($request);
        // if ($request->has('registration_no')) {
        //     $query->select('uid', 'registration_no');
        // }
        // if ($request->has('name')) {
        //     $query->select('uid', 'name');
        // }
        // if ($request->has('profile_picture')) {
        //     $query->select('uid');
        // }
        // if ($request->has('gender')) {
        //     $query->select('uid', 'gender');
        // }
        $data = $query->get();
        // dd( $data ->user->name);
        $columns = $request->columns;
        return Excel::download(new AllMemberExport($data,$columns), 'Member_' . $this->current_year->title . '.xlsx');
    }
}
