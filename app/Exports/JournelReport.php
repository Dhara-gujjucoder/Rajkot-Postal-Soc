<?php

namespace App\Exports;

use App\Exports\JournelReportExport;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class JournelReport implements WithMultipleSheets
{
    use Exportable;
    protected $months;
    public function __construct(array $months)
    {
        $this->months = $months;
    }

    public function sheets(): array
    {
        $sheets = [];
        // dd( $this->months);
        foreach ($this->months as $key => $month) {
            $sheets[] = new JournelReportExport($month); //['value']
        }
        return $sheets;
    }
}
