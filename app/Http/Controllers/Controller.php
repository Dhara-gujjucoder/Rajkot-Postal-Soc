<?php

namespace App\Http\Controllers;

use View;
use Illuminate\Http\Request;
use App\Models\FinancialYear;
use App\Models\Setting;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public $financial_year,$current_year,$setting;

    public function __construct() 
    {
        // Fetch the Site Settings object
        $this->financial_year = FinancialYear::active()->get();
        // $current_year = $this->financial_year->where('is_current',1)->first();
        $this->current_year = $this->financial_year->where('is_current',1)->first();
        $this->setting =  Setting::get()->first();
        // dd( $this->financial_year );
        $data = array(
            'financial_year' => $this->financial_year,
            'current_year' => $this->current_year,
            'setting' => $this->setting,
        );
        View::share($data);
    }
}
