<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\BulkEntry;
use App\Models\LoanMaster;
use App\Models\BulkEntryMaster;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class JournelReportExport implements FromCollection, WithTitle, WithMapping, ShouldAutoSize, WithStrictNullComparison, WithStyles, WithDrawings
{
    // public $index = 0, $total_row = 0;
    protected $month, $data, $month_from, $month_to, $settlement;

    public function __construct($month)
    {
        $this->month = $month;
        // $this->request = request();

    }

    public function collection()
    {
        $this->data = BulkEntryMaster::where('month', $this->month)->orderBy('id', 'desc')->get();
        // dd($this->month);
        $this->settlement = LoanMaster::where('loan_settlment_month', 'like', '%' . $this->month)->get();
        $total = [];

        foreach ($this->data as $key => $value) {
            // dd($value->department->department_name);
            $total[$key] = [
                $value->department->department_name,
                $value->bulk_entries->sum('principal') + $value->bulk_entries->sum('interest') + $value->bulk_entries->sum('fixed') + $value->bulk_entries->sum('ms'),
                $value->bulk_entries->sum('principal'),
                $value->bulk_entries->sum('interest'),
                $value->bulk_entries->sum('fixed'),
                $value->bulk_entries->sum('ms')
            ];
            // $value->department_total
            // $total[$key] = [$value->department->department_name,$value->department_total];
        }

        //    dd($this->settlement[0]->member->name);
        // $total[$key + 1] = ['LOAN SETTLEMENT  Pri.', '', 0, 0, 0, 0];
        // dd($total);

        $newData = collect([
            'summary' => ['department_total' => $total, 'loan_settlment' => $this->settlement]
        ]);

        $this->data = $this->data->concat($newData);
        // dd($this->data);
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
            if (!is_array($record) && $record->bulk_entries->count()) {

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
        // $this->index++;
        if (is_array($bulk_entry_master)) {

            // dd($bulk_entry_master);
            $entry = [
                [],
                [],
                ['', '', '', 'LOAN SETTLEMENT Pri.'],
                ['', 'Sr.', 'M.No.', 'Name', 'Rec.No.', 'Pri.Rs.', 'Int.Rs.', 'FS.Rs.', 'MS.', 'Total']
            ];

            $loan_settlement_amt = 0;

            foreach ($bulk_entry_master['loan_settlment'] as $key => $value) {
                $loan_settlement_amt = $loan_settlement_amt + $value->loan_settlement_amt;
                // dd( $value->member->uid);
                array_push($entry, ['', $key + 1, $value->member->uid,  $value->member->name, '', $value->loan_settlement_amt, '0', '0', '0', $value->loan_settlement_amt]);
            }

            $entry[] = [];
            $entry[] = ['', '', '', '', 'TOTAL: ', $loan_settlement_amt, '0', '0', '0', $loan_settlement_amt];

            array_push(
                $entry,
                [],
                [],
                [],
                ['', '', '', 'SUMMARY'],
                ['', '', '', '', '', 'PRI.', 'INT.', 'FB', 'MS.', 'Total']
                // ['','','','',$bulk_entry_master['departments'][0]],
            );

            $pri = 0;
            $int = 0;
            $fix = 0;
            $m_s = 0;
            $total = 0;

            foreach ($bulk_entry_master['department_total'] as $key => $value) {
                // dd( $value[2]);
                $pri += $value[2];
                $int += $value[3];
                $fix += $value[4];
                $m_s += $value[5];
                $total = $pri + $int + $fix + $m_s;

                array_push($entry, ['', '', '', $value[0], '', $value[2], $value[3], $value[4], $value[5], $value[1]]);
            }
            array_push($entry, ['', '', '', 'LOAN SETTLEMENT Pri.', '', $loan_settlement_amt, '0', '0', '0', $loan_settlement_amt]);

            $entry[] = [];
            $entry[] = ['', '', '', '', 'TOTAL: ', $pri + $loan_settlement_amt,  $int, $fix, $m_s, $total + $loan_settlement_amt];

            // dd($entry);
            return $entry;
        } else {
            // dd($bulk_entry_master);
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
                ['', 'Sr.', 'M.No.', 'Name', 'Rec.No.', 'Pri.Rs.', 'Int.Rs.', 'FS.Rs.', 'MS.', 'Total']
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
            $entry[] = ['', '', '', '', 'TOTAL: ', $principal, $interest, $fixed, $ms, $total];
            $entry[] = [];

            return $entry;
        }
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
                if (!is_array($record) && $record->bulk_entries->count()) {

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
                    // dd($rowTotal);

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
                } elseif (is_array($record)) {
                    // dd($record['loan_settlment']);
                    // dump($rowTotal); //641, 183, 264
                    $line_of_total = $rowTotal + 1;



                    // dd($line_of_total);

                    if ($record['loan_settlment']->count()) {

                        /*this is for loan settlement style*/
                        $sheet->getStyle('D' . ($line_of_total + 2) . ':E' . ($line_of_total + 2))->applyFromArray($substyle);

                        $sheet->getStyle('B' . ($line_of_total + 3) . ':J' . ($line_of_total + 3))->applyFromArray($substyle);
                        $sheet->getStyle('B' . ($line_of_total + 3) . ':J' . ($line_of_total + 3))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);

                        $line_of_total = $rowTotal + 6 + $record['loan_settlment']->count();

                        $sheet->getStyle('E' . ($line_of_total) . ':J' . ($line_of_total))->applyFromArray($substyle);
                        $sheet->getStyle('E' . ($line_of_total) . ':J' . ($line_of_total))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);
                    } else {
                        // *******for Loan settlement*********
                        $sheet->getStyle('D' . ($line_of_total + 2) . ':E' . ($line_of_total + 2))->applyFromArray($substyle);
                        $sheet->getStyle('B' . ($line_of_total + 3) . ':J' . ($line_of_total + 3))->applyFromArray($substyle);
                        $sheet->getStyle('B' . ($line_of_total + 3) . ':J' . ($line_of_total + 3))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);
                        $sheet->getStyle('E' . ($line_of_total + 5) . ':J' . ($line_of_total + 5))->applyFromArray($substyle);
                        $sheet->getStyle('E' . ($line_of_total + 5) . ':J' . ($line_of_total + 5))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);

                        // *******for Summery style*********
                        $sheet->getStyle('D' . ($line_of_total + 9) . ':E' . ($line_of_total + 9))->applyFromArray($substyle);
                        $sheet->getStyle('B' . ($line_of_total + 10) . ':J' . ($line_of_total + 10))->applyFromArray($substyle);
                        $sheet->getStyle('B' . ($line_of_total + 10) . ':J' . ($line_of_total + 10))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);
                        $sheet->getStyle('E' . ($line_of_total + 18) . ':J' . ($line_of_total + 18))->applyFromArray($substyle);
                        $sheet->getStyle('E' . ($line_of_total + 18) . ':J' . ($line_of_total + 18))->applyFromArray([
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

                    // dump('ss'.$line_of_total);
                    // dd($line_of_total);

                    if (count($record['department_total']) && count($record['loan_settlment'])) {
                        $sheet->getStyle('D' . ($line_of_total + 4) . ':E' . ($line_of_total + 4))->applyFromArray($substyle);
                        // $sheet->mergeCells('B' . ($line_of_total+4) . ':D' . ($line_of_total+4));

                        $sheet->getStyle('F' . ($line_of_total + 5) . ':J' . ($line_of_total + 5))->applyFromArray($substyle);
                        $sheet->getStyle('B' . ($line_of_total + 5) . ':J' . ($line_of_total + 5))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);

                        /*this is for loan settlement style*/
                        // dd($line_of_total , 2 , count($record['department_total']));
                        $department_total = $line_of_total + 8 + count($record['department_total']);
                        $sheet->getStyle('E' . ($department_total) . ':J' . ($department_total))->applyFromArray($substyle);
                        $sheet->getStyle('E' . ($department_total) . ':J' . ($department_total))->applyFromArray([
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
        }
    }






    // **************************************************




    // **************************************************


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
