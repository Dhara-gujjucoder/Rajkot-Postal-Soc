<?php

namespace App\Exports;

use App\Models\Member;
use App\Models\LedgerAccount;
use App\Models\MemberFixedSaving;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LedgerFixedSavingExport implements FromCollection, WithMapping, ShouldAutoSize, WithStrictNullComparison, WithStyles, WithDrawings
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection()
    {
        return LedgerAccount::where('id', $this->id)->get();
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('This is my logo');
        $drawing->setPath(public_path(getSetting()->logo));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A6');

        return $drawing;
    }

    public function map($ledger_account): array
    {
        $entry  = [
            [], [], [], [],
            [],
            ['', getSetting()->title],
            ['', getSetting()->address],
            ['', 'REG. NO.' . $ledger_account->member->registration_no],
            [], [], [],

            ['Name :', $ledger_account->member->name, 'Member No.', $ledger_account->member->uid],
            ['Address :', $ledger_account->member->current_address, 'Ledger', $ledger_account->account_name],
            ['Period', currentYear()->title, 'F.Y', currentYear()->title], [],

            ['Date', 'L/F', 'Particular', 'DR', 'CR', 'Balance'],

            [date('Y-m-d'), '', 'Opening balance', '', '', $ledger_account->opening_balance],
        ];

        $fixed_saving = $ledger_account->member->fixed_saving()->get();
        // dd($fixed_saving );
        $total = 0;
        foreach ($fixed_saving as $key => $value) {
            // dump( $value->double_entry);
            $total =  $total + $value->fixed_amount;

            $entry[] = [
                $value->month, '', '', '', $value->fixed_amount, $total,
            ];
            // withoutGlobalScope('bulk_entry')->

            $double_entry_data = MemberFixedSaving::withoutGlobalScope('bulk_entry')->where('member_id', $ledger_account->member->id)
                ->where('month', $value->month)->where('is_double_entry', 1)->get();

            if ($double_entry_data->isNotEmpty()) {
                // dd($double_entry_data);
                $entry[] = [
                    $value->month, '', ($value->double_entry->particular ?? ''), '', ($value->double_entry->amount ?? 0), $total + ($value->double_entry->amount ?? 0),
                ];
                $total = $total;
                // $total = $total + ($value->double_entry->amount ?? 0);
            }
        }

        $all = ($total + $ledger_account->opening_balance);
        $entry[] = [];
        $entry[]  = ['', '', '', 'TOTAL:', $total, $all];

        return $entry;
    }

    public function styles(Worksheet $sheet)
    {
        // $sheet->mergeCells('A'.$sheet->getHighestRow().':C'.$sheet->getHighestRow());
        $sheet->mergeCells('B6:E6');
        $sheet->mergeCells('B7:E7');
        $sheet->mergeCells('B8:E8');
        $range = 'B6:E10';
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
                'bold'  =>  true,
            ],
        ];
        $sheet->getStyle($range)->applyFromArray($style);
        $sheet->getStyle('C12:C14')->applyFromArray($substyle);
        $sheet->getStyle('A12:A14')->applyFromArray($substyle);
        // $sheet->getStyle('B6:B10')->getFont()->setBold(true);
        // $sheet->getStyle('B6:B10')->getFont()->setBold(true);
        // $sheet->getStyle('A13:A15')->getFont()->setBold(true);
        $sheet->getStyle('A16:F16')->applyFromArray([
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
