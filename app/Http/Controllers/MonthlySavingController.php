<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlySaving;

class MonthlySavingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['monthly_saving'] = MonthlySaving::get();
        $data['page_title'] = __('Monthly Saving');
        // dd($data['monthly_saving']);
        return view('setting.monthlysaving.index',$data);
    }

    public function create()
    {
        $data['page_title'] = __('Add New Monthly Saving');
        // dd($data['monthly_saving']);
        return view('setting.monthlysaving.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(['monthly_saving' => 'required']);

            $last = MonthlySaving::latest()->first();
            if($last){
                MonthlySaving::query()->update(['is_active' => false]);
                $last->update(['end_date'=> date('Y-m-d H:i:s')]);
            }
            MonthlySaving::create(['monthly_saving' => $request->monthly_saving,'is_active' => 1,'start_date' => date('Y-m-d H:i:s')]);
        
        return redirect()->route('monthlysaving.index')
        ->withSuccess(__('Setting is updated successfully.'));
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['monthly_saving'] = MonthlySaving::where('id',$id)->first();
        $data['page_title'] = __('Edit Monthly Saving');
        // dd($data['monthly_saving']);
        return view('setting.monthlysaving.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $request->validate(['monthly_saving' => 'required']);
        $monthly_saving = MonthlySaving::where('id',$id)->first();
        if($request->is_active == 1){
            MonthlySaving::query()->where('is_active',1)->update(['is_active' => false,'end_date' => date('Y-m-d H:i:s')]);
            $monthly_saving->update(['is_active' => true]);
        }
        $monthly_saving->update(['monthly_saving' => $request->monthly_saving]);
        return redirect()->route('monthlysaving.index')
        ->withSuccess(__('Setting is updated successfully.'));
    }

}
