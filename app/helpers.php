<?php

use Carbon\Carbon;
use App\Models\Member;
use App\Models\LoanEMI;
use App\Models\Setting;
use App\Models\LedgerGroup;
use App\Models\ShareAmount;
use App\Models\LoanInterest;
use App\Models\FinancialYear;
use App\Models\MonthlySaving;
use App\Models\MemberFixedSaving;
use Illuminate\Support\Collection;


if (!function_exists('getSetting')) {
    function getSetting(){
        $setting = Setting::first();
        return $setting;
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
        // dd($no);
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

    if (!function_exists('loan_remaining_amount')) {
        function loan_remaining_amount($member_id)
        {
            // dd($member_id);
            return LoanEMI::where('member_id', $member_id)->pending()->sum('emi');
        }
    }


    if (!function_exists('remaining_fixed_saving')) {
        function remaining_fixed_saving($member_id)
        {
            $fixed_saving = MemberFixedSaving::where('member_id', $member_id)->pluck('fixed_amount')->all();
            $all_year = FinancialYear::where('end_year', '<=', date('Y'))->pluck('id')->all();
            $merged_collection = new Collection();
            // dd($all_year);
            // foreach ($all_year as $key => $yearvalue) {
            $months = collect(getMonthsOfYear(currentYear()->id));
            $merged_collection = $merged_collection->merge($months);
            // }
            // dd($merged_collection);
            foreach ($merged_collection->pluck('value') as $key => $value) {
                $date = Carbon::parse('01-' . $value);

                if ($date->lessThan(date('d-m-Y'))) {
                    $final[] = $date->format('m-Y');
                }
            }
            // dd( $final);
            // dd($merged_collection->whereIn('value',$final));
            // $required_amount = count($final)*current_fixed_saving()->monthly_saving;
            // dd($final);
            $member_fixed_Saving = MemberFixedSaving::where('member_id', $member_id)->whereIn('month', $final);
            // $nil_entries =  $member_fixed_Saving->where('fixed_amount',0)->count();
            $saving_amount = $member_fixed_Saving->withoutGlobalScope('bulk_entry')->sum('fixed_amount');
            // dd($member_fixed_Saving->get());
            $required_amount = $member_fixed_Saving->groupBy('month')->get(['month'])->count() * current_fixed_saving()->monthly_saving;
            //    dd( $saving_amount);
            // $member = Member::find($member_id);
            // $count = count(getMonthsOfYear(currentYear()->id));
            return  $required_amount - $saving_amount > 0 ? $required_amount - $saving_amount : 0;
        }
    }
}
