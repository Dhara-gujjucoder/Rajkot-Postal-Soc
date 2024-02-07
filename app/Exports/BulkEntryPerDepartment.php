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

class BulkEntryPerDepartment implements FromCollection, WithTitle, WithMapping, WithHeadings,WithColumnWidths,WithStyles
{
    private $month;
    private $department_id;
    public function __construct(string $month, int $department)
    {
        $this->month = $month;
        $this->department_id  = $department;
    }

    /**
     * @return Builder
     */
    public function collection()
    {
        $entries =  BulkEntryModel::query()
            ->where('month', $this->month)
            ->where('department_id', $this->department_id)->get();
            $entries->push(collect([
                'Total'=> 12,
                'principal_total' =>  $entries->sum('principal'),
                'interest_total' => $entries->sum('interest'),
                'fixed_total' => $entries->sum('fixed'),
                'ms_total' => $entries->sum('ms'),
                'total_amount' => $entries->sum('total_amount'),
            ]));
            // dd($entries[168]);
            return $entries;
    }
    public function map($bulk_entry): array
    {
        // dump($bulk_entry);
        if($bulk_entry['Total']){
            return [
                '',
                '',
                '',
                $bulk_entry['principal_total'],
                $bulk_entry['interest_total'],
                $bulk_entry['fixed_total'],
                $bulk_entry['ms_total'],
                $bulk_entry['total_amount'],
            ];
        }else{
            return [
                $bulk_entry->member->uid,
                $bulk_entry->member->user->name,
                $bulk_entry->master_entry->cheque_no ? $bulk_entry->rec_no : '',
                $bulk_entry->principal,
                $bulk_entry->interest,
                $bulk_entry->fixed,
                $bulk_entry->ms,
                $bulk_entry->total_amount,
            ];
        }
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $department_name = Department::where('id',$this->department_id)->pluck('department_name')->first();
        return $department_name;
    }
    public function headings(): array
    {
        return [
            'Member Id',
            'Name',
            'Rec.No.',
            'Principal',
            'Interest',
            'Fixed Saving',
            'MS',
            'Total'
        ];
    }
    public function columnWidths(): array
    {
        return [
            // 'A' => 10,
            'A' => 12,   
            'B' => 48,
            'C' => 10,
            'D' => 10,
            'E' => 10,
            'F' => 12,
            'G' => 10,
            'H' => 12,
        ];
    }
    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A'.$sheet->getHighestRow().':C'.$sheet->getHighestRow());

        $sheet->getStyle('A' . $sheet->getHighestRow() . ':'.$sheet->getHighestColumn().$sheet->getHighestRow())->getFont()->setBold(true);

        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]]
        ];
    }     
}
