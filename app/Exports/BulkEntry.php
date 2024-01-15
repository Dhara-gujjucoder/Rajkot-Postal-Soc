<?php

namespace App\Exports;

use App\Models\Department;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class BulkEntry implements FromCollection,WithMultipleSheets
{
    use Exportable;
    protected $month;

    public function __construct(string $month)
    {
        $this->month = $month;
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function sheets(): array
    {
        $sheets = [];
        $departments = Department::whereNot('id', 5)->get()->all();
        foreach ($departments as $key => $department) {
            $sheets[] = new BulkEntryPerDepartment($this->month,$department->id);
        }
        $sheets[] = new SummarySheet($this->month);
        return $sheets;
    }
}
