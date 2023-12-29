<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public $dirPath = '/images';
    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-user|edit-user|delete-user|view-user', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-user', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-user', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-user', ['only' => ['destroy']]);
        $this->middleware('permission:view-user', ['only' => ['show', 'index']]);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('users.index', [
            'users' => User::admin()->latest('id')->paginate(10),
            'page_title' => __('View Users')
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create', [
            'roles' => Role::pluck('name')->all(),
            'page_title' => __('Add User')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['is_member'] = '0';
        // $files = ['aadhar_card', 'pan_card',' department_id_proof','signature','witness_signature'];
        // foreach ($files as $key => $value) {
        //     if ($request->has($value)) {
        //         $image = $request->{$value};
        //         $image_name = rand() . '.' . $image->getClientOriginalExtension();
        //         $image->move(public_path($this->dirPath).'/'.$value, $image_name);
        //         $input[$value] = $this->dirPath .'/'.$value.'/'.$image_name;
        //     }
        // }
        $user = User::create($input);
        $user->assignRole(['Admin']);

        return redirect()->route('users.index')
            ->withSuccess(__('New user is added successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        return view('users.show', [
            'user' => $user,
            'page_title' => __('View User')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        // Check Only Super Admin can update his own Profile
        if ($user->hasRole('Super Admin')) {
            if ($user->id != auth()->user()->id) {
                abort(403, __('USER DOES NOT HAVE THE RIGHT PERMISSIONS'));
            }
        }

        return view('users.edit', [
            'user' => $user,
            'roles' => Role::pluck('name')->all(),
            'userRoles' => $user->roles->pluck('name')->all(),
            'page_title' => __('Edit User')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $input = $request->all();

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            $input = $request->except('password');
        }
    
        $user->update($input);
        // $user->syncRoles(['user']);

        return redirect()->route('users.index')
            ->withSuccess(__('User is updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // About if user is Super Admin or User ID belongs to Auth User
        if ($user->hasRole('Super Admin') || $user->id == auth()->user()->id) {
            abort(403, __('USER DOES NOT HAVE THE RIGHT PERMISSIONS'));
        }

        $user->syncRoles([]);
        $user->delete();
        return redirect()->route('users.index')
            ->withSuccess(__('User is deleted successfully.'));
    }


     /**
     * Display the specified resource for profile.
     */
    public function profile(User $user): View
    {
        // dd($user);
        return view('users.profile', [
            'user' => $user,
            'page_title' => __('User Profile')
        ]);
    }

     /**
     * Display the specified resource via profile.
     */
    public function updateProfile(UpdateUserRequest $request,User $user): RedirectResponse
    {
        $input = $request->all();
        // dd($input);

        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        } else {
            $input = $request->except('password');
        }
    
        $user->update($input);
        // dd( $user);
        return redirect()->back()
            ->withSuccess(__('Profile is updated successfully.'));
    }

    
}
