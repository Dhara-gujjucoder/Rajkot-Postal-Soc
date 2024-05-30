<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AllMemberExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithStrictNullComparison
{




    public function collection()
    {
        $data = Member::orderBy('uid', 'ASC')->get();
        // $this->total_row = count($data) + 1;
        $data->push([]);
        return $data;
    }

    public function headings(): array
    {
        $heading = ['M.No', 'Registration no', 'Department name', 'Member name', 'Mobile', 'Email'];
        return $heading;
    }

    public function map($member): array
    {
        if (is_object($member)) {
            return [
                $member->uid,
                $member->registration_no ?? '-',
                $member->department->department_name ?? '-',
                $member->name,
                $member->mobile_no,
                $member->email ?? '-'
            ];
        }else {
            return [];
        }


    }

    public function styles(Worksheet $sheet)
    {

        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
        ];

        $substyle = [
            'alignment' => [
                // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                'wrapText' => true,
            ],
            'font' => [
                'bold'  =>  true,
            ],

        ];

        $sheet->getStyle('A1:F1')->applyFromArray($substyle);
        $sheet->getStyle('A1:F1')->applyFromArray($style);
    }

}
