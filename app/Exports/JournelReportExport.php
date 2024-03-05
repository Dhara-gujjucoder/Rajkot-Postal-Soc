<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\BulkEntry;
use App\Models\BulkEntryMaster;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithTitle;

class JournelReportExport implements FromCollection, WithTitle, WithMapping, ShouldAutoSize, WithStrictNullComparison, WithStyles, WithDrawings
{
    protected $month, $data, $month_from, $month_to;

    public function __construct($month)
    {
        $this->month = $month;
        // $this->request = request();

    }

    public function collection()
    {
        $this->data = BulkEntryMaster::where('month', $this->month)->orderBy('id', 'desc')->get();
        return $this->data;
    }

    public function title(): string
    {
        // dd($this->month);
        return date('M-Y', strtotime('01-' . $this->month));
    }

    public function drawings()
    {
        $data = $this->data;
        $drawings = [];
        $rowIndex = 2;
        // dd($data);
        foreach ($data as $key => $record) {
            if ($record->bulk_entries->count()) {

                $drawing = new Drawing();
                $drawing->setPath(public_path(getSetting()->logo));
                $drawing->setHeight(30);
                $drawing->setWidth(100);
                // $drawing->setCoordinates('K'.($key+31));
                $drawing->setCoordinates('A' . $rowIndex);

                $records = $record->bulk_entries->count();

                $rowIndex +=  $records + 11;
                // $rowIndex += 19;

                $drawings[] = ($drawing);
            }
            // if (isset($record->ledger_group_id)) {
            // }
        }
        return $drawings;
    }

    public function map($bulk_entry_master): array
    {
        // dd($bulk_entry->bulk_entries->get());
        // if(isset($bulk_entry['dept'])){
        //     $entry[] = ['dept'];
        //     return $entry;
        // }else{

        if (!$bulk_entry_master->bulk_entries->count()) {
            $entry = [];
            return $entry;
        }

        $entry = [
            [],
            [],
            ['', '', '', getSetting()->title],
            ['', '', '', getSetting()->address],
            ['', '', '', ('Dept: ' . $bulk_entry_master->department->department_name)],
            [],
            [],
            ['', 'Sr.', 'M.No.', 'Name', 'Rec.No.', 'Pri.Rs.', 'Int.Rs.', 'FS.Rs.', 'MS', 'Total']
        ];

        // dd($bulk_entry_master->members);
        $total = 0;
        $principal = 0;
        $fixed = 0;
        $interest = 0;
        $ms = 0;

        foreach ($bulk_entry_master->bulk_entries as $key => $value) {
            $total = $total + $value->principal + $value->interest + $value->fixed + $value->ms;
            $principal += $value->principal;
            $fixed += $value->fixed;
            $interest += $value->interest;
            $ms += $value->ms;

            $entry[] = [
                '', $key + 1, $value->member->uid, $value->member->name ?? '', '', $value->principal, $value->interest, $value->fixed, $value->ms,  $value->principal + $value->interest + $value->fixed + $value->ms,
                // $bulk_entry_master->rec_no, <=== 'Rec.No.'
            ];
        }

        $entry[] = [];
        $entry[] = ['', '', '', '', 'Total', $principal, $interest, $fixed, $ms, $total];
        $entry[] = [];
        return $entry;
    }

    public function styles(Worksheet $sheet)
    {
        $data = $this->data;
        $rowIndex = 3;
        $rowTotal = 0;

        if ($data->count()) {
            $style = [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'font' => [
                    'bold' => true,
                    'size' => 14,
                ],
            ];

            $substyle = [
                'alignment' => [
                    // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                    'wrapText' => true,
                ],
                'font' => [
                    'bold' => true,
                ],
            ];

            foreach ($data as $key => $record) {
                if ($record->bulk_entries->count()) {

                    // $sheet->mergeCells('A'.$sheet->getHighestRow().':C'.$sheet->getHighestRow());
                    $sheet->mergeCells('D' . $rowIndex . ':H' . $rowIndex);
                    $sheet->mergeCells('D' . ($rowIndex + 1) . ':H' . ($rowIndex + 1));
                    $sheet->mergeCells('D' . ($rowIndex + 2) . ':H' . ($rowIndex + 2));
                    $range = 'B' . $rowIndex . ':E' . ($rowIndex + 4);
                    // dd($range);

                    $sheet->getStyle($range)->applyFromArray($style);
                    // $sheet->getStyle('E' . ($rowIndex + 17) . ':J' . ($rowIndex + 17))->applyFromArray($substyle);
                    // $sheet->getStyle('C' . ($rowIndex + 6) . ':C' . ($rowIndex + 8))->applyFromArray($substyle);
                    // $sheet->getStyle('E'. ($rowIndex+25))->applyFromArray($substyle);
                    // $sheet->getStyle('F'. ($rowIndex+25))->applyFromArray($substyle);


                    $sheet->getStyle('B' . ($rowIndex + 5) . ':J' . ($rowIndex + 5))->applyFromArray($substyle);
                    $sheet->getStyle('A' . ($rowIndex + 5) . ':J' . ($rowIndex + 5))->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'top' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                        ],
                    ]);

                    $records = $record->bulk_entries->count();
                    $rowIndex +=  $records + 11;

                    // dd($records);

                    $rowTotal = ($rowTotal + $records + 11);

                    $sheet->getStyle('E' . ($rowTotal - 1) . ':J' . ($rowTotal - 1))->applyFromArray($substyle);
                    $sheet->getStyle('E' . ($rowTotal - 1) . ':J' . ($rowTotal - 1))->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ],
                            'top' => [
                                'borderStyle' => Border::BORDER_THIN,
                            ]
                        ],
                    ]);
                }
            }
        }
        // $sheet->getStyle('B6:B10')->getFont()->setBold(true);
        // $sheet->getStyle('B6:B10')->getFont()->setBold(true);
        // $sheet->getStyle('A13:A15')->getFont()->setBold(true);
        // $sheet->getStyle('A16:F16')->applyFromArray([
        //     'borders' => [
        //         'bottom' => [
        //             'borderStyle' => Border::BORDER_THIN,
        //         ],
        //         'top' => [
        //             'borderStyle' => Border::BORDER_THIN,
        //         ]
        //     ],
        // ]);
        // $sheet->getStyle('A' . $sheet->getHighestRow() . ':' . $sheet->getHighestColumn() . $sheet->getHighestRow())->getFont()->setBold(true);
    }
}
