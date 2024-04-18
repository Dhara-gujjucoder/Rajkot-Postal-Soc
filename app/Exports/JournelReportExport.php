<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\BulkEntry;
use App\Models\LoanMaster;
use App\Models\BulkEntryMaster;
use App\Models\MasterDoubleEntry;
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


        // $total[$key + 1] = ['LOAN SETTLEMENT  Pri.', '', 0, 0, 0, 0];
        $member_date = explode('-', $this->month);
        $other_exp_dobule = MasterDoubleEntry::whereMonth('date', $member_date[0])->whereYear('date', $member_date[1])->get()->count() + 2;

        $newData = collect([
            'summary' => ['department_total' => $total, 'loan_settlment' => $this->settlement, 'other_exp_dobule' => $other_exp_dobule]
        ]);

        $this->data = $this->data->concat($newData);

        return $this->data;
    }

    public function title(): string
    {
        return date('M-Y', strtotime('01-' . $this->month));
    }

    public function drawings()
    {
        $data = $this->data;
        $drawings = [];
        $rowIndex = 2;

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


            $entry = [
                [],
                [],
                ['', '', '', 'LOAN SETTLEMENT Pri.'],
                ['Sr.', 'M.No.', 'Emp.No.', 'Name', 'Rec.No.', 'Pri.Rs.', 'Int.Rs.', 'FS.Rs.', 'MS.', 'Total']
            ];

            $loan_settlement_amt = 0;

            foreach ($bulk_entry_master['loan_settlment'] as $key => $value) {
                $loan_settlement_amt = $loan_settlement_amt + $value->loan_settlement_amt;

                array_push($entry, [$key + 1,  $value->member->uid, $value->member->registration_no, $value->member->name, '', $value->loan_settlement_amt, '0', '0', '0', $value->loan_settlement_amt]);
            }

            $entry[] = [];
            $entry[] = ['', '', '', '', 'TOTAL: ', $loan_settlement_amt, '0', '0', '0', $loan_settlement_amt];


            // $other_expanse =
            // dd($this->month);
            $member_date = explode('-', $this->month);
            $month = $member_date[0];
            $year = $member_date[1];
            $member = Member::whereMonth('created_at', $month)->whereYear('created_at', $year);
            $member_fee = $member->sum('member_fee');
            $member_share = $member->sum('share_amt');
            $double_entry = MasterDoubleEntry::whereMonth('date', $month)->whereYear('date', $year)->get();

            array_push(
                $entry,
                [],
                [],
                [],
                ['', '', '', 'Other Expanses'],
            );
            $entry[] = ['', '', '', '', '', 'Credit', 'Debit', '', '', ''];
            $entry[] = ['', '', '', 'Member Fee', '', $member_fee, '0', '', '', ''];
            $entry[] = ['', '', '', 'Member Share Amount', '', $member_share, '0', '', '', ''];

            $credit = 0;
            $debit = 0;

            foreach ($double_entry as $key => $value) {
                $credit += $value->credit_amount;
                $debit += $value->debit_amount;

                $entry[] = ['', '', '', $value->description, '', $value->credit_amount, $value->debit_amount, '', '', ''];
            }
            $entry[] = [];
            $entry[] = ['', '', '', '', 'TOTAL: ', ($member_fee + $member_share) + ($credit), $debit, '', '', ''];


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


            return $entry;
        } else {

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
                ['Sr.', 'M.No.', 'Emp.No.', 'Name', 'Rec.No.', 'Pri.Rs.', 'Int.Rs.', 'FS.Rs.', 'MS.', 'Total']
            ];


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
                    $key + 1, $value->member->uid, $value->member->registration_no, $value->member->name ?? '', '', $value->principal, $value->interest, $value->fixed, $value->ms, $value->principal + $value->interest + $value->fixed + $value->ms,
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

        if ($data->count()) {

            foreach ($data as $key => $record) {
                if (!is_array($record) && $record->bulk_entries->count()) {

                    $sheet->mergeCells('D' . $rowIndex . ':H' . $rowIndex);
                    $sheet->mergeCells('D' . ($rowIndex + 1) . ':H' . ($rowIndex + 1));
                    $sheet->mergeCells('D' . ($rowIndex + 2) . ':H' . ($rowIndex + 2));
                    $range = 'B' . $rowIndex . ':E' . ($rowIndex + 4);

                    $sheet->getStyle($range)->applyFromArray($style);
                    $sheet->getStyle('A' . ($rowIndex + 5) . ':J' . ($rowIndex + 5))->applyFromArray($substyle);
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

                } elseif (is_array($record)) {
                    $line_of_total = $rowTotal + 1;

                    // Loan settlement Style
                    if ($record['loan_settlment']->count()) {
                        $line_of_total = $line_of_total + 2;

                        /*this is for loan settlement style*/
                        $sheet->getStyle('D' . ($line_of_total) . ':E' . ($line_of_total))->applyFromArray($substyle);

                        $sheet->getStyle('A' . ($line_of_total + 1) . ':J' . ($line_of_total + 1))->applyFromArray($substyle);
                        $sheet->getStyle('A' . ($line_of_total + 1) . ':J' . ($line_of_total + 1))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);
                        $line_of_total = $line_of_total + 3 + $record['loan_settlment']->count();

                        // dd($line_of_total);

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
                        $line_of_total = $line_of_total + 3;
                    } else {

                        // *******for Loan settlement*********
                        $sheet->getStyle('D' . ($line_of_total + 2) . ':E' . ($line_of_total + 2))->applyFromArray($substyle);
                        $sheet->getStyle('A' . ($line_of_total + 3) . ':J' . ($line_of_total + 3))->applyFromArray($substyle);
                        $sheet->getStyle('A' . ($line_of_total + 3) . ':J' . ($line_of_total + 3))->applyFromArray([
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
                        $line_of_total = $line_of_total + 8; // if loan settlement count is 0 than add space for it
                    }

                    // summry style
                    if ($record['other_exp_dobule'] > 0) {

                        //heading
                        $sheet->getStyle('D' . ($line_of_total + 1) . ':E' . ($line_of_total + 1))->applyFromArray($substyle);
                        $sheet->getStyle('D' . ($line_of_total + 2) . ':J' . ($line_of_total + 2))->applyFromArray($substyle);
                        $sheet->getStyle('D' . ($line_of_total + 2) . ':J' . ($line_of_total + 2))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);

                        //for total of other expense
                        $line_of_total =  $line_of_total + 3 + $record['other_exp_dobule'];
                        $sheet->getStyle('E' . ($line_of_total + 1) . ':J' . ($line_of_total + 1))->applyFromArray($substyle);
                        $sheet->getStyle('E' . ($line_of_total + 1) . ':J' . ($line_of_total + 1))->applyFromArray([
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
                    if (count($record['department_total'])+1) {

                        /* for summry  */
                        $sheet->getStyle('D' . ($line_of_total + 5) . ':E' . ($line_of_total + 5))->applyFromArray($substyle);
                        // $sheet->mergeCells('B' . ($line_of_total+4) . ':D' . ($line_of_total+4));
                        $sheet->getStyle('D' . ($line_of_total + 6) . ':J' . ($line_of_total + 6))->applyFromArray($substyle);
                        $sheet->getStyle('D' . ($line_of_total + 6) . ':J' . ($line_of_total + 6))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ],
                                'top' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                ]
                            ],
                        ]);

                        /*this is total oif summry */

                        $department_total = $line_of_total + 7 + count($record['department_total']);

                        $sheet->getStyle('E' . ($department_total + 2) . ':J' . ($department_total + 2))->applyFromArray($substyle);
                        $sheet->getStyle('E' . ($department_total + 2) . ':J' . ($department_total + 2))->applyFromArray([
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
