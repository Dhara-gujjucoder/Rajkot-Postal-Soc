<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\LedgerAccount;
use App\Models\MemberFixedSaving;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AllLedgerFixedSavingExport implements FromCollection, WithMapping, ShouldAutoSize, WithStrictNullComparison, WithStyles, WithDrawings
{
    protected $main_total = [];
    protected $from, $to, $month_from, $month_to, $data;

    public function __construct($from, $to, $month_from, $month_to)
    {
        // dump($month_from, $month_to);
        // dd(getMonthsOfYear(currentYear()->id));
        $result[] = '';
        $all_months = getMonthsOfYear(currentYear()->id);
        // dump($all_months);
        foreach ($all_months as $key => $value) {
            if ($key <= $month_to && $key >= $month_from) {
                $result[] = $value;
            }

            // $result = array_slice($array, $month_from, $month_to-$month_from);
        }
            // $result = array_slice($array, $month_from, $month_to-$month_from);
            // dd($array,  $month_from,  $month_to,$result);
        $months = collect($result)->pluck('value');

        $this->main_total['saving'] = 0;
        $this->main_total['balance'] = 0;
        $this->main_total['total_balance'] = 0;
        $this->from = $from;
        $this->to = $to;
        $this->month_from = $month_from;
        $this->month_to = $month_to;

        DB::enableQueryLog();
        // dump( [$from, $to]);

        $rr = collect([$from, $to]);
        $sorted = $rr->sortBy(function ($item, $key) {
            return $item;
        })->toArray();
        // dd( $sorted);

        $this->data = LedgerAccount::where('ledger_group_id', 1)
            ->when($this->from && $this->to, function ($q) use ($from, $to,$sorted) {
                $q->whereHas('member', function ($query) use ($from, $to,$sorted) {
                    return $query->whereBetween('uid', [$sorted]);
                });
            })
            ->when($this->from && !$this->to, function ($q) use ($from) {
                $q->whereHas('member', function ($query) use ($from) {
                    return $query->where('uid', $from);
                });
            })
            ->when(!$this->from && $this->to, function ($q) use ($to) {
                $q->whereHas('member', function ($query) use ($to) {
                    return $query->where('uid', $to);
                });
            })

            ->when($this->month_from || $this->month_to, function ($q) use ($months,$all_months) {
                $q->with(['member.fixed_saving' => function ($query) use ($months,$all_months) {

                    if ($this->month_from != null && $this->month_to != null) {
                        $query->whereIn('month', $months->toArray());
                    }
                    elseif ($this->month_from && $this->month_to === null) {
                        $query->where('month', $all_months[$this->month_from]['value']);
                    }
                    elseif ($this->month_from === null && $this->month_to) {
                        $query->where('month', $all_months[$this->month_to]['value']);
                    }
                }]);
            })
            ->get();
            // dd(DB::getQueryLog());
    }

    public function collection()
    {
        $coll = $this->data;
        $coll->push($this->main_total);
        return $coll;
    }

    public function drawings()
    {
        $data = $this->data;
        $drawings = [];
        $rowIndex = 6;
        foreach ($data as $key => $record) {
            if (isset($record->ledger_group_id)) {
                $drawing = new Drawing();
                $drawing->setPath(public_path(getSetting()->logo));
                $drawing->setHeight(30);
                $drawing->setWidth(100);
                // $drawing->setCoordinates('K'.($key+31));
                $drawing->setCoordinates('A' . $rowIndex);

                $records = $record->member->fixed_saving->count();

                $rowIndex +=  $records + 19;

                // $rowIndex += 19;

                $drawings[] = ($drawing);
            }
        }
        return $drawings;
    }

    public function map($ledger_account): array
    {
        // $this->main_total['saving'] = 0;
        // $this->main_total['balance'] = 0;
        $entry = [];
        // dd($ledger_account);
        if (!isset($ledger_account['total_balance'])) {
            $entry = [
                [], [], [], [],
                [],
                ['', getSetting()->title],
                ['', getSetting()->address],
                ['', 'REG. NO.' . $ledger_account->member->registration_no],
                [], [], [],
                ['Name :', $ledger_account->member->name, 'Member No.', $ledger_account->member->uid],
                ['Address :', $ledger_account->member->permanent_address, 'Ledger', $ledger_account->account_name], //current_address
                ['Period', currentYear()->title, 'F.Y', currentYear()->title], [],

                ['Date', 'L/F', 'Particular', 'DR', 'CR', 'Balance'],

                [date('Y-m-d'), '', 'Opening balance', '', '', $ledger_account->opening_balance],

            ];

            // $opening_balance += $ledger_account->opening_balance;
            $fixed_saving = $ledger_account->member->fixed_saving;
            $total = 0;

            foreach ($fixed_saving as $key => $value) {
                $total = $total + $value->fixed_amount;

                $entry[] = [

                    $value->month, '', '', '', $value->fixed_amount, $total,
                ];

                $double_entry_data = MemberFixedSaving::withoutGlobalScope('bulk_entry')->where('member_id',$ledger_account->member->id)
                ->where('month',$value->month)->where('is_double_entry',1)->get();

                if($double_entry_data->isNotEmpty()){
                    // dd($double_entry_data);
                    // dd($value->double_entry);
                    $entry[] = [
                        $value->month, '', $value->double_entry->particular ?? '', '', $value->double_entry->amount ?? 0, $total + ($value->double_entry->amount??0),
                    ];
                    $total = $total + ($value->double_entry->amount ?? 0);
                }
            }

            $all = ($total + $ledger_account->opening_balance);
            $entry[] = [];
            $entry[]  = ['', '', '', 'TOTAL:', $total, $all];
            $this->main_total['saving'] +=  $total;
            $this->main_total['balance'] +=  $all;
            // dd( $this->main_total['saving'] );

        } else {
            // dd($this->main_total['saving']);
            $entry[] = [];
            $entry[] = ['', '', '', 'Total Balance', $this->main_total['saving'], $this->main_total['balance']];

            //     dd($main_total);

        }

        return $entry;
    }

    public function styles(Worksheet $sheet)
    {
        $data = $this->data;
        $rowIndex = 6;
        $rowTotal = 0;

        // $sheet->mergeCells('A'.$sheet->getHighestRow().':C'.$sheet->getHighestRow());
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'font' => [
                'bold'  =>  true,
                'size'  =>  14,
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
            if (isset($record->ledger_group_id)) {
                $sheet->mergeCells('B' . $rowIndex . ':E' . $rowIndex);
                $sheet->mergeCells('B' . ($rowIndex + 1) . ':E' . ($rowIndex + 1));
                $sheet->mergeCells('B' . ($rowIndex + 2) . ':E' . ($rowIndex + 2));
                $range = 'B' . $rowIndex . ':E' . ($rowIndex + 4);


                $sheet->getStyle($range)->applyFromArray($style);
                $sheet->getStyle('A' . ($rowIndex + 6) . ':A' . ($rowIndex + 8))->applyFromArray($substyle);
                $sheet->getStyle('C' . ($rowIndex + 6) . ':C' . ($rowIndex + 8))->applyFromArray($substyle);
                // $sheet->getStyle('E'. ($rowIndex+25))->applyFromArray($substyle);
                // $sheet->getStyle('F'. ($rowIndex+25))->applyFromArray($substyle);

                $sheet->getStyle('A' . ($rowIndex + 10) . ':F' . ($rowIndex + 10))->applyFromArray($substyle);
                $sheet->getStyle('A' . ($rowIndex + 10) . ':F' . ($rowIndex + 10))->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ],
                ]);

                $records = $record->member->fixed_saving->count();
                $double_entry_count = MemberFixedSaving::withoutGlobalScope('bulk_entry')->where('member_id',$record->member->id)
               ->where('is_double_entry',1)->count();
               $records =  $records +  $double_entry_count ;

                $rowIndex +=  $records + 19;

                // dd($rowIndex);
                $rowTotal = ($rowTotal + $records + 19);

                // $sheet->getStyle('E' . ($rowTotal))->applyFromArray($substyle);
                // $sheet->getStyle('F' . ($rowTotal))->applyFromArray($substyle);

                $sheet->getStyle('D' . ($rowTotal) . ':F' . ($rowTotal))->applyFromArray($substyle);
                $sheet->getStyle('D' . ($rowTotal) . ':F' . ($rowTotal))->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ]
                    ],
                ]);

                $sheet->getStyle('A' . $sheet->getHighestRow() . ':' . $sheet->getHighestColumn() . $sheet->getHighestRow())->getFont()->setBold(true);
            }
        }

        // $sheet->mergeCells('B6:E6');
        // $sheet->mergeCells('B7:E7');
        // $sheet->mergeCells('B8:E8');
        // $range = 'B6:E10';
        // $sheet->getStyle($range)->applyFromArray($style);
        // $sheet->getStyle('C12:C14')->applyFromArray($substyle);
        // $sheet->getStyle('A12:A14')->applyFromArray($substyle);

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
        // $sheet->getStyle('A' . $sheet->getHighestRow() . ':'.$sheet->getHighestColumn().$sheet->getHighestRow())->getFont()->setBold(true);
    }
}
