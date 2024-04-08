<?php

namespace App\Exports;

use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ShareLedgerExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithStrictNullComparison
{
    public $index = 0, $total_row = 0, $ob = 0, $pshare = 0, $sshare = 0;
    public function collection()
    {
        $data = Member::withTrashed()->orderBy('uid', 'ASC')->active()->get();
        $this->total_row = count($data) + 1;
        $data->push($data[0]);
        return $data;
    }

    public function headings(): array
    {
        $heading = ['M.No', 'Name', 'OB', 'Puch.', 'Sold', 'Net'];
        return $heading;
    }

    public function map($member): array
    {

        if ($member->share_ledger_account !== null) {
            $this->ob = $member->share_ledger_account->sum('opening_balance');
        } else {
            $this->ob = '';
        }

        if ($member->purchased_share !== null ) {
            $this->pshare += $member->purchased_share->sum('share_sum_share_amount');
        } else {
            $this->pshare = '';
        }

        if ( $member->sold_share !== null) {
            $this->sshare += $member->sold_share->sum('share_sum_share_amount');
        } else {
            $this->sshare = '';
        }

        // $this->ob =  $member->share_ledger_account->sum('opening_balance');
        // $this->pshare += $member->purchased_share->sum('share_sum_share_amount');
        // $this->sshare += $member->sold_share->sum('share_sum_share_amount');

        $this->index++;

        $this->ob = (int) $this->ob;
        $this->pshare = (int) $this->pshare;
        $this->sshare = (int) $this->sshare;

        $row2 = [
            '', 'TOTAL:', $this->ob, $this->pshare, $this->sshare, ($this->ob + $this->pshare) - $this->sshare, ''
        ];
        if ($this->index ==  $this->total_row) {
            return [[], $row2];
        } else {

            $opening_balance = $member->share_ledger_account !== null ? $member->share_ledger_account->opening_balance ?? '' : '';
            $purchased_share_amount = $member->purchased_share !== null ? $member->purchased_share->sum('share_sum_share_amount') : '';
            $sold_share_amount = $member->sold_share !== null ? $member->sold_share->sum('share_sum_share_amount') : '';
            // dd($sold_share_amount);

            $balance = '';
            if ($member->share_ledger_account !== null && $member->purchased_share !== null && $member->sold_share !== null) {
                $balance = $opening_balance + $purchased_share_amount - $sold_share_amount;
            }

            return [
                $member->uid,
                $member->name,
                $opening_balance,
                $purchased_share_amount,
                $sold_share_amount,
                $balance,
                // $member->share_ledger_account->opening_balance ?? '',
                // $member->purchased_share->sum('share_sum_share_amount'),
                // $member->sold_share->sum('share_sum_share_amount'),
                // $member->share_ledger_account->opening_balance + $member->purchased_share->sum('share_sum_share_amount') - $member->sold_share->sum('share_sum_share_amount'),
            ];
        }

        // $opening_balance = $member->share_ledger_account->opening_balance;
        // $sold_share = $member->sold_share->sum('share_sum_share_amount');

        // $entry[] = ['','TOTAL:', $member->share_ledger_account->sum('opening_balance'),'','',''];
    }

    public function styles(Worksheet $sheet)
    {
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

        $sheet->getStyle('A' . $sheet->getHighestRow() . ':' . $sheet->getHighestColumn() . $sheet->getHighestRow())->getFont()->setBold(true);
    }
}
