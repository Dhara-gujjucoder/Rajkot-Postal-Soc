<?php

use App\Models\FinancialYear;

if (! function_exists('currentYear')) {
    function currentYear() {
        return FinancialYear::where('is_selected')->pluck('year')->first();
    }
}
?>