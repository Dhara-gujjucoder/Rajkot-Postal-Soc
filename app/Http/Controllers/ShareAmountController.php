<?php

namespace App\Http\Controllers;

use App\Models\ShareAmount;
use Illuminate\Http\Request;

class ShareAmountController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['share_amount'] = ShareAmount::get();
        $data['page_title'] = __('Share Amount');
        // dd($data['share_amount']);
        return view('setting.shareamount.index',$data);
    }

    public function create()
    {
        $data['share_amount'] = ShareAmount::get();
        $data['page_title'] = __('Add New Share Amount');
        // dd($data['share_amount']);
        return view('setting.shareamount.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['share_amount' => 'required', 'minimum_share' => 'required']);

            $last = ShareAmount::latest()->first();
            if($last){
                ShareAmount::query()->update(['is_active' => false]);
                $last->update(['end_date'=> date('Y-m-d H:i:s')]);
            }
            ShareAmount::create(['share_amount' => $request->share_amount,'minimum_share' => $request->minimum_share,'is_active' => true,'start_date' => date('Y-m-d H:i:s')]);

        return redirect()->route('shareamount.index')
        ->withSuccess(__('Setting is updated successfully.'));
    }
     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['share_amount'] = ShareAmount::where('id',$id)->first();
        $data['page_title'] = __('Edit Share Amount');
        // dd($data['share_amount']);
        return view('setting.shareamount.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['share_amount' => 'required', 'minimum_share' => 'required']);
        $share_amount = ShareAmount::where('id',$id)->first();
        if($request->is_active == 1){
            ShareAmount::query()->where('is_active',true)->update(['is_active' => false,'end_date' => date('Y-m-d H:i:s')]);
            $share_amount->update(['is_active' => true]);
        }
        $share_amount->update(['share_amount' => $request->share_amount,'minimum_share' => $request->minimum_share ]);
        return redirect()->route('shareamount.index')
        ->withSuccess(__('Setting is updated successfully.'));
    }
}
