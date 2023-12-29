<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GlobalSetting;


class GlobalSettingController extends Controller
{
    protected $dirPath = 'admin/globalsetting/logo/';

    public function create(){
        $global = GlobalSetting::get()->first();
        return view('admin.globalsetting.create', compact('global'));
    }

    public function store(Request $request)
    {
        if($request->form_type == 'homepage'){
            $request->validate([
                'home_title'     => ['required'],
                'home_desc'      => ['required'],
                'image1'         => 'required_if:old_image1,=,null',
                'title1'         => ['required'],
                'sub_title1'     => ['required'],
                'icon_image1'    => 'required_if:old_icon_image1,=,null',
                'icon_title1'    => ['required'],
                'product_title1' => ['required'],
                'icon_desc1'     => ['required'],
                'icon_image2'    => 'required_if:old_icon_image2,=,null',
                'icon_title2'    => ['required'],
                'product_title2' => ['required'],
                'icon_desc2'     => ['required'],
                'icon_image3'    => 'required_if:old_icon_image3,=,null',
                'icon_title3'    => ['required'],
                'product_title3' => ['required'],
                'icon_desc3'     => ['required'],
                'icon_image4'    => 'required_if:old_icon_image4,=,null',
                'icon_title4'    => ['required'],
                'product_title4' => ['required'],
                'icon_desc4'     => ['required'],
            ]);
        }else{
            $request->validate([
                'favicon'    => 'required_if:old_favicon,=,null',
                'site_title' => ['required'],
                'logo'       => 'required_if:old_logo,=,null',
                'facebook'   => ['required'],
                'instagram'  => ['required'],
                'youtube'    => ['required'],
                'address'    => ['required'],
                'phone_no'   => ['required'],
                'mobile_no'  => ['required'],
                'mobile_no1' => ['required'],
                'email'      => ['required'],
                'footer_map' => ['required'],
                'copyright_msg' => ['required'],
            ]);
        }

        $global = GlobalSetting::firstOrNew(['id' => 1]);


// dd($request->all());

        //  **********images unlink purpose**********
                $req_all = $request->all();

                unset($req_all['icon_image4'],$req_all['favicon'],$req_all['logo'],$req_all['image1'],$req_all['icon_image1'],$req_all['icon_image2'],$req_all['icon_image3']);
                // dd($req_all);
                $global->update($req_all);

                // dd($global->toArray());
        //  *******************end*******************


        if ($request->has('favicon')) {

            if (file_exists(($global->favicon))) {
                @unlink(($global->favicon));
            }
            $image = $request->file('favicon');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            // dd($this->dirPath . $image_name);
            $global->favicon = $this->dirPath . $image_name;
        }

        if ($request->has('logo')) {
            if (file_exists(public_path($global->logo))) {
                @unlink(public_path($global->logo));
            }
            $image = $request->file('logo'); // name of your input field
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name); // for store in folder
            $global->logo = $this->dirPath . $image_name; // for store in database
        }

        if ($request->hasFile('image1')) {
            if (file_exists(public_path($global->image1))) {
                @unlink(public_path($global->image1));
            }
            $image = $request->file('image1');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $global->image1 = $this->dirPath . $image_name;
        }

        if ($request->has('icon_image1')) {
            if (file_exists(public_path($global->icon_image1))) {
                unlink(public_path($global->icon_image1));
            }
            $image = $request->file('icon_image1');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $global->icon_image1 = $this->dirPath . $image_name;
        }

        if ($request->has('icon_image2')) {
            if (file_exists(public_path($global->icon_image2))) {
                @unlink(public_path($global->icon_image2));
            }
            $image = $request->file('icon_image2');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $global->icon_image2 = $this->dirPath . $image_name;
        }

        if ($request->has('icon_image3')) {
            if (file_exists(public_path($global->icon_image3))) {
                @unlink(public_path($global->icon_image3));
            }
            $image = $request->file('icon_image3');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $global->icon_image3 = $this->dirPath . $image_name;
        }

        if ($request->has('icon_image4')) {
            if (file_exists(public_path($global->icon_image4))) {
                @unlink(public_path($global->icon_image4));
            }
            $image = $request->file('icon_image4');
            $image_name = rand() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($this->dirPath), $image_name);
            $global->icon_image4 = $this->dirPath . $image_name;
        }


        $global->save();

        return redirect()->back()->with('success', 'Setting updated');
    }

    public function homepage()
    {
        $global = GlobalSetting::get()->first();
        return view('admin.globalsetting.homepage.homepage', compact('global'));
    }



}
