<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateSettingRequest;

class SettingController extends Controller
{
    protected $dirPath = 'images/setting/';

    /*check permission*/
    public function __construct()
    {
        $this->middleware('auth');
        // $this->middleware('permission:edit-setting');
        parent::__construct();
    }

    public function create(Setting $setting)
    {
        // $user = Auth::user();
        // $user->syncPermissions(Permission::pluck('name')->all());
        $data['setting'] = $setting;
        $data['page_title'] = __('Update Setting');
        return view('setting.create', $data);
    }

    public function store(UpdateSettingRequest $request, Setting $setting)
    {
        // dd($request->all());
        // $request->validate(['logo' => 'mimes:jpeg,jpg,png,gif|max:10000']);

        $input = $request->all();
        if ($request->has('logo')) {
            // dd($setting->logo);
            if (file_exists(public_path($setting->logo))) {
                @unlink(public_path($setting->logo));
            }
            $image = $request->file('logo');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $input['logo'] = $this->dirPath . $image_name;
        }
        if ($request->has('favicon')) {
            if (file_exists(public_path($setting->favicon))) {
                @unlink(public_path($setting->favicon));
            }
            $image = $request->file('favicon');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $input['favicon'] = $this->dirPath . $image_name;
        }
        // dd($input);
        $setting->update($input);
        return redirect()->back()
            ->withSuccess(__('Setting is updated successfully.'));
    }
}
