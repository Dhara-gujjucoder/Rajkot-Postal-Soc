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
        // dump($date);

        $reg_members = Member::whereMonth('created_at', $month)->whereYear('created_at', $year)->withTrashed();
        $member_fee = $reg_members->sum('member_fee');
        $member_share = $reg_members->sum('share_amt');

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
                $ms_sum = $value->bulk_entries()->sum('ms');

                if ($fixed_sum > 0 || $principal_sum > 0 || $interest_sum > 0) {
                    if ($fixed_sum > 0) {
                        $entry[] = ['', $value->department_name . '(Fixed saving)', $fixed_sum, $value->department_name . ' Chq.', $value->receipt->exact_amount];
                    }
                    if ($principal_sum > 0) {
                        $entry[] = ['', $value->department->department_name . '(Loan principal)', $principal_sum, '', ''];
                    }
                    if ($interest_sum > 0) {
                        $entry[] = ['', $value->department->department_name . '(Loan interest)', $interest_sum, '', ''];
                    }
                    if ($ms_sum > 0) {
                        $entry[] = ['', $value->department->department_name . '(MS)', $ms_sum, '', ''];
                    }
                }
                // $entry[] = [];
            }
        }

        $all_member_shares = MemberShare::whereHas('share_detail', function ($user) use ($month, $year) {
            $user->where('is_purchase', 1)->whereMonth('created_at', $month)->whereYear('created_at', $year);
        })->get();

        if ($all_member_shares) {

            $members_share =  $all_member_shares->pluck('member_id')->all();
            $members_ids = array_unique($members_share);

            foreach ($members_ids as $key => $id) {
                $share_amt = $all_member_shares->where('member_id', $id)->sum('share_amount') ?? 0;
                $member = $all_member_shares->where('member_id', $id)->first()->member;
                $payment_type_status = '';
                // dd($reg_members->pluck('id')->all());
                if (in_array($id, $reg_members->pluck('id')->all())) {
                    $payment_type_status = $member->payment_type_status;
                }
                $entry[] = ['', $member->name . ' (Share)', $share_amt, $payment_type_status, $member->total];
                $share_total += $share_amt;
            }
        }

        $rdb_bank_total = $member_fee + $share_total;

        if ($member_fee > 0) {
            $entry[] = ['', 'Member Fee', $member_fee, '', ''];
        }

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

        foreach ($this->months as $key => $value) {

            //style for first month heading
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
            $date = explode('-', $value);
            $data[$key]['date'] = $value;

            //add bulk counts
            $bulk_masters = BulkEntryMaster::where('month', $value)->get();
            foreach ($bulk_masters as $key => $value) {
                $value->bulk_entries()->sum('fixed') > 0 ? $row++ : '';
                $value->bulk_entries()->sum('principal') > 0 ? $row++ : '';
                $value->bulk_entries()->sum('ms') > 0 ? $row++ : '';
                $value->bulk_entries()->sum('interest') > 0 ? $row++ : '';
            }

            //add double entry counts from its max value meta entry
            $master_double_entry = MasterDoubleEntry::query()
                ->whereMonth('date', $date[0])->whereYear('date', $date[1])
                ->get();
            if ($master_double_entry) {

                foreach ($master_double_entry as $key => $double_entry) {

                    $credit_entry = $double_entry->meta_entry()->where('type', 'credit')->get();
                    $debit_entry = $double_entry->meta_entry()->where('type', 'debit')->get();

                    $count = max($credit_entry->count(), $debit_entry->count());
                    $row += $count;
                }
                //decreaser 1 row if double entries bcs its overlap to left side
                // $row = $row - 1;
            }

            //add share entry counts
            $row += MemberShare::whereHas('share_detail', function ($user) use ($date) {
                $user->where('is_purchase', 1)->whereMonth('created_at', $date[0])->whereYear('created_at', $date[1]);
            })->get()->count();

            //add member fee count if exist
            if (Member::whereMonth('created_at', $date[0])->whereYear('created_at', $date[1])->withTrashed()->count()) {
                $row += 1;
            }

            //add column margin of heading row
            $row = $row + 4;

            $sheet->getStyle('B' . ($row + 1) . ':E' . ($row + 1))->applyFromArray($substyle);
            $sheet->getStyle('B' . ($row + 1) . ':E' . ($row + 1))->applyFromArray([
                'borders' => [
                    'bottom' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                    'top' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ]
                ],
            ]);
            //add column margin of row of total
            $row = $row + 4;
        }
    }
}
