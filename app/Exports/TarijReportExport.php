<?php

namespace App\Exports;

use App\Models\MasterDoubleEntry;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class TarijReportExport implements FromCollection, WithMapping, ShouldAutoSize, WithStrictNullComparison, WithStyles
{
    protected $data;

    // public function __construct($id)
    // {
    //     $this->id = $id;
    // }

    public function collection()
    {
        $months =  collect(getMonthsOfYear(currentYear()->id))->pluck('value')->all();
        $data = [];
        foreach ($months as $key => $value) {
            # code...
            $date = explode('-', $value);
            $data[$key]['date'] = $value;
            // if ($value == '02-2024') {

            $data[$key]['data'] = MasterDoubleEntry::whereYear('date', $date[1])
                ->whereMonth('date', $date[0])->get();
            //    dd(date('m',$date[0]),date('Y',$date[1]));
            // }
        }
        // dd($data);
        $this->data = $data;
        return collect($this->data);
    }

    public function map($master_double_entry): array
    {

        // dd($master_double_entry);
        $entry = [
            [],
            ['', 'THE RAJKOT POSTAL EMPLOYEE CO-OP CREDIT SOC. LTD.'],
            ['', date("M-Y", strtotime('01-' . $master_double_entry['date']))],
            [],
            ['', 'CREDIT', 'RS.', 'DEBIT', 'RS.'],

        ];
        if (isset($master_double_entry['data'])) {
            $credit_total = 0;
            $debit_total = 0;

            // dd($master_double_entry['data']);
            foreach ($master_double_entry['data'] as $key => $double_entry) {



                $credit_entry = $double_entry->meta_entry()->where('type', 'credit')->get();
                $debit_entry = $double_entry->meta_entry()->where('type', 'debit')->get();

                $count = max($credit_entry->count(), $debit_entry->count());

                for ($i = 0; $i < $count; $i++) {
                    // $entry[] = ['',($credit_entry[$i]->type == 'credit' ? $credit_entry[$i]->particular  :  ''),'',($value->type == 'debit' ? $value->particular  :  '')];

                    $credit_particular = isset($credit_entry[$i]) && $credit_entry[$i]->type == 'credit' ? $credit_entry[$i]->particular : '';
                    $credit_amount = isset($credit_entry[$i]) && $credit_entry[$i]->type == 'credit' ? $credit_entry[$i]->amount : '';
                    $debit_particular = isset($debit_entry[$i]) && $debit_entry[$i]->type == 'debit' ? $debit_entry[$i]->particular : '';
                    $debit_amount = isset($debit_entry[$i]) && $debit_entry[$i]->type == 'debit' ? $debit_entry[$i]->amount : '';
                    // $credit_total = $credit_total+$credit_amount;
                    // $debit_total   =  $debit_total+$debit_amount;
                    $credit_total += is_numeric($credit_amount) ? $credit_amount : 0;
                    $debit_total += is_numeric($debit_amount) ? $debit_amount : 0;


                    $entry[] = ['', $credit_particular, $credit_amount, $debit_particular, $debit_amount];
                }


                // if ($value->type == 'credit') {
                //     // $credit_total += $value->amount;
                //     $entry[] = ['', $value->particular, $value->amount, ''];
                // } elseif ($value->type == 'debit') {
                //     // $debit_total += $value->amount;
                //     $entry[] = ['', '','', $value->particular, $value->amount];
                // }
                // }


            }
        }



        $entry[] = ['', '', '', '', '',];
        $entry[]  = ['', 'TOTAL', $credit_total, '', $debit_total];

        return $entry;
    }
    public function styles(Worksheet $sheet)
    {
        $data = $this->data;
        $sheet->mergeCells('B2:E2');
        $sheet->mergeCells('B3:E3');
        $row = 2;
        $start = 2;
        // $range = 'B6:E10';
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

        foreach ($data as $key => $double_entry) {
            // dd($double_entry['data']);

            $sheet->getStyle('B' . $row . ':E' . $row)->applyFromArray($style);
            $sheet->mergeCells('B' . $row . ':E' . $row);
            $sheet->mergeCells('B' . ($row + 1) . ':E' . ($row + 1));
            $sheet->getStyle('B' . ($row + 1) . ':E' . ($row + 1))->applyFromArray($style);
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
            $row = $row + 7;

            foreach ($double_entry['data'] as $key => $value) {
                # code...
                $credit_entry = $value->meta_entry()->where('type', 'credit')->get();
                $debit_entry = $value->meta_entry()->where('type', 'debit')->get();
                $count = max($credit_entry->count(), $debit_entry->count());
                $row += $count;
            }
        }
    }
}
