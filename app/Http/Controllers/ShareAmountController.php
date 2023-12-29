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
        $request->validate(['share_amount' => 'required']);

            $last = ShareAmount::latest()->first();
            if($last){
                ShareAmount::query()->update(['is_active' => 0]);
                $last->update(['end_date'=> date('Y-m-d H:i:s')]);
            }
            ShareAmount::create(['share_amount' => $request->share_amount,'is_active' => 1,'start_date' => date('Y-m-d H:i:s')]);
        
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
        $share_amount = ShareAmount::where('id',$id)->first();
        if($request->is_active == 1){
            ShareAmount::query()->where('is_active',1)->update(['is_active' => 0,'end_date' => date('Y-m-d H:i:s')]);
            $share_amount->update(['is_active' => 1]);
        }
        $share_amount->update(['share_amount' => $request->share_amount]);
        return redirect()->route('shareamount.index')
        ->withSuccess(__('Setting is updated successfully.'));
    }
}
