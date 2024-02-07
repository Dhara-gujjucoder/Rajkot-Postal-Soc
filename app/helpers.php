<?php

use App\Models\Setting;
use App\Models\LedgerGroup;
use App\Models\ShareAmount;
use App\Models\FinancialYear;
use App\Models\LoanInterest;
use App\Models\MonthlySaving;

if (!function_exists('Setting')) {

    function getSetting()
    {
        $setting = Setting::first();
        return ($setting);
    }
}

if (!function_exists('currentYear')) {
    function currentYear()
    {
        return FinancialYear::where('is_current', 1)->first();
    }
}

if (!function_exists('current_share_amount')) {
    function current_share_amount()
    {
        return ShareAmount::where('is_active', 1)->first();
    }
}

if (!function_exists('current_loan_interest')) {
    function current_loan_interest()
    {
        return LoanInterest::where('is_active', 1)->first();
    }
}

if (!function_exists('getLoanParam')) {
    function getLoanParam()
    {
        return [
            0 => 30,
            1 => 365
        ];
    }
}

if (!function_exists('current_fixed_saving')) {
    function current_fixed_saving()
    {
        return MonthlySaving::where('is_active', 1)->first();
    }
}

if (!function_exists('getYear')) {
    function getYear($id, $month)
    {
        $year = FinancialYear::where('id', $id)->first();
        $a = $year->start_year . '-' . $year->start_month . '-01';
        $b = $year->end_year . '-' . $year->end_month . '-01';
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

if (!function_exists('getMonthsOfYear')) {
    function getMonthsOfYear($id)
    {
        $year = FinancialYear::where('id', $id)->first();
        $options = [];
        $a = $year->start_year . '-' . $year->start_month . '-01';
        $b = $year->end_year . '-' . $year->end_month . '-01';
        $period = \Carbon\CarbonPeriod::create($a, '1 month', $b);
        foreach ($period as $dt) {
            $options[] = ['id' => $year->id, 'value' => $dt->format("m-Y"), 'month' => $dt->format("M-Y")];
        }
        return $options;
    }
}

if (!function_exists('getNextNoOfMonths')) {
    function getNextNoOfMonths($no)
    {
        dd($no);
        $response = [];
        $month = date('m');
        $year = date('Y');
        for ($i = 0; $i <= $no; $i++) {
            if ($month === 12) {
                $month = 0;
                $year++;
            }
            $response[] = ($month + 1 + '-' + $year);
            $month++;
        }
        return $response;
    }
}

if (!function_exists('getLedgerGroupDropDown')) {
    function getLedgerGroupDropDown($is_selected = null)
    {
        $ledger_group_parent = LedgerGroup::whereNot('parent_id', '>', 0)->get()->all();
        $html = '';
        foreach ($ledger_group_parent as $key => $value) {
            $children = LedgerGroup::where('parent_id', $value->id)->get();
            if (count($children) > 0) {
                $html .= '<option value="' . $value->id . '" class="" ' . ($is_selected == $value->id ? 'selected="selected"' : '') . '>' . $value->ledger_group . '</option>';
                foreach ($children as $key => $ch) {
                    # code...
                    $html .= '<option value="' . $ch->id . '"  class="" ' . ($is_selected == $ch->id ? 'selected="selected"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $ch->ledger_group . '</option>';
                }
            } else {
                $html .= '<option value="' . $value->id . '" class="" ' . ($is_selected == $value->id ? 'selected="selected"' : '') . '>' . $value->ledger_group . '</option>';
            }
        }
        return $html;
    }
}
