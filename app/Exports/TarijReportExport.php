<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\Receipt;
use App\Models\BulkEntry;
use App\Models\BulkMaster;
use App\Models\Department;
use App\Models\MemberShare;
use App\Models\LedgerAccount;
use App\Models\BulkEntryMaster;
use App\Models\MasterDoubleEntry;
use App\Models\MemberShareDetail;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class TarijReportExport implements FromCollection, WithMapping, ShouldAutoSize, WithStrictNullComparison, WithStyles
{

    protected $data, $month_from, $month_to, $months;

    public function __construct($month_from, $month_to)
    {
        $result = [];
        $all_months = getMonthsOfYear(currentYear()->id);
        $this->months = collect($all_months)->pluck('value')->toArray();

        if ($month_from != null && $month_to != null) {
            foreach ($all_months as $key => $value) {
                if ($key <= $month_to && $key >= $month_from) {
                    $result[] = $value;
                }
            }
            $this->months = collect($result)->pluck('value')->toArray();
        } elseif ($month_from != null) {
            $this->months = [$all_months[$month_from]['value']];
        } elseif ($month_to != null) {
            $this->months = [$all_months[$month_to]['value']];
        }

        $this->month_from = $month_from;
        $this->month_to = $month_to;
    }

    public function collection()
    {
        $data = [];
        foreach ($this->months as $key => $value) {
            $date = explode('-', $value);

            $data[$key]['date'] = $value;

            $month_from = $this->month_from;
            $month_to = $this->month_to;
            $data[$key]['data'] = MasterDoubleEntry::query()
                ->whereMonth('date', $date[0])->whereYear('date', $date[1])
                ->get();
        }
        // dd($data);
        $this->data = $data;
        return collect($this->data);
    }

    public function map($master_double_entry): array
    {
        $entry = [
            [],
            ['', 'THE RAJKOT POSTAL EMPLOYEE CO-OP CREDIT SOC. LTD.'],
            ['', date("M-Y", strtotime('01-' . $master_double_entry['date']))],
            [],
            ['', 'CREDIT', 'RS.', 'DEBIT', 'RS.']

        ];
        $bulk_master = BulkMaster::where('month', 'like', $master_double_entry['date'])->first();
        $date = explode('-', $master_double_entry['date']);
        $month = $date[0];
        $year = $date[1];
        $member = Member::whereMonth('created_at', $month)->whereYear('created_at', $year);
        $member_fee = $member->sum('member_fee');
        $member_share = $member->sum('share_amt');

        $rdb_bank_total = 0;

        $fixed_saving = $bulk_master->fixed_saving_total ?? 0;
        $loan_interest = $bulk_master->interest_total ?? 0;
        $loan_principal = $bulk_master->principal_total ?? 0;
        $ms_total = $bulk_master->ms_total ?? 0;

        $credit_total = 0;
        $debit_total = 0;
        $share_total = 0;

        $rdb_bank_name = LedgerAccount::where('ledger_group_id', 10)->first();
        if ($bulk_master) {

            $bulk_masters = BulkEntryMaster::where('month', $master_double_entry['date'])->get();  //where('department_id', 1)->
            // $recept = Receipt::where('department_id', 1)->where('month', $master_double_entry['date'])->get();
            foreach ($bulk_masters as $key => $value) {
                $credit_total +=  $value->bulk_entries()->sum('fixed') + $value->bulk_entries()->sum('principal') + $value->bulk_entries()->sum('interest') ?? 0;
                $debit_total += $value->receipt->exact_amount ?? 0;

                $fixed_sum = $value->bulk_entries()->sum('fixed');
                $principal_sum = $value->bulk_entries()->sum('principal');
                $interest_sum = $value->bulk_entries()->sum('interest');

                if ($fixed_sum > 0 || $principal_sum > 0 || $interest_sum > 0) {
                    if ($fixed_sum > 0) {
                        $entry[] = ['', $value->department->department_name . '(Fixed saving)', $fixed_sum, $value->department->department_name . ' Chq.', $value->receipt->exact_amount];
                    }
                    if ($principal_sum > 0) {
                        $entry[] = ['', $value->department->department_name . '(Loan principal)', $principal_sum, '', ''];
                    }
                    if ($interest_sum > 0) {
                        $entry[] = ['', $value->department->department_name . '(Loan interest)', $interest_sum, '', ''];
                    }
                }
                // $entry[] = [];
            }
            // dd($bulk_masters->count());

            $entry[] = ['', 'MS', $ms_total, '', ''];
        }

        $all_member_shares = MemberShare::whereHas('share_detail', function ($user) use ($month, $year) {
            $user->where('is_purchase', 1)->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->get();

        if ($all_member_shares) {

            $members_share =  $all_member_shares->pluck('member_id')->all();
            $members_ids = array_unique($members_share);

            foreach ($members_ids as $key => $id) {
                $share_amt = $all_member_shares->where('member_id', $id)->sum('share_amount') ?? 0;
                $member_name = $all_member_shares->where('member_id', $id)->first()->member->name;
                $entry[] = ['', $member_name . ' (Share)', $share_amt, '', ''];
                $share_total += $share_amt;
            }
        }

        $rdb_bank_total = $member_fee + $share_total;

        $entry[] = ['', 'Member Fee', $member_fee, $rdb_bank_name->account_name, $rdb_bank_total];

        foreach ($master_double_entry['data'] as $key => $double_entry) {

            $credit_entry = $double_entry->meta_entry()->where('type', 'credit')->get();
            $debit_entry = $double_entry->meta_entry()->where('type', 'debit')->get();

            $count = max($credit_entry->count(), $debit_entry->count());

            for ($i = 0; $i < $count; $i++) {

                $credit_particular = isset($credit_entry[$i]) && $credit_entry[$i]->type == 'credit' ? $credit_entry[$i]->particular : '';
                $credit_amount = isset($credit_entry[$i]) && $credit_entry[$i]->type == 'credit' ? $credit_entry[$i]->amount : '';
                $debit_particular = isset($debit_entry[$i]) && $debit_entry[$i]->type == 'debit' ? $debit_entry[$i]->particular : '';
                $debit_amount = isset($debit_entry[$i]) && $debit_entry[$i]->type == 'debit' ? $debit_entry[$i]->amount : '';

                $credit_total += (is_numeric($credit_amount) ? $credit_amount : 0);
                $debit_total += (is_numeric($debit_amount) ? $debit_amount : 0);

                $entry[] = ['', $credit_particular, $credit_amount, $debit_particular, $debit_amount];
            }
        }

        $entry[] = ['', '', '', '', '',];
        $entry[]  = ['', 'TOTAL: ', ($credit_total + $rdb_bank_total), '', ($debit_total + $rdb_bank_total)];  //+$rdb_bank_total
        $entry[] = ['', '', '', '', '',];

        return $entry;
    }

    public function styles(Worksheet $sheet)
    {
        $data = $this->data;
        $sheet->mergeCells('B2:E2');
        $sheet->mergeCells('B3:E3');
        $row = 2;

        $style =[
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

        foreach ($data as $key => $double_entry) {
            $sheet->getStyle('B' . $row . ':E' . $row)->applyFromArray($style);
            $sheet->mergeCells('B' . $row . ':E' . $row);
            $sheet->mergeCells('B' . ($row + 1) . ':E' . ($row + 1));
            $sheet->getStyle('B' . ($row + 1) . ':E' . ($row + 1))->applyFromArray($style);
            $sheet->getStyle('B' . ($row + 3) . ':E' . ($row + 3))->applyFromArray($substyle);
            $sheet->getStyle('B' . ($row + 3) . ':E' . ($row + 3))->applyFromArray([
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


    // public function styles(Worksheet $sheet)
    // {
    //     $data = $this->data;
    //     // dd($data);
    //     $sheet->mergeCells('B2:E2');
    //     $sheet->mergeCells('B3:E3');
    //     $row = 2;
    //     $start = 2;
    //     // $range = 'B6:E10';
    //     $style = [
    //         'alignment' => [
    //             'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    //             'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    //             'wrapText' => true,
    //         ],
    //         'font' => [
    //             'bold'  =>  true,
    //             'size'  =>  14,
    //         ],
    //     ];
    //     $substyle = [
    //         'alignment' => [
    //             // 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
    //             'wrapText' => true,
    //         ],
    //         'font' => [
    //             'bold' => true,
    //         ],
    //     ];
    //     foreach ($data as $key => $double_entry) {
    //         // dd($double_entry);
    //         // if (isset($double_entry['data']) &&  !$double_entry['data']->isEmpty()) {

    //         $sheet->getStyle('B' . $row . ':E' . $row)->applyFromArray($style);
    //         $sheet->mergeCells('B' . $row . ':E' . $row);
    //         $sheet->mergeCells('B' . ($row + 1) . ':E' . ($row + 1));
    //         $sheet->getStyle('B' . ($row + 1) . ':E' . ($row + 1))->applyFromArray($style);
    //         $sheet->getStyle('B' . ($row + 3) . ':E' . ($row + 3))->applyFromArray($substyle);
    //         $sheet->getStyle('B' . ($row + 3) . ':E' . ($row + 3))->applyFromArray([
    //             'borders' => [
    //                 'bottom' => [
    //                     'borderStyle' => Border::BORDER_THIN,
    //                 ],
    //                 'top' => [
    //                     'borderStyle' => Border::BORDER_THIN,
    //                 ]
    //             ],
    //         ]);
    //         $row = $row + 14;

    //         // dump( $row);
    //         $bulk_master = BulkMaster::where('month', $double_entry['date'])
    //             ->first();
    //         // dd($bulk_master );

    //         if ($double_entry['data']->isEmpty() && !$bulk_master) {
    //         } elseif ($double_entry['data']->isEmpty()) {
    //             $row = $row - 4;
    //         }

    //         if (!$bulk_master) {
    //             $row = $row - 6;
    //         } else {
    //             $row = $row - 2;
    //         }
    //         // dump( $row);
    //         // dump( $row);
    //         foreach ($double_entry['data'] as $key => $value) {
    //             $credit_entry = $value->meta_entry()->where('type', 'credit')->get();
    //             $debit_entry = $value->meta_entry()->where('type', 'debit')->get();
    //             $count = max($credit_entry->count(), $debit_entry->count());
    //             $row += $count;
    //         }

    //         $date = explode('-', $double_entry['date']);
    //         $month = $date[0];
    //         $year = $date[1];
    //         if (Member::whereMonth('created_at', $month)->whereYear('created_at', $year)->get()->count()) {
    //             $row = $row + 2;
    //             // dd($row );
    //         }
    //         // dd( $row);
    //         $sheet->getStyle('B' . ($row - 3) . ':E' . ($row - 3))->applyFromArray($substyle);
    //         $sheet->getStyle('B' . ($row - 3) . ':E' . ($row - 3))->applyFromArray([
    //             'borders' => [
    //                 'bottom' => [
    //                     'borderStyle' => Border::BORDER_THIN,
    //                 ],
    //                 'top' => [
    //                     'borderStyle' => Border::BORDER_THIN,
    //                 ]
    //             ],
    //         ]);
    //         // }
    //     }
    // }
}
