<?php

use App\Models\FinancialYear;

if (! function_exists('currentYear')) {
    function currentYear() {
        return FinancialYear::where('is_selected',1)->pluck('year')->first();
    }
}

if (! function_exists('getYear')) {
    function getYear($id,$month) {
        $year = FinancialYear::where('id',$id)->first();
        $a = $year->start_year.'-'.$year->start_month.'-01';
        $b = $year->end_year.'-'.$year->end_month.'-01';
        $period = \Carbon\CarbonPeriod::create($a, '1 month', $b);
        foreach ($period as $dt) {
            $options[] = $dt->format("m-Y");
        }
        // date('m-Y'.strtotime($month.'-'));
        // dd($options[$month]);
        return $year->start_year;
        // return FinancialYear::where('id',$id)->pluck('year')->first();
    }
}

if (! function_exists('getYearDropDown')) {
    function getYearDropDown($id) {
        $year = FinancialYear::where('id',$id)->first();
        $options = [];
        $a = $year->start_year.'-'.$year->start_month.'-01';
        $b = $year->end_year.'-'.$year->end_month.'-01';
        $period = \Carbon\CarbonPeriod::create($a, '1 month', $b);
        foreach ($period as $dt) {
            $options[] = ['id'=> $year->id,'value'=> $dt->format("m-Y"), 'month' => $dt->format("M-Y")];
        }
        return $options;
    }
}



?>