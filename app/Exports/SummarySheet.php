<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use App\Models\BulkEntry as BulkEntryModel;
use App\Models\BulkEntryMaster;
use App\Models\Department;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummarySheet implements FromCollection, WithTitle, WithHeadings, WithColumnWidths, WithStyles
{
    private $month, $department_id;
    public function __construct(string $month)
    {
        $this->month = $month;
    }
    /**
     * @return Builder
     */
    public function collection()
    {
        $ledger_type = ['principal', 'interest', 'fixed', 'ms', 'total_amount'];
        $departments =  Department::whereNot('id', 5)->get();
        $sh  = collect([]);
        foreach ($departments as $key => $department) {
            $entries  = BulkEntryModel::query()
                ->where('month', $this->month)->get();
            $sh[$key] = collect([
                'no' => $key+1,
                'name' => $department->department_name,
                'principal' => $entries->where('department_id', $department->id)->sum('principal'),
                'interest' => $entries->where('department_id', $department->id)->sum('interest'),
                'fixed' => $entries->where('department_id', $department->id)->sum('fixed'),
                'ms' => $entries->where('department_id', $department->id)->sum('ms'),
                'total_amount' => $entries->where('department_id', $department->id)->sum('total_amount'),
            ]);

        }
        $sh[$key+1] = collect([
            'no' => '',
            'name' => 'Total',
            'principal' => $entries->sum('principal'),
            'interest' => $entries->sum('interest'),
            'fixed' => $entries->sum('fixed'),
            'ms' => $entries->sum('ms'),
            'total_amount' => $entries->sum('total_amount'),
        ]);
        //  $sh->push(collect([
        //         'Total' => 12,

        //     ]));
        // foreach ($ledger_type as $key => $ledger) {
        //     # code...
        //     $entries->push(collect([
        //         'Total' => 12,
        //         $ledger . '_total' =>  $entries->sum($ledger),
        //     ]));
        // }

        return $sh;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'SUMMARY';
    }
    public function headings(): array
    {
        return [
            'No.',
            'Department',
            'Principal',
            'Interest',
            'Fixed saving',
            'Ms',
            'Total',
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 48,
            'C' => 10,
            'D' => 10,
            'E' => 12,
            'F' => 10,
            'G' => 12,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A' . $sheet->getHighestRow() . ':D' . $sheet->getHighestRow());

        $sheet->getStyle('A' . $sheet->getHighestRow() . ':' . $sheet->getHighestColumn() . $sheet->getHighestRow())->getFont()->setBold(true);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }
}
